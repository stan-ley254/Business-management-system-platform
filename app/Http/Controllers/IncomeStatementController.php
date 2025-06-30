<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Expense;
use App\Models\Other_Income;
use App\Models\ProductImportLog;
use App\Models\ReturnInward;
use App\Models\ReturnOutward;
use App\Models\Stock_Snapshot;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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

    // Count and log data
$salesCount = Sales::where('business_id', $businessId)->whereBetween('created_at', [$start, $end])->count();
$returnInwardCount = ReturnInward::where('business_id', $businessId)->whereBetween('date', [$start, $end])->count();
$returnOutwardCount = ReturnOutward::where('business_id', $businessId)->whereBetween('date', [$start, $end])->count();
$importCount = ProductImportLog::where('business_id', $businessId)->whereBetween('created_at', [$start, $end])->count();
$otherIncomeCount = Other_Income::where('business_id', $businessId)->whereBetween('date', [$start, $end])->count();
$expenseCount = Expense::where('business_id', $businessId)->whereBetween('date', [$start, $end])->count();

$openingSnapshot = Stock_Snapshot::where('business_id', $businessId)->whereDate('created_at', '<=', $start)->latest()->first();
$openingSnapshotDate = $openingSnapshot?->created_at->format('Y-m-d');
$closingSnapshot = Stock_Snapshot::where('business_id', $businessId)->whereDate('created_at', '<=', $end)->latest()->first();
$closingSnapshotDate = $closingSnapshot?->created_at->format('Y-m-d');

    // Net Sales
    $totalSales = Sales::where('business_id', $businessId)->whereBetween('created_at', [$start, $end])->sum('total');
    $returns = ReturnInward::where('business_id', $businessId)->whereBetween('date', [$start, $end])->sum('amount');
    $netSales = $totalSales - $returns;

    // Cost of Goods Available for Sale
    $openingStock = Stock_Snapshot::where('business_id', $businessId)->whereDate('created_at', '<=', $start)->latest()->first()->snapshot_value ?? 0;
    $purchases = ProductImportLog::where('business_id', $businessId)->whereBetween('created_at', [$start, $end])->sum(DB::raw('quantity_added * cost_price'));
    $returnOutward = ReturnOutward::where('business_id', $businessId)->whereBetween('date', [$start, $end])->sum('amount');
    $goodsAvailable = $openingStock + $purchases - $returnOutward;

    // Closing Stock
    $closingStock = Stock_Snapshot::where('business_id', $businessId)->whereDate('created_at', '<=', $end)->latest()->first()->snapshot_value ?? 0;
    $costOfSales = $goodsAvailable - $closingStock;

    // Gross Profit
    $grossProfit = $netSales - $costOfSales;

    // Add Other Income
    $otherIncome = Other_Income::where('business_id', $businessId)->whereBetween('date', [$start, $end])->sum('amount');

    // Subtract Expenses
    $expenses = Expense::where('business_id', $businessId)->whereBetween('date', [$start, $end])->sum('amount');

    $netProfit = $grossProfit + $otherIncome - $expenses;
Log::info('Start:', ['start' => $start->toDateTimeString()]);
Log::info('End:', ['end' => $end->toDateTimeString()]);
Log::info('Import Logs Count:', ['count' => ProductImportLog::where('business_id', $businessId)->whereBetween('created_at', [$start, $end])->count()]);
Log::info('Expenses Count:', ['count' => Expense::where('business_id', $businessId)->whereBetween('date', [$start, $end])->count()]);
    return view('admin.reports.income-statement-result', compact(
    'netSales', 'costOfSales', 'grossProfit', 'otherIncome', 'expenses', 'netProfit',
    'salesCount', 'returnInwardCount', 'returnOutwardCount', 'importCount',
    'otherIncomeCount', 'expenseCount', 'openingSnapshotDate', 'closingSnapshotDate'
));
  
}


}
