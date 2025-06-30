<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>
        .sidebar {
            position: fixed;
        }
        .form_color {
            color: #ffffff;
        }
    </style>
</head>
<body>
@include('admin.sidebar')
@include('admin.header')
    <div class="main-panel">
      
        <div class="content-wrapper">
            <div class="message d-print-inline-flex rounded">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            <div class="container-md mt-2">
                <div class="card">
<div class="card-body">
       <h2>Income Statement</h2>
    <ul class="nav flex-column sub-menu">
    <li>Net Sales: KES {{ number_format($netSales, 2) }}</li>
    <li>Cost of Sales: KES {{ number_format($costOfSales, 2) }}</li>
    <li>Gross Profit: KES {{ number_format($grossProfit, 2) }}</li>
    <li>Other Income: KES {{ number_format($otherIncome, 2) }}</li>
    <li>Expenses: KES {{ number_format($expenses, 2) }}</li>
    <li><strong>Net Profit: KES {{ number_format($netProfit, 2) }}</strong></li>
</ul>

<hr>
<h3>Data Summary</h3>
<ul class="nav flex-column sub-menu">
    <li>Total Sales Records: {{ $salesCount }}</li>
    <li>Total Return Inwards: {{ $returnInwardCount }}</li>
    <li>Total Return Outwards: {{ $returnOutwardCount }}</li>
    <li>Total Product Imports: {{ $importCount }}</li>
    <li>Total Stock Snapshots Used: Opening - {{ $openingSnapshotDate ?? 'N/A' }}, Closing - {{ $closingSnapshotDate ?? 'N/A' }}</li>
    <li>Total Other Income Records: {{ $otherIncomeCount }}</li>
    <li>Total Expense Records: {{ $expenseCount }}</li>
</ul>
</div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.script')
</body>
</html>
