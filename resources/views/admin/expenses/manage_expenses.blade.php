<form action="{{ isset($expense) ? route('expenses.update', $expense) : route('expenses.store') }}" method="POST">
    @csrf
    @if(isset($expense)) @method('PUT') @endif
    <label>Name</label>
    <input type="text" name="name" value="{{ $expense->name ?? '' }}" required>
    <label>Amount</label>
    <input type="number" name="amount" step="0.01" value="{{ $expense->amount ?? '' }}" required>
    <label>Date</label>
    <input type="date" name="date" value="{{ $expense->date ?? '' }}" required>
    <button type="submit">Save</button>
</form>
