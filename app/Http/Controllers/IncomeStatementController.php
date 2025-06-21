<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Expense;
use App\Models\OtherIncome;
use App\Models\ProductImportLog;
use App\Models\ReturnInward;
use App\Models\ReturnOutward;
use App\Models\StockSnapshot;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class IncomeStatementController extends Controller
{
    //
  public function index()
{
    return view('admin.reports.income-statement');
}

public function generate(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $businessId = auth()->user()->business_id;
    $start = Carbon::parse($request->start_date)->startOfDay();
    $end = Carbon::parse($request->end_date)->endOfDay();

    // Net Sales
    $totalSales = Sales::where('business_id', $businessId)->whereBetween('created_at', [$start, $end])->sum('total');
    $returns = ReturnInward::where('business_id', $businessId)->whereBetween('date', [$start, $end])->sum('amount');
    $netSales = $totalSales - $returns;

    // Cost of Goods Available for Sale
    $openingStock = StockSnapshot::where('business_id', $businessId)->whereDate('created_at', '<=', $start)->latest()->first()->snapshot_value ?? 0;
    $purchases = ProductImportLog::where('business_id', $businessId)->whereBetween('created_at', [$start, $end])->sum(DB::raw('quantity_added * cost_price'));
    $returnOutward = ReturnOutward::where('business_id', $businessId)->whereBetween('date', [$start, $end])->sum('amount');
    $goodsAvailable = $openingStock + $purchases - $returnOutward;

    // Closing Stock
    $closingStock = StockSnapshot::where('business_id', $businessId)->whereDate('created_at', '<=', $end)->latest()->first()->snapshot_value ?? 0;
    $costOfSales = $goodsAvailable - $closingStock;

    // Gross Profit
    $grossProfit = $netSales - $costOfSales;

    // Add Other Income
    $otherIncome = OtherIncome::where('business_id', $businessId)->whereBetween('date', [$start, $end])->sum('amount');

    // Subtract Expenses
    $expenses = Expense::where('business_id', $businessId)->whereBetween('date', [$start, $end])->sum('amount');

    $netProfit = $grossProfit + $otherIncome - $expenses;

    return view('admin.reports.income-statement-result', compact(
        'netSales', 'costOfSales', 'grossProfit', 'otherIncome', 'expenses', 'netProfit'
    ));
}


}
