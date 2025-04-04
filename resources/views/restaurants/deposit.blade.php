<x-app-layout>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Deposits </h3>
            <a href="{{ route('restaurant.index') }}" class="btn btn-primary">Restaurants</a>
        </div>
        <div class="row">
            <div class="col-12 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white">
                    <div class="card-body">
                        <h1 class="font-weight-normal mb-3" style="color: #683817">
                            Welcome to Branch
                            <span class="font-weight-bold float-end" style="color: #f5821f">{{$restaurant->branch_code}}</span>
                        </h1>
                        <h3 class="font-weight-normal" style="color: #683817">Details and operations specific to Branch <span class="text-warning">{{$restaurant->name}}</span></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 stretch-card grid-margin">
                <div class="card">
                    <div class="card-body">
                        <form id="searchForm">
                            <div class="row align-items-center">
                                <!-- Start Date -->
                                <div class="col-md-4">
                                    <label for="start_date" class="form-label">Start Date:</label>
                                    <input type="date" id="start_date" name="start_date" class="form-control p-2" value="">
                                </div>

                                <!-- End Date -->
                                <div class="col-md-4">
                                    <label for="end_date" class="form-label">End Date:</label>
                                    <input type="date" id="end_date" name="end_date" class="form-control p-2" value="">
                                </div>

                                <!-- Fetch Button -->
                                <div class="col-md-2 mt-4">
                                    <button type="button" class="btn btn-primary submit-search-form btn-sm">Fetch</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="page-header d-flex justify-content-between align-items-center">
                            <h3 class="page-title"> Deposit Details </h3>
                            <div class="btn-group" role="group">
                                <a href="{{ route('restaurant.deposit', $restaurant->id) }}" class="btn btn-dark btn-sm">
                                    <i class="mdi mdi-reload"></i> Reload
                                </a>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#depositModal">
                                    Add New Deposit
                                </button>
                                <a href="{{ route('deposit.download',['restaurant' => $restaurant->id, 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-success btn-sm">Download CSV</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-responsive table-bordered">
                                <thead>
                                    <tr>
                                        <th>@sortablelink('created_at', 'Date')</th>
                                        <th>@sortablelink('expected_deposit', 'Expected Deposit')</th>
                                        <th>@sortablelink('actual_deposit', 'Actual Deposit')</th>
                                        <th>@sortablelink('shortage', 'Shortage')</th>
                                        <th>@sortablelink('comments', 'Comments')</th>
                                        <th>@sortablelink('deposited_by', 'Deposited by')</th>
                                        <th>Update</th>
                                    </tr>
                                    <form id="filterForm">
                                        <input type="hidden" id="table_sort" name="sort">
                                        <input type="hidden" id="table_order" name="order">
                                        <input type="hidden" id="total_records" name="total_records">
                                        <tr>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Date" name="created_at" type="date" value="{{request('created_at','')}}" />
                                            </th>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Expected Deposit" name="expected_deposit" type="text" value="{{ request('expected_deposit', '') }}" />
                                            </th>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Actual Deposit" name="actual_deposit" type="text" value="{{ request('actual_deposit', '') }}" />
                                            </th>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Shortage" name="shortage" type="text" value="{{ request('shortage', '') }}" />
                                            </th>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Comments" name="comments" type="text" value="{{ request('comments', '') }}" />
                                            </th>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Deposited by" name="deposited_by" type="text" value="{{ request('deposited_by', '') }}" />
                                            </th>
                                            <th></th>
                                        </tr>
                                    </form>
                                </thead>
                                <tbody>
                                    @foreach($deposites as $deposit)
                                        <tr>
                                            <td>{{ $deposit->created_at->format('d-m-Y') }}</td>
                                            <td>{{ $deposit->expected_deposit }}</td>
                                            <td>{{ $deposit->actual_deposit }}</td>
                                            <td>{{ $deposit->shortage }}</td>
                                            <td>{{ ucfirst($deposit->comments) }}</td>
                                            <td>{{ ucfirst($deposit->deposited_by) }}</td>
                                            <td>
                                                <button class="btn btn-warning btn-sm editDeposit"
                                                    data-id="{{ $deposit->id }}"
                                                    data-expected_deposit="{{ $deposit->expected_deposit }}"
                                                    data-actual_deposit="{{ $deposit->actual_deposit }}"
                                                    data-comments="{{ $deposit->comments }}"
                                                    data-deposited_by="{{ $deposit->deposited_by }}"
                                                    data-created_at="{{ $deposit->created_at }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editDepositModal">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <thead>
                                    <tr>
                                        <th class="font-weight-normal">Summary</th>
                                        <th>$<span id="total_expected_deposit">{{$total_expected}}</span></th>
                                        <th>$<span id="total_actual_deposit">{{$total_actual}}</span></th>
                                        <th>$<span id="total_shortage">{{$total_shortage}}</span></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        {{ $deposites->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Deposit Modal -->
        <div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="depositModalLabel">Add New Deposit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="depositForm">
                            <input type="hidden" class="form-control" id="branch_code" placeholder="Enter branch code" required value="{{$restaurant->branch_code}}">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" placeholder="Enter date" required>
                            </div>
                            <div class="mb-3">
                                <label for="expected_deposit" class="form-label">Expected Deposit</label>
                                <input type="number" class="form-control" id="expected_deposit" placeholder="Enter expected deposit" required>
                            </div>
                            <div class="mb-3">
                                <label for="actual_deposit" class="form-label">Actual Deposit</label>
                                <input type="number" class="form-control" id="actual_deposit" placeholder="Enter actual deposit" required>
                            </div>
                            <div class="mb-3">
                                <label for="comments" class="form-label">Comments</label>
                                <textarea class="form-control" id="comments" rows="3" placeholder="Enter any comments"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="deposit_by" class="form-label">Deposited by</label>
                                <input type="text" class="form-control" id="deposited_by" placeholder="Enter deposited by" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveDeposit">Save Deposit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editDepositModal" tabindex="-1" aria-labelledby="editDepositModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDepositModalLabel">Edit Deposit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editDepositForm">
                            @csrf
                            <input type="hidden" id="edit_deposit_id">

                            <div class="mb-3">
                                <label for="edit_expected_deposit" class="form-label">Expected Deposit</label>
                                <input type="text" class="form-control" id="edit_expected_deposit" name="expected_deposit">
                            </div>

                            <div class="mb-3">
                                <label for="edit_actual_deposit" class="form-label">Actual Deposit</label>
                                <input type="text" class="form-control" id="edit_actual_deposit" name="actual_deposit">
                            </div>

                            <div class="mb-3">
                                <label for="edit_comments" class="form-label">Comments</label>
                                <textarea class="form-control" id="edit_comments" name="comments"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="edit_date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="edit_date" name="created_at">
                            </div>

                            <div class="mb-3">
                                <label for="edit_comments" class="form-label">Deposited By</label>
                                <input type="text" class="form-control" id="edit_deposited_by" name="deposited_by">
                            </div>

                            <button type="submit" class="btn btn-primary">Update Deposit</button>
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
    <script>
        $('.input-filter').change(function() {
            $('#filterForm').submit();
        });
        $('.submit-search-form').click(function() {
            $('#searchForm').submit();
        });

        document.getElementById('saveDeposit').addEventListener('click', function () {
            let data = {
                branch_code: document.getElementById('branch_code').value,
                expected_deposit: document.getElementById('expected_deposit').value,
                actual_deposit: document.getElementById('actual_deposit').value,
                deposited_by: document.getElementById('deposited_by').value,
                comments: document.getElementById('comments').value,
                created_at: document.getElementById('date').value
            };

            fetch('/deposits', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    let errorMessages = Object.values(data.errors).flat().join('<br>');
                    toastr.error(errorMessages, 'Error', { timeOut: 5000 });
                } else {
                    toastr.success('Deposit saved successfully!', 'Success', { timeOut: 3000 });
                    setTimeout(() => location.reload(), 2000);
                }
            })
            .catch(error => {
                toastr.error('Something went wrong!', 'Error', { timeOut: 5000 });
                console.error('Error:', error);
            });
        });

        $(document).ready(function() {
            // Open modal and fill form with existing data
            $(".editDeposit").click(function() {
                $("#edit_deposit_id").val($(this).data("id"));
                $("#edit_expected_deposit").val($(this).data("expected_deposit"));
                $("#edit_actual_deposit").val($(this).data("actual_deposit"));
                $("#edit_deposited_by").val($(this).data("deposited_by"));
                let editDate = $(this).data("created_at"); // Get timestamp
                let date = editDate ? editDate.split(' ')[0] : '';
                console.log(date);
                $("#edit_date").val(date);
                $("#edit_comments").val($(this).data("comments"));
            });

            // Submit edited deposit data via AJAX
            $("#editDepositForm").submit(function(e) {
                e.preventDefault();

                let depositId = $("#edit_deposit_id").val();
                let formData = {
                    _token: "{{ csrf_token() }}",
                    expected_deposit: $("#edit_expected_deposit").val(),
                    actual_deposit: $("#edit_actual_deposit").val(),
                    deposited_by: $("#edit_deposited_by").val(),
                    created_at: $("#edit_date").val(),
                    comments: $("#edit_comments").val()
                };

                $.ajax({
                    url: `/deposits/${depositId}`,
                    type: "PUT",
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success("Deposit updated successfully!");
                            $("#editDepositModal").modal("hide");
                            location.reload();
                        } else {
                            toastr.error("Failed to update deposit.");
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Error updating deposit.");
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
