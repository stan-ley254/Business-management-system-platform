<form action="{{ route('admin.income-statement.generate') }}" method="POST">
    @csrf
    <label for="start_date">Start Date</label>
    <input type="date" name="start_date" required>

    <label for="end_date">End Date</label>
    <input type="date" name="end_date" required>

    <button type="submit">Generate Statement</button>
</form>