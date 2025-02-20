<x-app-layout>
    <div class="content-wrapper">
        <div class="row mb-3">
            <div class="col-12">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12">
                @include('profile.partials.update-password-form')
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
