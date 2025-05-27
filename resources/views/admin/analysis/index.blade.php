
<div class="mb-4">
    <h1 class="h3 text-success">Business Insights</h1>
    <p class="text-muted">Understand your sales and customer behaviors with AI-driven insights.</p>
</div>

<div class="row">
    <!-- Top Products -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                Top 5 Selling Products
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($topProducts as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $product->product->name ?? 'Unknown Product' }}
                            <span class="badge bg-success rounded-pill">{{ $product->total_quantity }} Sold</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Top Customers -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                Top 5 Customers
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach($topCustomers as $customer)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $customer->customer->name ?? 'Unknown Customer' }}
                            <span class="badge bg-success rounded-pill">${{ number_format($customer->total_spent, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Sales Chart -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white">
        Sales Over Time
    </div>
    <div class="card-body">
        <canvas id="salesChart"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($salesByDay->pluck('day')) !!},
            datasets: [{
                label: 'Sales Amount ($)',
                data: {!! json_encode($salesByDay->pluck('total')) !!},
                backgroundColor: 'rgba(0, 128, 0, 0.3)',
                borderColor: 'darkgreen',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
