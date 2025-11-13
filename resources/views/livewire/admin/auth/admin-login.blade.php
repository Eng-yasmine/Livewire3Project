<form wire:submit="save" class="mb-3">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input wire:model="email" type="text" class="form-control" placeholder="Enter your email" autofocus />
        @error('email')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3 form-password-toggle">
        <div class="d-flex justify-content-between">
            <label class="form-label" for="password">Password</label>

        </div>
        <div class="input-group input-group-merge">
            <input wire:model="password" type="password" id="password" class="form-control"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                aria-describedby="password" />
            <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
        </div>
        @error('password')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <div class="form-check">
            <input wire:model="remember" class="form-check-input" type="checkbox" />
            <label class="form-check-label"> Remember Me </label>
        </div>
    </div>
    <div class="mb-3">
        <button class="btn btn-primary d-grid w-100" type="submit">
            Sign in
           <div class="d-flex justify-content-center align-items-center">
           <span wire:loading wire:target="save" class="spinner-border spinner-border-sm text-white" role="status">
           <span class="visually-hidden">Loading...</span>
       </span>
        </div>
        </button>
    </div>
</form>
