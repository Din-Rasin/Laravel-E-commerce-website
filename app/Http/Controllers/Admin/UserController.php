<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('role', 'like', '%' . $searchTerm . '%');
            });
        }

        $users = $query->orderBy('id', 'desc')->paginate(10);

        // Preserve search query in pagination
        if ($request->has('search')) {
            $users->appends(['search' => $request->search]);
        }

        return view('admin.users.index', compact('users'));
    }

    /**
     * API endpoint to get all users for Postman testing.
     */
    public function apiIndex(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('role', 'like', '%' . $searchTerm . '%');
            });
        }

        // Get all users or paginated results
        if ($request->has('paginate') && $request->paginate === 'false') {
            $users = $query->orderBy('id', 'desc')->get();
        } else {
            $users = $query->orderBy('id', 'desc')->paginate($request->get('per_page', 10));
        }

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully',
            'data' => $users,
            'total_count' => User::count(),
            'admin_count' => User::where('role', 'admin')->count(),
            'user_count' => User::where('role', 'user')->count(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'role' => 'required|in:admin,user',
                'password' => 'required|string|min:8|confirmed',
            ]);

            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput()
                ->with('openAddUserModal', true);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $orders = $user->orders()->with('items')->orderBy('id', 'desc')->paginate(5);
        return view('admin.users.show', compact('user', 'orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ],
                'role' => 'required|in:admin,user',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
            ];

            // Only update password if provided
            if (!empty($validated['password'])) {
                $data['password'] = Hash::make($validated['password']);
            }

            $user->update($data);

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput()
                ->with('openEditUserModal', $user->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Check if user is the last admin
            if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'Cannot delete the last admin user.');
            }

            // Check if user has orders
            if ($user->orders()->count() > 0) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'Cannot delete user with existing orders. Consider deactivating instead.');
            }

            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
