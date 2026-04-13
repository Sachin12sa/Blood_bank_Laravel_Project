<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\BloodRequest;
use App\Models\RequestItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BloodInventory;
use App\Models\Donor;
use App\Notifications\BloodShortageNotification;
use Illuminate\Support\Facades\Notification;

class HospitalController extends Controller
{
    private function getHospital()
    {
        return Hospital::where('user_id', Auth::id())->first();
    }

    public function dashboard()
    {
        $hospital = $this->getHospital();
        $stats = null;

        if ($hospital && $hospital->isApproved()) {
        $stats = [
                'total'      => $hospital->bloodRequests()->count(),
                'pending'    => $hospital->bloodRequests()->where('status', 'pending')->count(),
                'approved'   => $hospital->bloodRequests()->where('status', 'approved')->count(),
                'dispatched' => $hospital->bloodRequests()->where('status', 'dispatched')->count(),
                'received'   => $hospital->bloodRequests()->where('status', 'received')->count(),
                'rejected'   => $hospital->bloodRequests()->where('status', 'rejected')->count(),
            ];
        }

        $recentRequests = $hospital?->bloodRequests()
            ->with('requestItems')
            ->latest()
            ->take(5)
            ->get() ?? collect();

        return view('hospital.dashboard', compact('hospital', 'stats', 'recentRequests'));
    }

    public function profile()
    {
        $hospital = $this->getHospital();
        return view('hospital.profile', compact('hospital'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'hospital_name'  => 'required|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:500',
        ]);

        $hospital = $this->getHospital();
        $hospital->update($request->only('hospital_name', 'phone', 'address'));

        if ($request->filled('name')) {
            Auth::user()->update(['name' => $request->name]);
        }

        return redirect()->route('hospital.profile')
                         ->with('success', 'Profile updated successfully!');
    }

    public function myRequests()
    {
        $hospital = $this->getHospital();
        $requests = $hospital->bloodRequests()
            ->with('requestItems')
            ->latest()
            ->paginate(10);

        return view('hospital.requests.index', compact('hospital', 'requests'));
    }

    public function createRequest()
    {
        $hospital = $this->getHospital();

        if (!$hospital->isApproved()) {
            return redirect()->route('hospital.dashboard')
                             ->with('error', 'Your hospital must be approved before submitting requests.');
        }

        return view('hospital.requests.create', compact('hospital'));
    }

    public function storeRequest(Request $request)
    {
        $hospital = $this->getHospital();

        if (!$hospital->isApproved()) {
            return redirect()->route('hospital.dashboard')
                             ->with('error', 'Your hospital must be approved before submitting requests.');
        }

        $request->validate([
            'urgency'              => 'required|in:normal,critical',
            'notes'                => 'nullable|string|max:1000',
            'items'                => 'required|array|min:1',
            'items.*.blood_group'  => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'items.*.units'        => 'required|integer|min:1|max:100',
        ]);

        DB::transaction(function () use ($request, $hospital) {
            $bloodRequest = BloodRequest::create([
                'hospital_id' => $hospital->id,
                'urgency'     => $request->urgency,
                'notes'       => $request->notes,
                'status'      => 'pending',
            ]);

            foreach ($request->items as $item) {
                RequestItem::create([
                    'blood_request_id' => $bloodRequest->id,
                    'blood_group'      => $item['blood_group'],
                    'units_requested'  => $item['units'],
                    'units_fulfilled'  => 0,
                ]);

                // Check if the request is critical and inventory for this group is 0 or missing
                $inventory = BloodInventory::where('blood_group', $item['blood_group'])->first();
                if ($request->urgency === 'critical' && (!$inventory || $inventory->available_units == 0)) {
                    $eligibleDonors = Donor::where('blood_group', $item['blood_group'])
                                           ->eligible()
                                           ->with('user')
                                           ->get();

                    if ($eligibleDonors->isNotEmpty()) {
                        Notification::send($eligibleDonors, new BloodShortageNotification($item['blood_group'], $bloodRequest));
                    }
                }
            }
        });

        return redirect()->route('hospital.requests.index')
                         ->with('success', 'Blood request submitted successfully!');
    }

    public function showRequest(BloodRequest $bloodRequest)
    {
        $hospital = $this->getHospital();

        if ($bloodRequest->hospital_id !== $hospital->id) {
            abort(403);
        }

        $bloodRequest->load('requestItems');

        return view('hospital.requests.show', compact('hospital', 'bloodRequest'));
    }

    public function updateStatus(Request $request, BloodRequest $bloodRequest)
    {
        $hospital = $this->getHospital();

        if ($bloodRequest->hospital_id !== $hospital->id) {
            abort(403);
        }

        if ($bloodRequest->status !== 'dispatched') {
            return back()->with('error', 'Only dispatched requests can be marked as received.');
        }

        $bloodRequest->update([
            'status' => 'received',
            'received_at' => now(),
        ]);

        return back()->with('success', 'Request #' . $bloodRequest->id . ' marked as received successfully!');
    }
}
