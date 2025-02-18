<x-app-layout>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Restaurants </h3>
            @if (Auth::user()->role === 'admin')
                <a href="{{ route('restaurant.create') }}" class="btn btn-primary">Add Restaurant</a>
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
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>@sortablelink('name', 'Name')</th>
                                    <th>@sortablelink('branch_code', 'Branch Code')</th>
                                    <th>@sortablelink('address', 'Address')</th>
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
                                            <input class="input-filter form-control p-2" placeholder="Name" name="name" type="text" value="{{request('name','')}}" />
                                        </th>
                                        <th class="px-1 py-2">
                                            <input class="input-filter form-control p-2" placeholder="Branch Code" name="branch_code" type="text" value="{{request('branch_code','')}}" />
                                        </th>
                                        <th class="px-1 py-2">
                                            <input class="input-filter form-control p-2" placeholder="Address" name="address" type="text" value="{{request('address','')}}" />
                                        </th>
                                        @if (Auth::user()->role === 'admin')
                                            <th></th>
                                        @endif
                                    </tr>
                                </form>
                            </thead>
                            <tbody>
                                @foreach($restaurants as $restaurant)
                                    <tr>
                                        <td>{{ ucfirst($restaurant->name) }}</td>
                                        <td>{{ $restaurant->branch_code }}</td>
                                        <td>{{ ucfirst($restaurant->address) }}</td>
                                        @if (Auth::user()->role === 'admin')
                                        <td>
                                            <a href="{{ route('restaurant.edit', $restaurant->id) }}" class="btn btn-warning btn-sm"><i class="mdi mdi-file-check"></i></a>

                                            <form action="{{ route('restaurant.destroy', $restaurant->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')"><i class="mdi mdi-delete"></i></button>
                                            </form>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $restaurants->links('vendor.pagination.bootstrap-5') }}
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
