<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(10);
        return view('admin.users.index', compact('users'), $this->dashboardData());
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'), $this->dashboardData());
    }

    public function store(UserUpdateRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $user->assignRole(Role::findOrFail($data['role']));

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $currentRoleId = $user->roles->first()?->id;
        return view('admin.users.edit', compact('user', 'roles', 'currentRoleId'), $this->dashboardData());
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();
        $user->update($data);
        $user->syncRoles([Role::findOrFail($data['role'])]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete admin users.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    private function dashboardData()
    {
        return [
            'totalDonors'      => \App\Models\Donor::count(),
            'totalHospitals'   => \App\Models\Hospital::where('status', 'approved')->count(),
            'pendingHospitals' => \App\Models\Hospital::where('status', 'pending')->count(),
            'pendingRequests'  => \App\Models\BloodRequest::where('status', 'pending')->count(),
            'inventory'        => \App\Models\BloodInventory::all(),
        ];
    }
}

