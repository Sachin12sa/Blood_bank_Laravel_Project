<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\Donation;
use App\Models\BloodUnit;
use App\Models\BloodInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class DonorController extends Controller
{
    private function getDonor()
    {
        $donor = \App\Models\Donor::firstOrCreate(
            ['user_id' => Auth::id()],
            ['is_eligible' => true]
        );
        $donor->fresh(); // Reload to get ID after create
        return $donor;
    }

    public function dashboard()
    {
        $donor = $this->getDonor();
        return view('donor.dashboard', compact('donor'));
    }

    public function profile()
    {
        $donor = $this->getDonor();
        return view('donor.profile', compact('donor'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'blood_group'   => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'phone'         => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'address'       => 'nullable|string|max:500',
        ]);

        $donor = $this->getDonor();
        $donor->update($request->only('blood_group', 'phone', 'date_of_birth', 'address'));

        // Update user name if provided
        if ($request->filled('name')) {
            Auth::user()->update(['name' => $request->name]);
        }

        return redirect()->route('donor.profile')
                         ->with('success', 'Profile updated successfully!');
    }

    public function donate(Request $request)
    {
        $donor = $this->getDonor();

        // Check 56-day cooldown
        if ($donor->isInCooldown()) {
            $daysLeft = 56 - $donor->last_donated_at->diffInDays(now());
            return back()->with('error', "You are in a cooldown period. You can donate again in {$daysLeft} days.");
        }

        // Check eligibility
        if (!$donor->is_eligible) {
            return back()->with('error', 'You are currently not eligible to donate.');
        }

        // Create pending donation request
        $donation = Donation::create([
            'donor_id'   => $donor->id,
            'donated_at' => now()->toDateString(),
        ]);

        // Notify admin
        $admins = \App\Models\User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\DonationRequestNotification($donation));
        }

        return back()->with('success', 'Donation request submitted! Waiting for admin approval.');
    }

    public function history()
    {
        $donor = $this->getDonor();
        $donations = Donation::where('donor_id', $donor->id)
                             ->with('bloodUnit')
                             ->latest('donated_at')
                             ->paginate(10);

        return view('donor.history', compact('donor', 'donations'));
    }

    public function certificates()
    {
        $donor = $this->getDonor();
        $donations = Donation::where('donor_id', $donor->id)
                             ->with('bloodUnit')
                             ->latest('donated_at')
                             ->get();

        return view('donor.certificates', compact('donor', 'donations'));
    }

    public function downloadCertificate(Donation $donation)
    {
        $donor = $this->getDonor();

        // Ensure the donation belongs to this donor
        if ($donation->donor_id !== $donor->id) {
            abort(403);
        }

        // If saved certificate exists, serve it
        if ($donation->certificate_path && Storage::disk('public')->exists($donation->certificate_path)) {
            return Storage::disk('public')->download($donation->certificate_path, "donation-certificate-{$donation->id}.pdf");
        }

        // Fallback to on-the-fly generation
        $data = [
            'donor'    => $donor,
            'donation' => $donation,
            'user'     => Auth::user(),
        ];

        $pdf = Pdf::loadView('pdf.certificate', $data);

        return $pdf->download("donation-certificate-{$donation->id}.pdf");
    }
}