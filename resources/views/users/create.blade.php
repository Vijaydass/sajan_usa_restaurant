<x-app-layout>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Add New User </h3>
            <a href="{{ route('users.index') }}" class="btn btn-primary">List</a>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('users.store') }}" method="POST" class="form-simple">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" class="form-control" required id="role">
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                                @error('role')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group" id="restaurant-selection" style="{{ old('role', $user->role ?? '') == 'user' ? '' : 'display: none;' }}">
                                <label for="restaurants">Assign Restaurants</label>
                                <select name="restaurants[]" id="restaurants" class="form-control" multiple>
                                    @foreach($restaurants as $restaurant)
                                        <option value="{{ $restaurant->id }}"
                                            {{ in_array($restaurant->id, old('restaurants', $user->restaurants->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                                            {{ $restaurant->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" required>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                                @error('password_confirmation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-success">Submit</button>
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


