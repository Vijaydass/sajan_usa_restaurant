<x-app-layout>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Sliders </h3>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Add New Slider</button>
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
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sliderTable">
                                @foreach ($sliders as $slider)
                                <tr id="sliderRow{{ $slider->id }}">
                                    <td><img src="{{ asset('uploads/sliders/' . $slider->image) }}" width="100"></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm editBtn" data-id="{{ $slider->id }}" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                        <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $slider->id }}">Delete</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Slider</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createForm">
                            @csrf
                            <input type="file" name="image" class="form-control mb-2" required>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Slider</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            @csrf
                            <input type="hidden" name="slider_id" id="editSliderId">
                            <input type="file" name="image" class="form-control mb-2">
                            <button type="submit" class="btn btn-success">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <style>
        th a {
            text-decoration: none;
            color: black;
        }
    </style>
    @push('scripts')
    <script>
    $(document).ready(function(){
        $('#createForm').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('sliders.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    location.reload();
                }
            });
        });

        $('.editBtn').click(function () {
            let id = $(this).data('id');
            $('#editSliderId').val(id);
        });

        $('#editForm').submit(function (e) {
            e.preventDefault();
            let id = $('#editSliderId').val();
            let formData = new FormData(this);

            $.ajax({
                url: "/sliders/" + id,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    location.reload();
                }
            });
        });

        $('.deleteBtn').click(function () {
            let id = $(this).data('id');

            $.ajax({
                url: "/sliders/" + id,
                type: "DELETE",
                data: {_token: "{{ csrf_token() }}"},
                success: function () {
                    location.reload();
                }
            });
        });
    });
    </script>
    @endpush
</x-app-layout>
