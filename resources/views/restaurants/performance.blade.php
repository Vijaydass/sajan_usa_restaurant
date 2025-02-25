<x-app-layout>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Performance </h3>
        </div>

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
                            <h3 class="page-title"> Performance </h3>
                            <div class="btn-group" role="group">
                                <a href="{{ route('performance.index',$branch_code) }}" class="btn btn-dark btn-sm">
                                    <i class="mdi mdi-reload"></i> Reload
                                </a>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPerformanceModal">
                                    Add Performance
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-responsive table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sale</th>
                                        <th>Growth</th>
                                        <th>Speed Service</th>
                                        <th>Complaints</th>
                                        <th>OSAT</th>
                                        <th>Redbook</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="metricsTable">
                                    @foreach($performances as $performance)
                                    <tr>
                                        <td>{{ $performance->sale }}</td>
                                        <td>{{ round($performance->growth,2) }} %</td>
                                        <td>{{ $performance->speed_service }}</td>
                                        <td>{{ $performance->complaints }}</td>
                                        <td>{{ $performance->osat }}</td>
                                        <td>{{ $performance->redbook }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm editPerformanceBtn"
                                                data-id="{{ $performance->id }}"
                                                data-weekly_metric_id="{{ $performance->weekly_metric_id }}"
                                                data-speed_service="{{ $performance->speed_service }}"
                                                data-complaints="{{ $performance->complaints }}"
                                                data-osat="{{ $performance->osat }}"
                                                data-redbook="{{ $performance->redbook }}">
                                                <i class="mdi mdi-pencil"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Performance Modal -->
        <div class="modal fade" id="addPerformanceModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Performance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addPerformanceForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Weekly Metric</label>
                                <select class="form-control" required name="weekly_metric_id">
                                    <option value="">Select Weekly Metric</option>
                                    @foreach($weeklyMetrics as $metric)
                                        <option value="{{ $metric->id }}">{{ $metric->week_start }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Speed Of Service</label>
                                <input type="text" name="speed_service" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Complaints</label>
                                <textarea name="complaints" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">OSAT</label>
                                <input type="text" name="osat" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Redbook</label>
                                <input type="text" name="redbook" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Performance</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Performance Modal -->
        <div class="modal fade" id="editPerformanceModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Performance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editPerformanceForm">
                            @csrf
                            <input type="hidden" id="edit_performance_id" name="performance_id">

                            <div class="mb-3">
                                <label class="form-label">Weekly Metric</label>
                                <select class="form-control" required name="weekly_metric_id" id="edit_weekly_metric_id">
                                    <option value="">Select Weekly Metric</option>
                                    @foreach($weeklyMetrics as $metric)
                                        <option value="{{ $metric->id }}">{{ $metric->week_start }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Speed Of Service</label>
                                <input type="text" name="speed_service" id="edit_speed_service" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Complaints</label>
                                <textarea name="complaints" id="edit_complaints" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">OSAT</label>
                                <input type="text" name="osat" id="edit_osat" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Redbook</label>
                                <input type="text" name="redbook" id="edit_redbook" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Performance</button>
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
        var labels = {!! json_encode($performances->pluck('sale')) !!};
        var sales = {!! json_encode($performances->pluck('sale')) !!};
        var growth = {!! json_encode($performances->pluck('growth')) !!};
        var speedService = {!! json_encode($performances->pluck('speed_service')) !!};

        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Sales ',
                        data: sales,
                        borderColor: 'green',
                        backgroundColor: 'rgba(0, 255, 0, 0.1)',
                        fill: true
                    },
                    {
                        label: 'Growth %',
                        data: growth,
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0, 255, 0, 0.1',
                        fill: true
                    },
                    {
                        label: 'Speed Of Service',
                        data: speedService,
                        borderColor: 'yellow',
                        backgroundColor: 'rgba(255, 255, 0, 0.1)',
                        fill: true
                    },
                ]
            },
            options: {
                responsive: true
            }
        });

        $(document).ready(function () {
            $('#addPerformanceForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('performance.store') }}",
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

            $('.editPerformanceBtn').on('click', function () {
                let id = $(this).data('id');
                let weekly_metric_id = $(this).data('weekly_metric_id');
                let speed_service = $(this).data('speed_service');
                let complaints = $(this).data('complaints');
                let osat = $(this).data('osat');
                let redbook = $(this).data('redbook');

                $('#edit_performance_id').val(id);
                $('#edit_weekly_metric_id').val(weekly_metric_id);
                $('#edit_speed_service').val(speed_service);
                $('#edit_complaints').val(complaints);
                $('#edit_osat').val(osat);
                $('#edit_redbook').val(redbook);

                $('#editPerformanceModal').modal('show');
            });

            // Handle Edit Form Submission (AJAX)
            $('#editPerformanceForm').on('submit', function (e) {
                e.preventDefault();

                let performance_id = $('#edit_performance_id').val();
                let formData = $(this).serialize();

                $.ajax({
                    url: '/performance/update/' + performance_id, // Update URL accordingly
                    type: 'PUT',
                    data: formData,
                    success: function (response) {
                        toastr.success(response.message, 'success', { timeOut: 5000 });
                        $('#editPerformanceModal').modal('hide');
                        // location.reload();
                    },
                    error: function (xhr) {
                        toastr.error('Something went wrong!', 'Error', { timeOut: 5000 });
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
