<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Employee;
use App\Models\Maintenance;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'branch_code' => 'required|string|unique:restaurants,branch_code|max:50',
        ]);

        Restaurant::create($request->all());

        return redirect()->route('restaurant.index')->with('success', 'Restaurant created successfully.');
    }

    public function show(Restaurant $restaurant)
    {
        $deposites = [];
        return view('restaurants.show', compact('restaurant','deposites'));
    }

    public function edit(Restaurant $restaurant)
    {
        return view('restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
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

    public function depositStore(Request $request)
    {
        $request->validate([
            'branch_code' => 'required|string|exists:restaurants,branch_code',
            'expected_deposit' => 'required|numeric|min:1',
            'actual_deposit' => 'required|numeric|min:0',
            'comments' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Calculate shortage
            $shortage = $request->expected_deposit - $request->actual_deposit;

            // Create deposit record
            $deposit = new Deposit();
            $deposit->branch_code = $request->branch_code;
            $deposit->expected_deposit = $request->expected_deposit;
            $deposit->actual_deposit = $request->actual_deposit;
            $deposit->shortage = $shortage;
            $deposit->comments = $request->comments;
            $deposit->save();

            DB::commit();

            return response()->json(['message' => 'Deposit successful', 'deposit' => $deposit], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }

    public function deposits(Restaurant $restaurant)
    {
        $query = Deposit::where('branch_code', $restaurant->branch_code);

        // Apply filters
        $query->when(request('expected_deposit'), function ($q) {
            $q->where('expected_deposit', request('expected_deposit'));
        })
        ->when(request('actual_deposit'), function ($q) {
            $q->where('actual_deposit', request('actual_deposit'));
        })
        ->when(request('shortage'), function ($q) {
            $q->where('shortage', request('shortage'));
        })
        ->when(request('comments'), function ($q) {
            $q->where('comments', 'like', '%' . request('comments') . '%');
        })
        ->when(request('created_at'), function ($q) {
            $q->whereDate('created_at', request('created_at'));
        })
        ->when(request('start_date') && request('end_date'), function ($q) {
            $q->whereBetween('created_at', [request('start_date') . ' 00:00:00', request('end_date') . ' 23:59:59']);
        });

        // Paginate the deposits
        $deposites = $query->sortable()->latest()->paginate(request('total_records', 10));

        // Calculate totals
        $total_expected = $query->sum('expected_deposit');
        $total_actual = $query->sum('actual_deposit');
        $total_shortage = $query->sum('shortage');

        return view('restaurants.deposit', compact('restaurant', 'deposites', 'total_expected', 'total_actual', 'total_shortage'));
    }

    public function depositUpdate(Request $request, $id)
    {
        $request->validate([
            'expected_deposit' => 'required|numeric',
            'actual_deposit' => 'required|numeric',
            'comments' => 'nullable|string',
        ]);

        $deposit = Deposit::findOrFail($id);

        $shortage = $request->expected_deposit - $request->actual_deposit;

        $deposit->update([
            'expected_deposit' => $request->expected_deposit,
            'actual_deposit' => $request->actual_deposit,
            'shortage' => $shortage,
            'comments' => $request->comments,
        ]);

        return response()->json(['success' => true, 'message' => 'Deposit updated successfully!']);
    }

    public function maintenances(Restaurant $restaurant)
    {
        $query = Maintenance::where('branch_code', $restaurant->branch_code);

        // Apply filters
        $query->when(request('equipment_name'), function ($q) {
            $q->where('equipment_name', 'like', '%' . request('equipment_name') . '%');
        })
        ->when(request('payment_type'), function ($q) {
            $q->where('payment_type', request('payment_type'));
        })
        ->when(request('status'), function ($q) {
            $q->where('status', request('status'));
        })
        ->when(request('reason'), function ($q) {
            $q->where('reason', 'like', '%' . request('reason') . '%');
        })
        ->when(request('created_at'), function ($q) {
            $q->whereDate('created_at', request('created_at'));
        })
        ->when(request('start_date') && request('end_date'), function ($q) {
            $q->whereBetween('created_at', [
                request('start_date') . ' 00:00:00',
                request('end_date') . ' 23:59:59'
            ]);
        });

        // Paginate the results
        $maintenances = $query->sortable()->latest()->paginate(request('total_records', 10));

        return view('restaurants.maintenance', compact('restaurant', 'maintenances'));
    }

    public function maintenanceStore(Request $request)
    {
        $request->validate([
            'branch_code' => 'required|string|exists:restaurants,branch_code',
            'equipment_name' => 'required|string',
            'payment_type' => 'required|in:cash,credit,debit',
            'reason' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,on_hold,awaiting_approval,scheduled,cancelled,completed,done',
        ]);

        try {
            DB::beginTransaction();

            $maintenance = new Maintenance();
            $maintenance->branch_code = $request->branch_code;
            $maintenance->equipment_name = $request->equipment_name;
            $maintenance->payment_type = $request->payment_type;
            $maintenance->reason = $request->reason;
            $maintenance->status = $request->status;
            $maintenance->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Maintenance added successfully', 'maintenance' => $maintenance], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }


    public function maintenanceUpdate(Request $request, $id)
    {
        $request->validate([
            'equipment_name' => 'required|string',
            'payment_type' => 'required|in:cash,credit,debit',
            'reason' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,on_hold,awaiting_approval,scheduled,cancelled,completed,done',
        ]);

        $deposit = Maintenance::findOrFail($id);

        $deposit->update([
            'equipment_name' => $request->equipment_name,
            'payment_type' => $request->payment_type,
            'reason' => $request->reason,
            'status' => $request->status,
        ]);

        return response()->json(['success' => true, 'message' => 'Maintenance updated successfully!']);
    }

    public function employee(Restaurant $restaurant)
    {
        $query = Employee::where('branch_code', $restaurant->branch_code);

        // Apply filters
        $query->when(request('name'), function ($q) {
            $q->where('name', 'like', '%' . request('name') . '%');
        })
        ->when(request('email'), function ($q) {
            $q->where('email', 'like', '%' . request('email') . '%');
        })
        ->when(request('designation'), function ($q) {
            $q->where('designation', request('designation'));
        })
        ->when(request('start_date') && request('end_date'), function ($q) {
            $q->whereBetween('start_date', [
                request('start_date') . ' 00:00:00',
                request('end_date') . ' 23:59:59'
            ]);
        });

        // Paginate the results
        $employees = $query->sortable()->latest()->paginate(request('total_records', 10));

        return view('restaurants.employee', compact('restaurant', 'employees'));
    }

    public function employeeStore(Request $request)
    {
        $request->validate([
            'branch_code' => 'required|string|exists:restaurants,branch_code',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'designation' => 'required|string|max:255',
            'address' => 'required|string',
            'ssn' => 'required|string|unique:employees,ssn',
            'pay_rate' => 'required|numeric',
            'dob' => 'required|date',
            'routing_number' => 'required|string',
            'account_number' => 'required|string',
            'bank' => 'required|string',
            'mobile' => 'required|string',
            'start_date' => 'required|date',
        ]);

        try {
            Employee::create($request->all());

            return response()->json(['success' => true, 'message' => 'Employee added successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }
}
