<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodUnit;
use App\Models\BloodInventory;
use App\Models\Donor;
use Illuminate\Http\Request;

class BloodUnitController extends Controller
{
    public function index(Request $request)
    {
        $query = BloodUnit::with('donor.user')->latest();

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('expiring_soon')) {
            $query->expiringSoon(7);
        }

        $units = $query->paginate(15);
        $inventory = BloodInventory::all();

        return view('admin.blood-units.index', compact('units', 'inventory'));
    }

    public function create()
    {
        $donors = Donor::with('user')->get();
        return view('admin.blood-units.create', compact('donors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'blood_group'  => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'donor_id'     => 'nullable|exists:donors,id',
            'collected_at' => 'required|date',
        ]);

        $data['expires_at'] = \Carbon\Carbon::parse($data['collected_at'])->addDays(42)->toDateString();
        $data['status'] = 'available';

        BloodUnit::create($data);

        // Update inventory
        $this->syncInventory($data['blood_group']);

        return redirect()->route('admin.blood-units.index')
                         ->with('success', 'Blood unit added successfully!');
    }

    public function edit(BloodUnit $blood_unit)
    {
        $donors = Donor::with('user')->get();
        return view('admin.blood-units.edit', compact('blood_unit', 'donors'));
    }

    public function update(Request $request, BloodUnit $blood_unit)
    {
        $data = $request->validate([
            'blood_group'  => 'required|in:A+,A-,B+,B-,O+,O-,AB+,AB-',
            'donor_id'     => 'nullable|exists:donors,id',
            'collected_at' => 'required|date',
            'status'       => 'required|in:available,reserved,used,expired',
        ]);

        $oldGroup = $blood_unit->blood_group;
        $data['expires_at'] = \Carbon\Carbon::parse($data['collected_at'])->addDays(42)->toDateString();
        $blood_unit->update($data);

        // Update inventory for both old and new group
        $this->syncInventory($oldGroup);
        if ($oldGroup !== $data['blood_group']) {
            $this->syncInventory($data['blood_group']);
        }

        return redirect()->route('admin.blood-units.index')
                         ->with('success', 'Blood unit updated successfully!');
    }

    public function destroy(BloodUnit $blood_unit)
    {
        $group = $blood_unit->blood_group;
        $blood_unit->delete();
        $this->syncInventory($group);

        return redirect()->route('admin.blood-units.index')
                         ->with('success', 'Blood unit deleted successfully!');
    }

    /**
     * Recalculate inventory for a blood group from blood_units table.
     */
    private function syncInventory(string $group): void
    {
        $total = BloodUnit::where('blood_group', $group)->count();
        $available = BloodUnit::where('blood_group', $group)->available()->count();

        BloodInventory::where('blood_group', $group)->update([
            'total_units'     => $total,
            'available_units' => $available,
        ]);
    }
}
