<x-app-layout>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Maintenances </h3>
            <a href="{{ route('restaurant.index') }}" class="btn btn-primary">Restaurants</a>
        </div>
        <div class="row">
            <div class="col-12 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white">
                    <div class="card-body">
                        <h1 class="font-weight-normal mb-3">
                            Welcome to Branch
                            <span class="font-weight-bold text-warning float-end">{{$restaurant->branch_code}}</span>
                        </h1>
                        <h3 class="font-weight-normal">Details and operations specific to Branch <span class="text-warning">{{$restaurant->name}}</span></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="page-header d-flex justify-content-between align-items-center">
                            <h3 class="page-title"> Maintenance Details </h3>
                            <div class="btn-group" role="group">
                                <a href="{{ route('restaurant.maintenance', $restaurant->id) }}" class="btn btn-dark btn-sm">
                                    <i class="mdi mdi-reload"></i> Reload
                                </a>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#maintenanceModal">
                                    Add New Maintenance
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-responsive table-bordered">
                                <thead>
                                    <tr>
                                        <th>@sortablelink('created_at', 'Date')</th>
                                        <th>@sortablelink('equipment_name', 'Equipment Name')</th>
                                        <th>@sortablelink('payment_type', 'Payment Type')</th>
                                        <th>@sortablelink('reason', 'Reason')</th>
                                        <th>@sortablelink('status', 'Status')</th>
                                        <th>Update</th>
                                    </tr>
                                    <form id="filterForm">
                                        <input type="hidden" id="table_sort" name="sort">
                                        <input type="hidden" id="table_order" name="order">
                                        <input type="hidden" id="total_records" name="total_records">
                                        <tr>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Date" name="created_at" type="date" value="{{ request('created_at', '') }}" />
                                            </th>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Equipment Name" name="equipment_name" type="text" value="{{ request('equipment_name', '') }}" />
                                            </th>
                                            <th class="px-1 py-2">
                                                <select class="form-control p-2 input-filter" name="payment_type">
                                                    <option value="">Select Payment Type</option>
                                                    @foreach(['credit', 'cash', 'debit'] as $payment_type)
                                                        <option value="{{ $payment_type }}" {{ request('payment_type') == $payment_type ? 'selected' : '' }}>
                                                            {{ ucfirst(str_replace('_', ' ', $payment_type)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </th>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Reason" name="reason" type="text" value="{{ request('reason', '') }}" />
                                            </th>
                                            <th class="px-1 py-2">
                                                <select class="form-control p-2 input-filter" name="status">
                                                    <option value="">Select Status</option>
                                                    @foreach(['pending', 'in_progress', 'on_hold', 'awaiting_approval', 'scheduled', 'cancelled', 'completed', 'done'] as $status)
                                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </th>
                                            <th></th>
                                        </tr>
                                    </form>
                                </thead>
                                <tbody>
                                    @foreach($maintenances as $maintenance)
                                        <tr>
                                            <td>{{ $maintenance->created_at->format('d-m-Y H:i:s') }}</td>
                                            <td>{{ $maintenance->equipment_name }}</td>
                                            <td>{{ $maintenance->payment_type }}</td>
                                            <td>{{ ucfirst($maintenance->reason) }}</td>
                                            <td>{{ $maintenance->status }}</td>
                                            <td>
                                                <button class="btn btn-warning btn-sm editMaintenance"
                                                    data-id="{{ $maintenance->id }}"
                                                    data-equipment_name="{{ $maintenance->equipment_name }}"
                                                    data-reason="{{ $maintenance->reason }}"
                                                    data-payment_type="{{ $maintenance->payment_type }}"
                                                    data-status="{{ $maintenance->status }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editMaintenanceModal">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $maintenances->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance Modal -->
        <div class="modal fade" id="maintenanceModal" tabindex="-1" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="maintenanceModalLabel">Add New Maintenance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="maintenanceForm">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" id="branch_code" name="branch_code" value="{{ $restaurant->branch_code }}">

                            <!-- Equipment Name -->
                            <div class="mb-3">
                                <label for="equipment_name" class="form-label">Equipment Name</label>
                                <input type="text" class="form-control" id="equipment_name" name="equipment_name" placeholder="Enter Equipment Name">
                                <div class="invalid-feedback" id="equipment_name_error"></div>
                            </div>

                            <!-- Payment Type -->
                            <div class="mb-3">
                                <label for="payment_type" class="form-label">Payment Type</label>
                                <select class="form-control" id="payment_type" name="payment_type">
                                    <option value="">Select Payment Type</option>
                                    <option value="cash">Cash</option>
                                    <option value="credit">Credit</option>
                                    <option value="debit">Debit</option>
                                </select>
                                <div class="invalid-feedback" id="payment_type_error"></div>
                            </div>

                            <!-- Reason -->
                            <div class="mb-3">
                                <label for="reason" class="form-label">Reason</label>
                                <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Enter reason"></textarea>
                                <div class="invalid-feedback" id="reason_error"></div>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="on_hold">On Hold</option>
                                    <option value="awaiting_approval">Awaiting Approval</option>
                                    <option value="scheduled">Scheduled</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="completed">Completed</option>
                                    <option value="done">Done</option>
                                </select>
                                <div class="invalid-feedback" id="status_error"></div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveMaintenance">Save Maintenance</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Edit Maintenance Modal -->
        <div class="modal fade" id="editMaintenanceModal" tabindex="-1" aria-labelledby="editMaintenanceModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMaintenanceModalLabel">Edit Maintenance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editMaintenanceForm">
                            @csrf
                            <input type="hidden" id="edit_maintenance_id" name="maintenance_id">

                            <!-- Equipment Name -->
                            <div class="mb-3">
                                <label for="edit_equipment_name" class="form-label">Equipment Name</label>
                                <input type="text" class="form-control" id="edit_equipment_name" name="equipment_name">
                                <div class="invalid-feedback" id="edit_equipment_name_error"></div>
                            </div>

                            <!-- Payment Type -->
                            <div class="mb-3">
                                <label for="edit_payment_type" class="form-label">Payment Type</label>
                                <select class="form-control" id="edit_payment_type" name="payment_type">
                                    <option value="cash">Cash</option>
                                    <option value="credit">Credit</option>
                                    <option value="debit">Debit</option>
                                </select>
                                <div class="invalid-feedback" id="edit_payment_type_error"></div>
                            </div>

                            <!-- Reason -->
                            <div class="mb-3">
                                <label for="edit_reason" class="form-label">Reason</label>
                                <textarea class="form-control" id="edit_reason" name="reason"></textarea>
                                <div class="invalid-feedback" id="edit_reason_error"></div>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-control" id="edit_status" name="status">
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="on_hold">On Hold</option>
                                    <option value="awaiting_approval">Awaiting Approval</option>
                                    <option value="scheduled">Scheduled</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="completed">Completed</option>
                                    <option value="done">Done</option>
                                </select>
                                <div class="invalid-feedback" id="edit_status_error"></div>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Maintenance</button>
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


        $(document).ready(function () {
            $("#maintenanceForm").submit(function (e) {
                e.preventDefault();

                // Clear previous error messages
                $(".invalid-feedback").text("");
                $(".form-control").removeClass("is-invalid");

                let formData = {
                    branch_code: $("#branch_code").val(),
                    equipment_name: $("#equipment_name").val(),
                    payment_type: $("#payment_type").val(),
                    reason: $("#reason").val(),
                    status: $("#status").val(),
                    _token: "{{ csrf_token() }}" // CSRF token for Laravel
                };

                $.ajax({
                    url: "{{ route('maintenances') }}",
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            toastr.success('Maintenance record added successfully!', 'Success', { timeOut: 3000 });
                            $("#maintenanceModal").modal("hide");
                            $("#maintenanceForm")[0].reset(); // Reset form fields
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            toastr.error('Something went wrong!', 'Error', { timeOut: 5000 });
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                $("#" + key).addClass("is-invalid");
                                $("#" + key + "_error").text(value[0]);
                            });
                        } else {
                            toastr.error('An unexpected error occurred.', 'Error', { timeOut: 5000 });
                        }
                    }
                });
            });
        });

        $(document).ready(function () {
            // Open edit modal & populate fields
            $(".editMaintenance").click(function () {
                let maintenance = $(this).data();

                $("#edit_maintenance_id").val(maintenance.id);
                $("#edit_equipment_name").val(maintenance.equipment_name);
                $("#edit_payment_type").val(maintenance.payment_type);
                $("#edit_reason").val(maintenance.reason);
                $("#edit_status").val(maintenance.status);

                $("#editMaintenanceModal").modal("show");
            });

            // Handle update form submission
            $("#editMaintenanceForm").submit(function (e) {
                e.preventDefault();

                $(".invalid-feedback").text(""); // Clear error messages
                $(".form-control").removeClass("is-invalid");

                let maintenanceId = $("#edit_maintenance_id").val();
                let formData = {
                    maintenance_id: maintenanceId,
                    equipment_name: $("#edit_equipment_name").val(),
                    payment_type: $("#edit_payment_type").val(),
                    reason: $("#edit_reason").val(),
                    status: $("#edit_status").val(),
                    _token: "{{ csrf_token() }}"
                };

                $.ajax({
                    url: "/maintenances/" + maintenanceId,
                    type: "PUT",
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            toastr.success('Maintenance updated successfully!', 'Success', { timeOut: 3000 });
                            $("#editMaintenanceModal").modal("hide");
                            $("#editMaintenanceForm")[0].reset(); // Reset form fields
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            toastr.error('Something went wrong!', 'Error', { timeOut: 5000 });
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                $("#edit_" + key).addClass("is-invalid");
                                $("#edit_" + key + "_error").text(value[0]);
                            });
                        } else {
                            toastr.error('An unexpected error occurred.', 'Error', { timeOut: 5000 });
                        }
                    }
                });
            });
        });

    </script>
    @endpush
</x-app-layout>
