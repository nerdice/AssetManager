<x-app-layout>
    <x-slot name="header"><h1 class="h3 mb-0">{{ __('Profile') }}</h1></x-slot>
    <div class="row g-4">
        <div class="col-12 col-lg-7">
            <div class="card"><div class="card-body p-4">@include('profile.partials.update-profile-information-form')</div></div>
        </div>
        <div class="col-12 col-lg-5">
            <div class="card"><div class="card-body p-4">@include('profile.partials.update-password-form')</div></div>
        </div>
        <div class="col-12">
            <div class="card border-danger"><div class="card-body p-4">@include('profile.partials.delete-user-form')</div></div>
        </div>
    </div>
</x-app-layout>
