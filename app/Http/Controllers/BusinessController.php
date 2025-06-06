<?php
namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BusinessController extends Controller
{
    public function index(){
        return view('/superadmin.businesses.index');
    }


    public function homeSuperAdmin()
    {
        $businesses = Business::all();
        $businesses = Business::withCount(['users', 'sales', 'products'])->paginate(10);

        $totalBusinesses = Business::count();
        $totalUsers = User::count();
        $activeBusinesses = Business::where('is_active', true)->count();
        return view('/superadmin.businesses.index', compact('businesses', 'totalBusinesses', 'totalUsers', 'activeBusinesses'));
    }
    public function toggleBusiness(Business $business)
    {
        $business->is_active = $business->is_active;
        $business->save();
    
        return redirect()->back()->with('success', 'Business status updated successfully.');
    }

    public function showSetDueDateForm()
{
    $businesses = Business::all(['id', 'name']);
    return view('superadmin.businesses.set_due_dates', compact('businesses'));
}

public function updateDueDate(Request $request)
{
    $request->validate([
        'business_id' => 'required|exists:businesses,id',
        'next_payment_due' => 'required|date',
    ]);

    $business = Business::findOrFail($request->business_id);
    $business->next_payment_due = Carbon::parse($request->next_payment_due);
     $business->is_active = true; // Reactivate upon valid payment
    $business->save();

    return redirect()->back()->with('success', 'Due date updated successfully for ' . $business->name);
}
    
    public function create()
    {
        return view('superadmin.businesses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:businesses,name',
        ]);
        Business::create($request->only('name'));
        return redirect()->route('superadmin.businesses.index')->with('success', 'Business created');
    }

    public function edit(Business $business)
    {
        return view('superadmin.businesses.edit', compact('business'));
    }

    public function update(Request $request, Business $business)
    {
        $business->update($request->only('name'));
        return redirect()->route('superadmin.businesses.index')->with('success', 'Updated successfully');
    }

    public function destroy(Business $business)
    {
        $business->delete();
        return back()->with('success', 'Deleted successfully');
    }
}

