<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
   public function index()
{
    $expenses = Expense::where('business_id', auth()->user()->business_id)->latest()->get();
    return view('admin.expenses.manage_expenses', compact('expenses'));
}

public function create()
{
    return view('admin.expenses.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'amount' => 'required|numeric',
        'date' => 'required|date',
    ]);

    Expense::create([
        'business_id' => auth()->user()->business_id,
        'name' => $request->name,
        'amount' => $request->amount,
        'date' => $request->date,
    ]);

    return redirect()->back()->with('success', 'Expense recorded successfully.');
}

public function edit(Expense $expense)
{
    return view('admin.expenses.manage_expenses', compact('expense'));
}

public function update(Request $request, Expense $expense)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'amount' => 'required|numeric',
        'date' => 'required|date',
    ]);

    $expense->update($request->only('name', 'amount', 'date'));

    return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
}

public function destroy(Expense $expense)
{
    $expense->delete();
    return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
}
}
