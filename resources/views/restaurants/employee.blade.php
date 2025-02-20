<x-app-layout>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Employees </h3>
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
                            <h3 class="page-title"> Employee Details </h3>
                            <div class="btn-group" role="group">
                                <a href="{{ route('restaurant.employees', $restaurant->id) }}" class="btn btn-dark btn-sm">
                                    <i class="mdi mdi-reload"></i> Reload
                                </a>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#employeeModal">
                                    Add New Employee
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-responsive table-bordered">
                                <thead>
                                    <tr>
                                        <th>@sortablelink('name', 'Name')</th>
                                        <th>@sortablelink('email', 'Email')</th>
                                        <th>@sortablelink('designation', 'Designation')</th>
                                        <th>Add Payroll</th>
                                    </tr>
                                    <form id="filterForm">
                                        <input type="hidden" id="table_sort" name="sort">
                                        <input type="hidden" id="table_order" name="order">
                                        <input type="hidden" id="total_records" name="total_records">
                                        <tr>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Name" name="name" type="text" value="{{ request('name', '') }}" />
                                            </th>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Email" name="email" type="email" value="{{ request('email', '') }}" />
                                            </th>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Designation" name="designation" type="text" value="{{ request('designation', '') }}" />
                                            </th>
                                            <th></th>
                                        </tr>
                                    </form>
                                </thead>
                                <tbody>
                                    @foreach($employees as $employee)
                                        <tr>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->email }}</td>
                                            <td>{{ $employee->designation }}</td>
                                            <td>
                                                <button class="btn btn-warning btn-sm addPayroll"
                                                    data-id="{{ $employee->id }}"
                                                    data-name="{{ $employee->name }}"
                                                    data-email="{{ $employee->email }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#addPayrolModal">
                                                    <i class="mdi mdi-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $employees->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance Modal -->
        <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="employeeModalLabel">Add New Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="employeeForm">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" id="branch_code" name="branch_code" value="{{ $restaurant->branch_code }}">
                                <!-- Left Column -->
                                <div class="col-md-6">
                                    <!-- Name -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                                        <div class="invalid-feedback" id="name_error"></div>
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                                        <div class="invalid-feedback" id="email_error"></div>
                                    </div>

                                    <!-- Designation -->
                                    <div class="mb-3">
                                        <label for="designation" class="form-label">Designation</label>
                                        <input type="text" class="form-control" id="designation" name="designation" placeholder="Enter Designation" required>
                                        <div class="invalid-feedback" id="designation_error"></div>
                                    </div>

                                    <!-- Address -->
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea class="form-control" id="address" name="address" rows="2" placeholder="Enter Address" required></textarea>
                                        <div class="invalid-feedback" id="address_error"></div>
                                    </div>

                                    <!-- SSN -->
                                    <div class="mb-3">
                                        <label for="ssn" class="form-label">SSN</label>
                                        <input type="text" class="form-control" id="ssn" name="ssn" placeholder="Enter SSN" required>
                                        <div class="invalid-feedback" id="ssn_error"></div>
                                    </div>

                                    <!-- Pay Rate -->
                                    <div class="mb-3">
                                        <label for="pay_rate" class="form-label">Pay Rate</label>
                                        <input type="number" class="form-control" id="pay_rate" name="pay_rate" placeholder="Enter Pay Rate" required>
                                        <div class="invalid-feedback" id="pay_rate_error"></div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <!-- DOB -->
                                    <div class="mb-3">
                                        <label for="dob" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="dob" name="dob" required>
                                        <div class="invalid-feedback" id="dob_error"></div>
                                    </div>

                                    <!-- Routing Number -->
                                    <div class="mb-3">
                                        <label for="routing_number" class="form-label">Routing Number</label>
                                        <input type="text" class="form-control" id="routing_number" name="routing_number" placeholder="Enter Routing Number" required>
                                        <div class="invalid-feedback" id="routing_number_error"></div>
                                    </div>

                                    <!-- Account Number -->
                                    <div class="mb-3">
                                        <label for="account_number" class="form-label">Account Number</label>
                                        <input type="text" class="form-control" id="account_number" name="account_number" placeholder="Enter Account Number" required>
                                        <div class="invalid-feedback" id="account_number_error"></div>
                                    </div>

                                    <!-- Bank -->
                                    <div class="mb-3">
                                        <label for="bank" class="form-label">Bank</label>
                                        <input type="text" class="form-control" id="bank" name="bank" placeholder="Enter Bank Name" required>
                                        <div class="invalid-feedback" id="bank_error"></div>
                                    </div>

                                    <!-- Mobile -->
                                    <div class="mb-3">
                                        <label for="mobile" class="form-label">Mobile</label>
                                        <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter Mobile Number" required>
                                        <div class="invalid-feedback" id="mobile_error"></div>
                                    </div>

                                    <!-- Start Date -->
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                                        <div class="invalid-feedback" id="start_date_error"></div>
                                    </div>
                                </div>
                            </div> <!-- End Row -->
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveEmployee">Save Employee</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Edit Maintenance Modal -->
        <div class="modal fade" id="addPayrollModal" tabindex="-1" aria-labelledby="addPayrollModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPayrollModalLabel">Add New Payroll Info</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addPayrollForm">
                            @csrf
                            <input type="hidden" id="add_employee_id" name="employee_id">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="add_employee_name" class="form-label">Name</label>
                                        <input type="text" readonly class="form-control" id="add_employee_name" name="employee_name">
                                        <div class="invalid-feedback" id="add_employee_name_error"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="add_employee_email" class="form-label">Email</label>
                                        <input type="text" readonly class="form-control" id="add_employee_email" name="employee_email">
                                        <div class="invalid-feedback" id="add_employee_email_error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="add_payroll_start_date" class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="add_payroll_start_date" name="payroll_start_date">
                                        <div class="invalid-feedback" id="add_payroll_start_date_error"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="add_payroll_end_date" class="form-label">End Date</label>
                                        <input type="date" class="form-control" id="add_payroll_end_date" name="payroll_end_date">
                                        <div class="invalid-feedback" id="add_payroll_end_date_error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-3">
                                    <label for="add_payroll_wk1_hrs" class="form-label">Wk1 Hrs</label>
                                        <input type="text" class="form-control" id="add_payroll_wk1_hrs" name="wk1_hrs">
                                        <div class="invalid-feedback" id="add_payroll_wk1_hrs_error"></div>
                                </div>
                                <div class="col-3">
                                    <label for="add_payroll_wk2_hrs" class="form-label">Wk2 Hrs</label>
                                        <input type="text" class="form-control" id="add_payroll_wk2_hrs" name="wk2_hrs">
                                        <div class="invalid-feedback" id="add_payroll_wk2_hrs_error"></div>
                                </div>
                                <div class="col-3">
                                    <label for="add_payroll_ot_wk1_hrs" class="form-label">OT Wk1 Hrs</label>
                                        <input type="text" class="form-control" id="add_payroll_ot_wk1_hrs" name="ot_wk1_hrs">
                                        <div class="invalid-feedback" id="add_payroll_ot_wk1_hrs_error"></div>
                                </div>
                                <div class="col-3">
                                    <label for="add_payroll_ot_wk2_hrs" class="form-label">OT Wk2 Hrs</label>
                                        <input type="text" class="form-control" id="add_payroll_ot_wk2_hrs" name="ot_wk2_hrs">
                                        <div class="invalid-feedback" id="add_payroll_ot_wk2_hrs_error"></div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
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
            // Open modal for adding new employee
            $('#employeeModal').on('show.bs.modal', function () {
                $('#employeeForm')[0].reset(); // Reset the form
                $('.invalid-feedback').text(''); // Clear error messages
                $('.form-control').removeClass('is-invalid'); // Remove validation styles
            });

            // Handle form submission
            $('#employeeForm').submit(function (e) {
                e.preventDefault(); // Prevent default form submission

                let formData = $(this).serialize(); // Serialize form data
                let submitButton = $('#saveEmployee');

                submitButton.prop('disabled', true).text('Saving...'); // Disable button while processing

                $.ajax({
                    url: "{{ route('employees.store') }}", // Update this with your actual route
                    type: "POST",
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            toastr.success('Employee added successfully!', 'Success', { timeOut: 3000 });
                            $("#employeeModal").modal("hide");
                            $("#employeeForm")[0].reset(); // Reset form fields
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            toastr.error('Something went wrong!', 'Error', { timeOut: 5000 });
                        }
                    },
                    error: function (xhr) {
                        submitButton.prop('disabled', false).text('Save Employee'); // Re-enable button

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            if (errors) {
                                $('.invalid-feedback').text(''); // Clear previous error messages
                                $('.form-control').removeClass('is-invalid'); // Remove previous invalid styles

                                // Display validation errors
                                $.each(errors, function (key, value) {
                                    $('#' + key).addClass('is-invalid');
                                    $('#' + key + '_error').text(value[0]);
                                });
                            }
                        } else {
                            toastr.error('An unexpected error occurred.', 'Error', { timeOut: 5000 });
                        }
                    }
                });
            });
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
            $(".addPayroll").click(function () {
                let employee = $(this).data();

                $("#add_employee_id").val(employee.id);
                $("#add_employee_name").val(employee.name);
                $("#add_employee_email").val(employee.email);

                $("#addPayrollModal").modal("show");
            });

            // Handle update form submission
            $("#addPayrollForm").submit(function (e) {
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
                            $("#addPayrollModal").modal("hide");
                            $("#addPayrollForm")[0].reset(); // Reset form fields
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
