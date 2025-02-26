<x-app-layout>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Contact </h3>
            @if (Auth::user()->role === 'admin')
                <button class="btn btn-primary mb-3" id="addContactBtn">Add Contact</button>
            @endif
        </div>
        <div class="row">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>@sortablelink('vendor', 'Vendor')</th>
                                        <th>@sortablelink('vendor_hour', 'Vendor Hours M-F (EST)')</th>
                                        <th>@sortablelink('phone', 'Phone')</th>
                                        <th>@sortablelink('email', 'Email')</th>
                                        @if (Auth::user()->role === 'admin')
                                            <th>Actions</th>
                                        @endif
                                    </tr>
                                    <form id="filterForm">
                                        <input type="hidden" id="table_sort" name="sort">
                                        <input type="hidden" id="table_order" name="order">
                                        <input type="hidden" id="total_records" name="total_records">
                                        <tr>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Vendor" name="vendor" type="text" value="{{ request('vendor', '') }}" />
                                            </th>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Vendor Hours" name="vendor_hour" type="text" value="{{ request('vendor_hour', '') }}" />
                                            </th>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Phone" name="phone" type="text" value="{{ request('phone', '') }}" />
                                            </th>
                                            <th class="px-1 py-2">
                                                <input class="input-filter form-control p-2" placeholder="Email" name="email" type="text" value="{{ request('email', '') }}" />
                                            </th>
                                            @if (Auth::user()->role === 'admin')
                                                <th></th>
                                            @endif
                                        </tr>
                                    </form>
                                </thead>
                                <tbody>
                                    @foreach($contacts as $contact)
                                        <tr id="row_{{ $contact->id }}">
                                            <td>{{ $contact->vendor }}</td>
                                            <td>{{ $contact->vendor_hour }}</td>
                                            <td>{{ $contact->phone ?? '-' }}</td>
                                            <td>{{ $contact->email ?? '-' }}</td>
                                            @if (Auth::user()->role === 'admin')
                                            <td>
                                                <button class="btn btn-warning btn-sm editBtn"
                                                    data-id="{{ $contact->id }}"
                                                    data-vendor="{{ $contact->vendor }}"
                                                    data-vendor_hour="{{ $contact->vendor_hour }}"
                                                    data-phone="{{ $contact->phone }}"
                                                    data-email="{{ $contact->email }}">
                                                    Edit
                                                </button>
                                                <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $contact->id }}">Delete</button>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $contacts->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create & Edit Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactModalLabel">Add Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="contactForm">
                        @csrf
                        <input type="hidden" id="contact_id">
                        <div class="mb-3">
                            <label for="vendor" class="form-label">Vendor</label>
                            <input type="text" class="form-control" id="vendor" name="vendor" required>
                        </div>
                        <div class="mb-3">
                            <label for="hour" class="form-label">Hour</label>
                            <input type="text" class="form-control" id="vendor_hour" name="vendor_hour" required placeholder="10am – 6pm">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <button type="button" class="btn btn-primary" id="saveContactBtn">Save</button>
                    </form>
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
    $(document).ready(function(){
        $('.input-filter').change(function() {
            $('#filterForm').submit();
        });

        $('#tbl_record_count').change(function() {
          $('#total_records').val($(this).val());
          $('#filterForm').submit();
        });

        // Open modal for new contact
        $('#addContactBtn').click(function () {
            $('#contactForm')[0].reset();
            $('#contact_id').val('');
            $('#contactModalLabel').text('Add Contact');
            $('#contactModal').modal('show');
        });

        // Open modal for editing
        $('.editBtn').click(function () {
            $('#contact_id').val($(this).data('id'));
            $('#vendor').val($(this).data('vendor'));
            $('#vendor_hour').val($(this).data('vendor_hour'));
            $('#phone').val($(this).data('phone'));
            $('#email').val($(this).data('email'));
            $('#contactModalLabel').text('Edit Contact');
            $('#contactModal').modal('show');
        });

        // Save Contact (Create & Update)
        $('#saveContactBtn').click(function () {
            let id = $('#contact_id').val();
            let url = id ? `/contacts/${id}` : '/contacts';
            let method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: {
                    _token: $('input[name=_token]').val(),
                    vendor: $('#vendor').val(),
                    vendor_hour: $('#vendor_hour').val(),
                    phone: $('#phone').val(),
                    email: $('#email').val()
                },
                success: function (response) {
                    toastr.success(response.success, 'Success', { timeOut: 3000 });
                    location.reload();
                },
                error: function (response) {
                    toastr.error('Error saving contact', 'Error', { timeOut: 5000 });
                }
            });
        });

        // Delete Contact
        $('.deleteBtn').click(function () {
            if (confirm("Are you sure?")) {
                let id = $(this).data('id');
                $.ajax({
                    url: `/contacts/${id}`,
                    type: 'DELETE',
                    data: { _token: $('input[name=_token]').val() },
                    success: function (response) {
                        toastr.success(response.success, 'Success', { timeOut: 3000 });
                        location.reload();
                    }
                });
            }
        });
    });
    </script>
    @endpush
</x-app-layout>
