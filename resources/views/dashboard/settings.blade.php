@extends('layouts.dashboard')

@section('title', 'Settings')

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')

<x-flash-message class="info" />

<form action="{{ route('dashboard.settings.update') }}" method="post">
    @csrf
    @method('patch')

    <div class="form-group mb-3">
        <x-form.input id="name" name="settings[app_name]" :label="__('App. Name')" :value="$settings['app.name']" />
    </div>

    <div class="form-group mb-3">
        <label for="app_currency">Currency</label>
        <select id="app_currency" name="settings[app_currency]" class="form-control @error('settings.app_currency') is-invalid @enderror">
            <option value="">Select One</option>
            @foreach($currencies as $code => $label)
            <option value="{{ $code }}" @selected($code == old('settings.app_currency', $settings['app.currency']))>{{$code }} - {{ $label }}</option>
            @endforeach
        </select>
        @error('settings.app_currency')
        <p class="invalid-feedback">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label for="app_locale">Locale</label>
        <select id="app_locale" name="settings[app_locale]" class="form-control @error('settings.app_locale') is-invalid @enderror">
            <option value="">Select One</option>
            @foreach($locales as $code => $label)
            <option value="{{ $code }}" @selected($code == old('settings.app_locale', $settings['app.locale']))>{{ $label }}</option>
            @endforeach
        </select>
        @error('settings.app_locale')
        <p class="invalid-feedback">{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group mb-3">
        <x-form.input id="name" name="settings[services_ipstack_key]" :label="__('IPSTACK Access Key')" :value="$settings['services.ipstack.key'] ?? ''" />
    </div>

    <div class="col-md-12">
        <div class="form-group mb-3">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </div>
    </div>

</form>

@endsection