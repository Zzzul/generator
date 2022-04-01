<div class="row mb-2">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">{{ __('Name') }}</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                placeholder="{{ __('Name') }}" value="{{ isset($role) ? $role->name : old('name') }}" autofocus>
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>

<label class="mb-1">Permissions</label>
@error('permissions')
    <span class="text-danger" style="font-size: 14px">{{ $message }}</span>
@enderror
<div class="row">
    @foreach (config('permission.list_permissions') as $permission)
        <div class="col-md-3">
            <div class="card border">
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title">{{ ucwords($permission['group']) }}</h4>
                        @foreach ($permission['lists'] as $list)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="{{ Str::slug($list) }}" name="permissions[]" value="{{ $list }}" {{ isset($role) && $role->hasPermissionTo($list) ? 'checked' : '' }} />
                                <label class="form-check-label" for="{{ Str::slug($list) }}">
                                    {{ ucwords($list) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
