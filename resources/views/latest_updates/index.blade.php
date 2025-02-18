<x-app-layout>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Important Updates </h3>
            <a href="{{ route('latest-updates.create') }}" class="btn btn-primary">Add Update</a>
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
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>URL</th>
                                    <th>Actions</th>
                                </tr>
                                <form id="filterForm">
                                    <input type="hidden" id="table_sort" name="sort">
                                    <input type="hidden" id="table_order" name="order">
                                    <input type="hidden" id="total_records" name="total_records">
                                    <tr>
                                        <th class="px-1 py-2">
                                            <input class="input-filter form-control p-2" placeholder="Title" name="title" type="text" value="{{request('title','')}}" />
                                        </th>
                                        <th class="px-1 py-2">
                                            <input class="input-filter form-control p-2" placeholder="Content" name="content" type="text" value="{{request('content','')}}" />
                                        </th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </form>
                            </thead>
                            <tbody>
                                @foreach($updates as $update)
                                    <tr>
                                        <td>{{ $update->title }}</td>
                                        <td>{{ Str::limit($update->content, 50) }}</td>
                                        <td>
                                            @if($update->url)
                                                <a href="{{ $update->url }}" target="_blank">View Link</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('latest-updates.edit', $update->id) }}" class="btn btn-warning btn-sm"><i class="mdi mdi-file-check"></i></a>
                                            <form action="{{ route('latest-updates.destroy', $update->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="mdi mdi-delete"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $updates->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
