<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $role = $request->input('role', null);
        $search = $request->input('search', '');
        
        $query = User::query();
        
        // Only include pelanggan and apoteker roles
        $query->where(function($q) {
            $q->where('role', User::ROLE_PELANGGAN)
              ->orWhere('role', User::ROLE_APOTEKER);
        });
        
        // Filter by role if provided
        if ($role) {
            $query->where('role', $role);
        }
        
        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.users.index', compact('users', 'role', 'search'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'string', Rule::in([User::ROLE_PELANGGAN, User::ROLE_APOTEKER])],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in([User::ROLE_PELANGGAN, User::ROLE_APOTEKER])],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
        
        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = ['required', Rules\Password::defaults()];
        }
        
        $request->validate($rules);
        
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ];
        
        // Only update password if it's provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        
        $user->update($userData);
        
        return redirect()->route('admin.users.index')
                         ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deletion of admin users
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')
                           ->with('error', 'Pengguna Admin tidak dapat dihapus.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
                         ->with('success', 'Pengguna berhasil dihapus.');
    }
    
    /**
     * Display a listing of customers (pelanggan).
     */
    public function customers(Request $request)
    {
        $search = $request->input('search', '');
        
        $query = User::pelanggan();
        
        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $customers = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.users.customers', compact('customers', 'search'));
    }
    
    /**
     * Display a listing of pharmacists (apoteker).
     */
    public function pharmacists(Request $request)
    {
        $search = $request->input('search', '');
        
        $query = User::apoteker();
        
        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $pharmacists = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.users.pharmacists', compact('pharmacists', 'search'));
    }
}
