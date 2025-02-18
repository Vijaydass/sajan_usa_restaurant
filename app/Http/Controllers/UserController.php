<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::sortable()
        ->when(request('email'),function($q){
            $q->where('email','LIKE','%'.request('email').'%');
        })
        ->when(request('name'),function($q){
            $q->where('name','LIKE','%'.request('name').'%');
        })
        ->when(request('role'),function($q){
            $q->where('role','LIKE','%'.request('role').'%');
        })
        ->latest()
        ->paginate(request('total_records',10));
        return view('users.index', compact('users'));
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user)
    {
        $restaurants = Restaurant::all();
        return view('users.create',compact('restaurants','user'));
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,user',
            'password' => 'required|string|min:8|confirmed',
            'restaurants' => 'nullable|array',
        ]);

        // Store the user in the database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        if ($request->role === 'user' && $request->has('restaurants')) {
            $user->restaurants()->sync($request->restaurants);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user')); // Show user details
    }

    public function edit(User $user)
    {
        $restaurants = Restaurant::all();
        return view('users.edit', compact('user','restaurants')); // Show edit user form
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:8|confirmed',
            'restaurants' => 'nullable|array',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $request->password ? Hash::make($request->password) : $user->password, // Only update password if provided
        ]);

        if ($request->role === 'user' && $request->has('restaurants')) {
            $user->restaurants()->sync($request->restaurants);
        } else {
            $user->restaurants()->detach(); // Remove restaurants if role is changed to admin
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $user->delete(); // Delete the user
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
