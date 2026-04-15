<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\BloodUnit;
use App\Models\BloodInventory;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\DonationApprovedNotification;

class AdminDonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::with(['donor.user', 'bloodUnit'])
            ->latest();

        if ($request->filled('status')) {
            $query->{$request->status}();
        }

        $donations = $query->paginate(15);
        $status = $request->get('status');

        return view('admin.donations.index', compact('donations', 'status'));
    }

    public function approve(Donation $donation)
    {
        if ($donation->status !== 'pending') {
            return redirect()->route('admin.donations.index')
                ->with('error', 'This donation has already been processed.');
        }

        DB::transaction(function () use ($donation) {
            $today = now()->toDateString();

            // Create blood unit
            $bloodUnit = BloodUnit::create([
                'blood_group'  => $donation->donor->blood_group,
                'donor_id'     => $donation->donor_id,
                'collected_at' => $today,
                'expires_at'   => now()->addDays(42)->toDateString(),
                'status'       => 'available',
            ]);

            // Link and approve donation
            $donation->update([
                'blood_unit_id' => $bloodUnit->id,
                'status'        => 'donated',
            ]);

            // Update donor last donation and eligibility
            $donor = Donor::find($donation->donor_id);
            $donor->update([
                'last_donated_at' => now(),
                'is_eligible' => false
            ]);

            // Update inventory
            $inventory = BloodInventory::where('blood_group', $donation->donor->blood_group)->first();
            if ($inventory) {
                $inventory->increment('total_units');
                $inventory->increment('available_units');
            }

            // Generate and Save Certificate
            $data = [
                'donor'    => $donor,
                'donation' => $donation,
                'user'     => $donor->user,
            ];
            $pdf = Pdf::loadView('pdf.certificate', $data);
            $fileName = 'certificate_' . $donation->id . '_' . time() . '.pdf';
            $path = 'certificates/' . $fileName;
            Storage::disk('public')->put($path, $pdf->output());

            $donation->update(['certificate_path' => $path]);

            // Notify donor
            $donor->user->notify(new DonationApprovedNotification($donation));
        });

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation approved and processed.');
    }

    public function reject(Donation $donation)
    {
        if ($donation->status !== 'pending') {
            return redirect()->route('admin.donations.index')
                ->with('error', 'This donation has already been processed.');
        }

        $donation->update(['status' => 'rejected']);

        // Notify donor
        $donation->donor->user->notify(new \App\Notifications\DonationRejectedNotification($donation));

        return redirect()->route('admin.donations.index')
            ->with('success', 'Donation request rejected.');
    }
}

