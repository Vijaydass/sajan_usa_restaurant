<x-app-layout>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Users </h3>
            <a href="{{ route('users.create') }}" class="btn btn-primary">Add User</a>
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
                                    <th>@sortablelink('name', 'Name', ['class'=>'text-dark'])</th>
                                    <th>@sortablelink('email', 'Email')</th>
                                    <th>@sortablelink('role', 'Role')</th>
                                    <th>Restaurants</th>
                                    <th>Actions</th>
                                </tr>
                                <form id="filterForm">
                                    <input type="hidden" id="table_sort" name="sort">
                                    <input type="hidden" id="table_order" name="order">
                                    <input type="hidden" id="total_records" name="total_records">
                                    <tr>
                                        <th class="px-1 py-2">
                                            <input class="input-filter form-control p-2" placeholder="Name" name="name" type="text" value="{{request('name','')}}" />
                                        </th>
                                        <th class="px-1 py-2">
                                            <input class="input-filter form-control p-2" placeholder="Email" name="email" type="email" value="{{request('email','')}}" />
                                        </th>
                                        <th class="px-1 py-2">
                                            <input class="input-filter form-control p-2" placeholder="Role" name="role" type="text" value="{{request('role','')}}" />
                                        </th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </form>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ ucfirst($user->name) }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ ucfirst($user->role) }}</td>
                                        <td>
                                            @if($user->role === 'user')
                                                @foreach($user->restaurants as $restaurant)
                                                    <span class="badge badge-info">{{ $restaurant->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm"><i class="mdi mdi-file-check"></i></a>

                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')"><i class="mdi mdi-delete"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $users->links('vendor.pagination.bootstrap-5') }}
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
        $('.input-filter').change(function() {
            $('#filterForm').submit();
        });

        $('#tbl_record_count').change(function() {
          $('#total_records').val($(this).val());
          $('#filterForm').submit();
        });
    });
    </script>
    @endpush
</x-app-layout>
