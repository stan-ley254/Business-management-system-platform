<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OtherIncome;
class OtherIncomeController extends Controller
{
  public function index()
{
    $otherIncomes = OtherIncome::where('business_id', auth()->user()->business_id)->latest()->get();
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

    OtherIncome::create([
        'business_id' => auth()->user()->business_id,
        'name' => $request->name,
        'amount' => $request->amount,
        'date' => $request->date,
    ]);

    return redirect()->route('other-incomes.index')->with('success', 'Other income recorded successfully.');
}

public function edit(OtherIncome $otherIncome)
{
    return view('admin.other_incomes.edit', compact('otherIncome'));
}

public function update(Request $request, OtherIncome $otherIncome)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'amount' => 'required|numeric',
        'date' => 'required|date',
    ]);

    $otherIncome->update($request->only('name', 'amount', 'date'));

    return redirect()->route('other-incomes.index')->with('success', 'Other income updated successfully.');
}

public function destroy(OtherIncome $otherIncome)
{
    $otherIncome->delete();
    return redirect()->route('other-incomes.index')->with('success', 'Other income deleted successfully.');
}
}
