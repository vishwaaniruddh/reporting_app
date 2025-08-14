@if(auth()->user()->can('permissions.assign'))
<div class="card mt-4 shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">Manage Permissions</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.permissions', $user->id) }}" method="POST">
            @csrf
            <div class="row">
                @foreach($allPermissions as $permission)
                <div class="col-md-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               name="permissions[]" 
                               value="{{ $permission->id }}"
                               {{ $user->permissions->contains($permission->id) ? 'checked' : '' }}>
                        <label class="form-check-label">
                            {{ $permission->name }}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update Permissions</button>
        </form>
    </div>
</div>
@endif