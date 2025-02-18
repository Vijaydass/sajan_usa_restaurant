<x-app-layout>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Edit Important Update </h3>
            <a href="{{ route('latest-updates.index') }}" class="btn btn-primary">List</a>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('latest-updates.update', $latest_update->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $latest_update->title }}" required>
                            </div>
                            <div class="form-group">
                                <label>Content</label>
                                <textarea name="content" class="form-control" required>{{ $latest_update->content }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>URL (Optional)</label>
                                <input type="url" name="url" class="form-control" value="{{ $latest_update->url }}">
                            </div>
                            <button type="submit" class="btn btn-success">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
