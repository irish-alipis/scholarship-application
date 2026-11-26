@csrf

<div class="grid grid-cols-12 gap-4">

    {{-- Name --}}
    <div class="col-span-12 md:col-span-6">
        <label for="name" class="form-label">Name</label>
        <div class="input-group">
            <div class="input-group-text">
                <i class="ri-user-line"></i>
            </div>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name ?? '') }}">
        </div>

        @error('name')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- Email --}}
    <div class="col-span-12 md:col-span-6">
        <label for="email" class="form-label">Email</label>

        <div class="input-group">
            <div class="input-group-text">
                <i class="ri-mail-line"></i>
            </div>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email ?? '') }}">
        </div>

        @error('email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- Role --}}
    <div class="col-span-12">
        <label for="role" class="form-label">Role</label>

        <div class="input-group">
            <div class="input-group-text">
                <i class="ri-user-settings-line"></i>
            </div>
            <select name="role" id="role" class="form-control">
                @foreach(\App\Enums\Role::cases() as $role)
                    <option value="{{ $role->value }}" @selected(old('role', isset($user) ? $user->role->value : '') === $role->value)>
                        {{ ucfirst($role->value) }}
                    </option>
                @endforeach
            </select>
        </div>

        @error('role')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- Password --}}
    <div class="col-span-12 md:col-span-6">
        <label for="password" class="form-label">Password</label>

        <div class="input-group">
            <div class="input-group-text">
                <i class="ri-lock-line"></i>
            </div>
            <input type="password" name="password" id="password" class="form-control">
        </div>
        
        @error('password')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    {{-- Confirm Password --}}
    <div class="col-span-12 md:col-span-6">
        <label for="password_confirmation" class="form-label">
            Confirm Password
        </label>

        <div class="input-group">
            <div class="input-group-text">
                <i class="ri-lock-line"></i>
            </div>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        
    </div>

</div>