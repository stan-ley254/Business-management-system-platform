
<div class="container">
    <h2 class="mb-4">Smart Business Analyst</h2>

    <form method="POST" action="{{ route('sales.upload') }}" enctype="multipart/form-data" class="mb-3">
        @csrf
        <div class="input-group">
            <input type="file" name="sales_file" class="form-control" required>
            <button class="btn btn-primary">Upload Sales CSV</button>
        </div>
    </form>

    <form method="POST" action="{{ route('sales.ask') }}">
        @csrf
        <div class="input-group mb-3">
            <input type="text" name="question" class="form-control" placeholder="Ask about your sales..." required>
            <button class="btn btn-success">Ask</button>
        </div>
    </form>

    @if(isset($question))
        <div class="alert alert-secondary">
            <strong>You asked:</strong> {{ $question }}<br>
            <strong>Answer:</strong> {{ $answer }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
</div>

