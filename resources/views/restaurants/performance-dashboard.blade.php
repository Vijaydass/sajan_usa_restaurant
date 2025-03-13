<x-app-layout>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Restaurant Performance Dashboard </h3>
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
                            <a href="{{ route('performance.dashboard', ['export' => 'csv', 'start_date' => request('start_date'), 'end_date' => request('end_date')])}}" class="btn btn-success btn-sm">Download CSV</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row my-4">
            @foreach ($weeklyMetrics as $metrics)
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="card p-3">
                        <h5 class="fw-bold">PC {{$metrics->branch_code}}</h5>

                        {{-- Sales Growth --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Sales Growth</span>
                            <span class="fw-bold
                                {{ $metrics->total_growth_percentage >= 0 ? 'text-success' : 'text-danger' }}">
                                {{$metrics->total_growth_percentage}}%
                            </span>
                        </div>
                        <div class="progress my-2">
                            <div class="progress-bar
                                {{ $metrics->total_growth_percentage >= 0 ? 'bg-success' : 'bg-danger' }}"
                                style="width: {{ abs($metrics->total_growth_percentage) }}%;">
                            </div>
                        </div>

                        {{-- Sales Details --}}
                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                <small class="text-muted">Current Sale</small>
                                <h5 class="fw-bold">${{ number_format($metrics->total_current_year_sale, 2) }}</h5>
                            </div>
                            <div>
                                <small class="text-muted">Big 2</small>
                                <h5 class="fw-bold">{{ $metrics->total_cml_percentage + $metrics->total_payroll_percentage + $metrics->total_ndcp_percentage }} %</h5>
                            </div>
                            <div>
                                <small class="text-muted">Last Year Sale</small>
                                <h5 class="fw-bold">${{ number_format($metrics->total_last_year_sale, 2) }}</h5>
                            </div>
                        </div>

                        {{-- Percentage Details --}}
                        <div class="d-flex justify-content-between text-center">
                            <div>
                                <small class="text-muted">CML %</small>
                                <h6 class="fw-bold">{{$metrics->total_cml_percentage}}%</h6>
                            </div>
                            <div>
                                <small class="text-muted">Payroll %</small>
                                <h6 class="fw-bold">{{$metrics->total_payroll_percentage}}%</h6>
                            </div>
                            <div>
                                <small class="text-muted">NDCP %</small>
                                <h6 class="fw-bold">{{$metrics->total_ndcp_percentage}}%</h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table class="table table-responsive table-bordered">
                                <thead>
                                    <tr>
                                        <th>Total Sales</th>
                                        <th>Avg Growth % </th>
                                        <th>Avg CML % </th>
                                        <th>Avg Payrolls %  </th>
                                        <th>Avg NDCP % </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>${{number_format($total_sales, 2)}}</strong></td>
                                        <td><span class="text-success">{{$average_growth}}%</span></td>
                                        <td>{{$average_cml}}%</td>
                                        <td>{{$average_payrolls}}%</td>
                                        <td>{{$average_ndcp}}%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @push('scripts')
    <script>
    </script>
    @endpush
</x-app-layout>
