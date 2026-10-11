<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * List all staff accounts.
     */
    public function index(): View
    {
        $users = User::orderBy('id', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form to create a new staff account.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created staff account.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', Rule::in(['super_admin', 'editor', 'support'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Staff account created successfully.');
    }

    /**
     * Show form to edit an existing staff account.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update an existing staff account.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in(['super_admin', 'editor', 'support'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $isActive = $request->boolean('is_active', true);

        // Protection: Prevent demoting or deactivating the last active super admin
        if ($user->isSuperAdmin() && ($validated['role'] !== 'super_admin' || ! $isActive)) {
            $otherSuperAdmins = User::where('id', '!=', $user->id)
                ->whereIn('role', ['super_admin', 'admin'])
                ->where('is_active', true)
                ->count();

            if ($otherSuperAdmins === 0) {
                return back()->with('error', 'Cannot demote or deactivate the last remaining active Super Administrator.');
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->is_active = $isActive;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Staff account updated successfully.');
    }

    /**
     * Toggle the active status of a user.
     */
    public function toggleActive(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        if ($user->is_active && $user->isSuperAdmin()) {
            $otherSuperAdmins = User::where('id', '!=', $user->id)
                ->whereIn('role', ['super_admin', 'admin'])
                ->where('is_active', true)
                ->count();

            if ($otherSuperAdmins === 0) {
                return back()->with('error', 'Cannot deactivate the last remaining active Super Administrator.');
            }
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Account for {$user->name} has been {$statusText}.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->isSuperAdmin()) {
            $otherSuperAdmins = User::where('id', '!=', $user->id)
                ->whereIn('role', ['super_admin', 'admin'])
                ->where('is_active', true)
                ->count();

            if ($otherSuperAdmins === 0) {
                return back()->with('error', 'Cannot delete the last remaining active Super Administrator.');
            }
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "Staff account for {$name} deleted successfully.");
    }
}
