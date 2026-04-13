<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\BloodUnit;
use App\Models\BloodInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBloodRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = BloodRequest::with(['hospital.user', 'requestItems'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        $requests = $query->paginate(15);

        return view('admin.blood-requests.index', compact('requests'));
    }

    public function show(BloodRequest $bloodRequest)
    {
        $bloodRequest->load(['hospital.user', 'requestItems']);

        // Compute available stock per requested blood group
        $stockInfo = [];
        foreach ($bloodRequest->requestItems as $item) {
            $inv = BloodInventory::where('blood_group', $item->blood_group)->first();
            $stockInfo[$item->blood_group] = $inv ? $inv->available_units : 0;
        }

        return view('admin.blood-requests.show', compact('bloodRequest', 'stockInfo'));
    }

    public function approve(BloodRequest $bloodRequest)
    {
        if ($bloodRequest->status !== 'pending') {
            return back()->with('error', 'This request is no longer pending.');
        }

        $bloodRequest->update([
            'status'      => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Request #' . $bloodRequest->id . ' has been approved.');
    }

    public function dispatch(BloodRequest $bloodRequest)
    {
        if (!in_array($bloodRequest->status, ['approved'])) {
            return back()->with('error', 'Request must be approved before dispatching.');
        }

        DB::transaction(function () use ($bloodRequest) {
            foreach ($bloodRequest->requestItems as $item) {
                // Find available blood units for this group
                $units = BloodUnit::where('blood_group', $item->blood_group)
                    ->available()
                    ->orderBy('expires_at', 'asc') // Use oldest units first (FIFO)
                    ->take($item->units_requested)
                    ->get();

                $fulfilled = 0;
                foreach ($units as $unit) {
                    $unit->update(['status' => 'used']);
                    $fulfilled++;
                }

                $item->update(['units_fulfilled' => $fulfilled]);
            }

            // Sync inventory for all affected groups
            $groups = $bloodRequest->requestItems->pluck('blood_group')->unique();
            foreach ($groups as $group) {
                $available = BloodUnit::where('blood_group', $group)->available()->count();
                $total = BloodUnit::where('blood_group', $group)->count();
                BloodInventory::where('blood_group', $group)->update([
                    'available_units' => $available,
                    'total_units'     => $total,
                ]);
            }

            $bloodRequest->update([
                'status'        => 'dispatched',
                'dispatched_at' => now(),
            ]);
        });

        return back()->with('success', 'Request #' . $bloodRequest->id . ' has been dispatched. Inventory updated.');
    }

    public function reject(BloodRequest $bloodRequest)
    {
        if ($bloodRequest->status === 'dispatched') {
            return back()->with('error', 'Cannot reject a dispatched request.');
        }

        $bloodRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Request #' . $bloodRequest->id . ' has been rejected.');
    }
}
