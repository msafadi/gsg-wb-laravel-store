@extends('layouts.dashboard')

@section('title', 'User Profile')

@section('content')

<x-flash-message />

<p>
    <a href="{{ route('change-password') }}">Change Password</a>
</p>

<form action="{{ route('profile.update') }}" method="post">
    @csrf
    @method('patch')
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <x-form.input name="first_name" :value="$user->profile->first_name" label="First Name" />
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <x-form.input name="last_name" :value="$user->profile->last_name" label="Last Name" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <x-form.input name="name" :value="$user->name" label="Display Name" />
    </div>
    <div class="form-group">
        <x-form.input name="email" :value="$user->email" label="Email Address" />
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <x-form.input type="date" name="birthday" :value="$user->profile->birthday" label="Birthday" />
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <x-form.label>Gender</x-form.label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="male" value="male" @if($user->profile->gender == 'male') checked @endif>
                        <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="female" value="female"  @if($user->profile->gender == 'female') checked @endif>
                        <label class="form-check-label" for="female">Female</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <x-form.textarea name="address" :value="$user->profile->address" label="Address" />
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <x-form.input name="city" :value="$user->profile->city" label="City" />
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <x-form.label>Country</x-form.label>
                <select name="country_code" id="country_code" class="form-control">
                    <option value="">Select Country</option>
                    @foreach(Symfony\Component\Intl\Countries::getNames() as $code => $name)
                        @if ($code == 'IL')
                            @continue
                        @endif
                    <option value="{{ $code }}" @if($user->profile->country_code == $code) selected @endif>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
        <div class="form-group">
                <x-form.label>Language</x-form.label>
                <select name="locale" id="locale" class="form-control">
                    <option value="">Select Language</option>
                    @foreach(Symfony\Component\Intl\Locales::getNames() as $code => $name)
                    <option value="{{ $code }}" @if($user->profile->locale == $code) selected @endif>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <x-form.label>Timezone</x-form.label>
                <select name="timezone" id="timezone" class="form-control">
                    <option value="">Select Timezone</option>
                    @foreach(Symfony\Component\Intl\Timezones::getNames() as $code => $name)
                    <option value="{{ $code }}" @if($user->profile->timezone == $code) selected @endif>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>

@endsection