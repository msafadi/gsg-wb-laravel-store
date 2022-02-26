    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-3">
                <x-form.input id="name" name="name" :label="__('Name')" :value="$role->name" />
            </div>
        </div>
        <div class="col-md-12">
            <h3>{{ __('Permissions') }}</h3>
            <div class="row">
                @foreach(config('permissions') as $key => $value)
                <div class="col-md-4">
                    <div class="custom-control custom-switch">
                        <input class="custom-control-input" type="checkbox" role="switch" id="permissions_{{ str_replace('.', '_', $key) }}" name="permissions[]" value="{{ $key }}" @if($role->has($key)) checked @endif>
                        <label class="custom-control-label" for="permissions_{{ str_replace('.', '_', $key) }}">{{ $value }}</label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group mb-3">
                <button type="submit" class="btn btn-primary">{{ $button }}</button>
                <a href="{{ route('dashboard.roles.index') }}" class="btn btn-light">Cancel</a>
            </div>
        </div>
    </div>
