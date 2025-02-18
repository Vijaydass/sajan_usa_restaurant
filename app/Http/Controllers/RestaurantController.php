<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin sees all restaurants
            $restaurants = Restaurant::sortable()
                ->when(request('name'),function($q){
                    $q->where('name','LIKE','%'.request('name').'%');
                })
                ->latest()
                ->paginate(request('total_records',10));
        } else {
            // User sees only their assigned restaurants from the pivot table
            $restaurants = $user->restaurants()->sortable()
                ->when(request('name'),function($q){
                    $q->where('name','LIKE','%'.request('name').'%');
                })
                ->latest()
                ->paginate(request('total_records',10));
        }

        return view('restaurants.index', compact('restaurants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('restaurants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'branch_code' => 'required|string|unique:restaurants,branch_code|max:50',
        ]);

        Restaurant::create($request->all());

        return redirect()->route('restaurant.index')->with('success', 'Restaurant created successfully.');
    }

    public function show(Restaurant $restaurant)
    {
        return view('restaurants.show', compact('restaurant'));
    }

    public function edit(Restaurant $restaurant)
    {
        return view('restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'branch_code' => 'required|string|max:50|unique:restaurants,branch_code,' . $restaurant->id,
        ]);

        $restaurant->update($request->all());

        return redirect()->route('restaurant.index')->with('success', 'Restaurant updated successfully.');
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();
        return redirect()->route('restaurant.index')->with('success', 'Restaurant deleted successfully.');
    }
}
