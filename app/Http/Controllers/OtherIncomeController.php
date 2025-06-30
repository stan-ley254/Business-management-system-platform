<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Other_Income;
class OtherIncomeController extends Controller
{
  public function index()
{
    $otherIncomes = Other_Income::where('business_id', auth()->user()->business_id)->latest()->get();
    return view('admin.other_incomes.index', compact('otherIncomes'));
}

public function create()
{
    return view('admin.other_incomes.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'amount' => 'required|numeric',
        'date' => 'required|date',
    ]);

    Other_Income::create([
        'business_id' => auth()->user()->business_id,
        'name' => $request->name,
        'amount' => $request->amount,
        'date' => $request->date,
    ]);

    return redirect()->route('other-incomes.index')->with('success', 'Other income recorded successfully.');
}

public function edit(Other_Income $otherIncome)
{
    return view('admin.other_incomes.create', compact('otherIncome'));
}

public function update(Request $request, Other_Income $otherIncome)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'amount' => 'required|numeric',
        'date' => 'required|date',
    ]);

    $otherIncome->update($request->only('name', 'amount', 'date'));

    return redirect()->route('other-incomes.index')->with('success', 'Other income updated successfully.');
}

public function destroy(Other_Income $otherIncome)
{
    $otherIncome->delete();
    return redirect()->route('other-incomes.index')->with('success', 'Other income deleted successfully.');
}
}
