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
                <canvas id="salesChart" width="400" height="150"></canvas>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="page-header d-flex justify-content-between align-items-center">
                            <h3 class="page-title"> Weekly Metrics </h3>
                            <div class="btn-group" role="group">
                                <a href="{{ route('metrics.index',$branch_code) }}" class="btn btn-dark btn-sm">
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
                                        <th>Payroll Tax</th>
                                        <th>Last Year Sale</th>
                                        <th>Current Year Sale</th>
                                        <th>Growth</th>
                                        <th>NDCP %</th>
                                        <th>CML %</th>
                                        <th>Payroll %</th>
                                        <th>Growth %</th>
                                        <th>Update</th>
                                    </tr>
                                </thead>
                                <tbody id="metricsTable">
                                    @foreach($metrics as $metric)
                                    <tr>
                                        <td>{{ $metric->week_start }} - {{ $metric->week_end }}</td>
                                        <td>{{ $metric->ndcp }}</td>
                                        <td>{{ $metric->cml }}</td>
                                        <td>{{ $metric->payrolls }}</td>
                                        <td>{{ $metric->payroll_tax }}</td>
                                        <td>{{ $metric->last_year_sale }}</td>
                                        <td>{{ $metric->current_year_sale }}</td>
                                        <td>{{ $metric->growth }}</td>
                                        <td>{{ $metric->ndcp_percentage }}%</td>
                                        <td>{{ $metric->cml_percentage }}%</td>
                                        <td>{{ $metric->payroll_percentage }}%</td>
                                        <td>{{ $metric->growth_percentage }}%</td>
                                        <td><button class="btn btn-warning edit-btn btn-sm" data-id="{{ $metric->id }}">Edit</button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <thead>
                                    <tr>
                                        <th class="font-weight-normal">Summary</th>
                                        <th>Total NDCP : <span id="total_expected_deposit">{{$total_ndcp}}</span></th>
                                        <th>Total CML : <span id="total_actual_deposit">{{$total_cml}}</span></th>
                                        <th>Total Payrolls : <span id="total_shortage">{{$total_payrolls}}</span></th>
                                        <th>Total Payroll Tax : <span id="total_shortage">{{$total_payroll_tax}}</span></th>
                                        <th>Total Last Year Sales : <span id="total_shortage">{{$total_last_year_sale}}</span></th>
                                        <th>Total Sales : <span id="total_shortage">{{$total_current_year_sale}}</span></th>
                                        <th>Total Growth : <span id="total_shortage">{{$total_growth}}</span></th>
                                        <th>Total NDCP % : <span id="total_shortage">{{$average_ndcp}}</span></th>
                                        <th>Total CML % : <span id="total_shortage">{{$average_cml}}</span></th>
                                        <th>Total Payrolls % : <span id="total_shortage">{{$average_payrolls}}</span></th>
                                        <th>Total Growth % : <span id="total_shortage">{{$average_growth}}</span></th>
                                    </tr>
                                </thead>
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
                            <input type="hidden" class="form-control" name="branch_code" value="{{$branch_code}}" required>
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
                                <label for="payrolls" class="form-label">Payroll Tax</label>
                                <input type="number" class="form-control" name="payroll_tax" required>
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

        <!-- Edit Weekly Metric Modal -->
        <div class="modal fade" id="editMetricModal" tabindex="-1" aria-labelledby="editMetricModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMetricModalLabel">Edit Weekly Metric</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editMetricForm">
                            @csrf
                            <input type="hidden" id="metric_id">
                            <input type="hidden" class="form-control" id="edit_branch_code" name="branch_code" value="{{$branch_code}}" required>
                            <div class="mb-3">
                                <label class="form-label">Week Start</label>
                                <input type="date" class="form-control" id="edit_week_start" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Week End</label>
                                <input type="date" class="form-control" id="edit_week_end" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">NDCP</label>
                                <input type="number" class="form-control" id="edit_ndcp" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">CML</label>
                                <input type="number" class="form-control" id="edit_cml" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payrolls</label>
                                <input type="number" class="form-control" id="edit_payrolls" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payroll Tax</label>
                                <input type="number" class="form-control" id="edit_payroll_tax" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Last Year Sale</label>
                                <input type="number" class="form-control" id="edit_last_year_sale" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Current Year Sale</label>
                                <input type="number" class="form-control" id="edit_current_year_sale" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
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
        var currentYearSale = {!! json_encode($metrics->pluck('current_year_sale')) !!};
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
                        label: 'Sales ',
                        data: currentYearSale,
                        borderColor: 'green',
                        backgroundColor: 'rgba(0, 255, 0, 0.1)',
                        fill: true
                    },
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
                            toastr.error('Error adding metric!', 'Error', { timeOut: 5000 });
                        }
                    }
                });
            });

            $('.edit-btn').on('click', function () {
                let metricId = $(this).data('id');

                $.get(`/weekly-metrics/${metricId}/edit`, function (data) {
                    $('#metric_id').val(data.id);
                    $('#edit_week_start').val(data.week_start);
                    $('#edit_week_end').val(data.week_end);
                    $('#edit_ndcp').val(data.ndcp);
                    $('#edit_cml').val(data.cml);
                    $('#edit_payrolls').val(data.payrolls);
                    $('#edit_payroll_tax').val(data.payroll_tax);
                    $('#edit_last_year_sale').val(data.last_year_sale);
                    $('#edit_current_year_sale').val(data.current_year_sale);
                    $('#editMetricModal').modal('show');
                });
            });

            $('#editMetricForm').on('submit', function (e) {
                e.preventDefault();

                let metricId = $('#metric_id').val();
                let formData = {
                    branch_code: $('#edit_branch_code').val(),
                    week_start: $('#edit_week_start').val(),
                    week_end: $('#edit_week_end').val(),
                    ndcp: $('#edit_ndcp').val(),
                    cml: $('#edit_cml').val(),
                    payrolls: $('#edit_payrolls').val(),
                    payroll_tax: $('#edit_payroll_tax').val(),
                    last_year_sale: $('#edit_last_year_sale').val(),
                    current_year_sale: $('#edit_current_year_sale').val(),
                    _method: 'PUT',
                    _token: "{{ csrf_token() }}"
                };

                $.ajax({
                    url: `/weekly-metrics/${metricId}`,
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            $('#editMetricModal').modal('hide');
                            location.reload();
                        }
                    }
                });
            });

        });
    </script>
    @endpush
</x-app-layout>
