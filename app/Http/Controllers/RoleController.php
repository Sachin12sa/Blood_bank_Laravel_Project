<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Hospital;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount('users')->with('permissions')->get();
        return view('roles.index', compact('roles'),[
            'totalDonors'      => Donor::count(),
            'totalHospitals'   => Hospital::where('status', 'approved')->count(),
            'pendingHospitals' => Hospital::where('status', 'pending')->count(),
            'pendingRequests'  => BloodRequest::where('status', 'pending')->count(),
            'inventory'        => BloodInventory::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create',[
            'totalDonors'      => Donor::count(),
            'totalHospitals'   => Hospital::where('status', 'approved')->count(),
            'pendingHospitals' => Hospital::where('status', 'pending')->count(),
            'pendingRequests'  => BloodRequest::where('status', 'pending')->count(),
            'inventory'        => BloodInventory::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $data = $request->validated();
        $role = Role::create([
            'name' => $data['name'],
            // 'description' => $data['description'] ?? null,
            'guard_name' => 'web',
        ]);

        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $permissionNames = Permission::whereIn('id', $data['permissions'])->pluck('name');
            $role->syncPermissions($permissionNames);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'),[
            'totalDonors'      => Donor::count(),
            'totalHospitals'   => Hospital::where('status', 'approved')->count(),
            'pendingHospitals' => Hospital::where('status', 'pending')->count(),
            'pendingRequests'  => BloodRequest::where('status', 'pending')->count(),
            'inventory'        => BloodInventory::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, Role $role)
    {
        $data = $request->validated();
        $role->update([
            'name' => $data['name'],
            // 'description' => $data['description'] ?? null,
        ]);

        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $permissionNames = Permission::whereIn('id', $data['permissions'])->pluck('name');
            $role->syncPermissions($permissionNames);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->users_count > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete role that is assigned to users.');
        }

        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully!');
    }
}
