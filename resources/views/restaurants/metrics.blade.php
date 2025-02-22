<x-app-layout>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Weekly Metrics </h3>
        </div>

        <form method="GET">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-4 pr-2">
                            <input type="date" name="start_date" class="form-control py-2" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-4 px-2">
                            <input type="date" name="end_date" class="form-control py-2" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-4 px-2">
                            <button type="submit" class="btn btn-primary btn-sm">Search</button>
                            <a href="{{ route('metrics.download') }}" class="btn btn-success btn-sm">Download CSV</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="card my-3">
            <div class="card-body">
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="page-header d-flex justify-content-between align-items-center">
                            <h3 class="page-title"> Weekly Metrics </h3>
                            <div class="btn-group" role="group">
                                <a href="{{ route('metrics.index') }}" class="btn btn-dark btn-sm">
                                    <i class="mdi mdi-reload"></i> Reload
                                </a>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMetricModal">
                                    Add Metric
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-responsive table-bordered">
                                <thead>
                                    <tr>
                                        <th>Week</th>
                                        <th>NDCP</th>
                                        <th>CML</th>
                                        <th>Payroll</th>
                                        <th>Last Year Sale</th>
                                        <th>Current Year Sale</th>
                                        <th>Growth</th>
                                        <th>NDCP %</th>
                                        <th>CML %</th>
                                        <th>Payroll %</th>
                                        <th>Growth %</th>
                                    </tr>
                                </thead>
                                <tbody id="metricsTable">
                                    @foreach($metrics as $metric)
                                    <tr>
                                        <td>{{ $metric->week_start }} - {{ $metric->week_end }}</td>
                                        <td>{{ $metric->ndcp }}</td>
                                        <td>{{ $metric->cml }}</td>
                                        <td>{{ $metric->payrolls }}</td>
                                        <td>{{ $metric->last_year_sale }}</td>
                                        <td>{{ $metric->current_year_sale }}</td>
                                        <td>{{ $metric->growth }}</td>
                                        <td>{{ $metric->ndcp_percentage }}%</td>
                                        <td>{{ $metric->cml_percentage }}%</td>
                                        <td>{{ $metric->payroll_percentage }}%</td>
                                        <td>{{ $metric->growth_percentage }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Metric Modal -->
        <div class="modal fade" id="addMetricModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Weekly Metric</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addMetricForm">
                            @csrf
                            <div class="mb-3">
                                <label for="week_start" class="form-label">Week Start</label>
                                <input type="date" class="form-control" name="week_start" required>
                            </div>
                            <div class="mb-3">
                                <label for="week_end" class="form-label">Week End</label>
                                <input type="date" class="form-control" name="week_end" required>
                            </div>
                            <div class="mb-3">
                                <label for="ndcp" class="form-label">NDCP (Truck)</label>
                                <input type="number" class="form-control" name="ndcp" required>
                            </div>
                            <div class="mb-3">
                                <label for="cml" class="form-label">CML (Donuts)</label>
                                <input type="number" class="form-control" name="cml" required>
                            </div>
                            <div class="mb-3">
                                <label for="payrolls" class="form-label">Payrolls</label>
                                <input type="number" class="form-control" name="payrolls" required>
                            </div>
                            <div class="mb-3">
                                <label for="last_year_sale" class="form-label">Last Year Sale</label>
                                <input type="number" class="form-control" name="last_year_sale" required>
                            </div>
                            <div class="mb-3">
                                <label for="current_year_sale" class="form-label">Current Year Sale</label>
                                <input type="number" class="form-control" name="current_year_sale" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Metric</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @endpush
    <style>
        th a {
            text-decoration: none;
            color: black;
        }
    </style>
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var labels = {!! json_encode($metrics->pluck('week_start')) !!};
        var ndcpPercentage = {!! json_encode($metrics->pluck('ndcp_percentage')) !!};
        var cmlPercentage = {!! json_encode($metrics->pluck('cml_percentage')) !!};
        var payrollPercentage = {!! json_encode($metrics->pluck('payroll_percentage')) !!};
        var growthPercentage = {!! json_encode($metrics->pluck('growth_percentage')) !!};

        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'NDCP %',
                        data: ndcpPercentage,
                        borderColor: 'red',
                        backgroundColor: 'rgba(255, 0, 0, 0.1)',
                        fill: true
                    },
                    {
                        label: 'CML %',
                        data: cmlPercentage,
                        borderColor: 'yellow',
                        backgroundColor: 'rgba(255, 255, 0, 0.1)',
                        fill: true
                    },
                    {
                        label: 'Payroll %',
                        data: payrollPercentage,
                        borderColor: 'green',
                        backgroundColor: 'rgba(0, 255, 0, 0.1)',
                        fill: true
                    },
                    {
                        label: 'Growth %',
                        data: growthPercentage,
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0, 255, 0, 0.1',
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true
            }
        });

        $(document).ready(function () {
            $('#addMetricForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('metrics.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert("Error adding metric!");
                        }
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
