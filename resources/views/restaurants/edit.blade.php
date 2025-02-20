<x-app-layout>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Edit Restaurant </h3>
            <a href="{{ route('restaurant.index') }}" class="btn btn-primary">List</a>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('restaurant.update', $restaurant->id) }}" method="POST">
                            @csrf
                            @method('PUT')  <!-- For PUT method to update -->

                            <!-- Branch Code Field -->
                            <div class="form-group">
                                <label for="branch_code">Branch Code</label>
                                <input type="text" name="branch_code" class="form-control @error('branch_code') is-invalid @enderror" value="{{ old('branch_code', $restaurant->branch_code) }}" required>
                                @error('branch_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Name Field -->
                            <div class="form-group">
                                <label for="name">Branch Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $restaurant->name) }}" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Address Field -->
                            {{-- <div class="form-group">
                                <label for="address">Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $restaurant->address) }}</textarea>
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            <button type="submit" class="btn btn-success">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        document.getElementById('role').addEventListener('change', function () {
            let restaurantDiv = document.getElementById('restaurant-selection');
            restaurantDiv.style.display = (this.value === 'user') ? 'block' : 'none';
        });
    </script>
    @endpush
</x-app-layout>
