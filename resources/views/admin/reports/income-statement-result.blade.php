<h2>Income Statement</h2>
<ul>
    <li>Net Sales: KES {{ number_format($netSales, 2) }}</li>
    <li>Cost of Sales: KES {{ number_format($costOfSales, 2) }}</li>
    <li>Gross Profit: KES {{ number_format($grossProfit, 2) }}</li>
    <li>Other Income: KES {{ number_format($otherIncome, 2) }}</li>
    <li>Expenses: KES {{ number_format($expenses, 2) }}</li>
    <li><strong>Net Profit: KES {{ number_format($netProfit, 2) }}</strong></li>
</ul>

// Blade: admin/expenses/index.blade.php
<a href="{{ route('expenses.create') }}">Add Expense</a>
<table>
    <tr><th>Name</th><th>Amount</th><th>Date</th><th>Actions</th></tr>
    @foreach($expenses as $expense)
        <tr>
            <td>{{ $expense->name }}</td>
            <td>{{ $expense->amount }}</td>
            <td>{{ $expense->date }}</td>
            <td>
                <a href="{{ route('expenses.edit', $expense) }}">Edit</a>
                <form action="{{ route('expenses.destroy', $expense) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>
