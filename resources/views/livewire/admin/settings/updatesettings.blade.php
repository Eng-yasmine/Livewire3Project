@if (session()->has('message'))
    <div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session()->get('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
<form class="card-body row" wire:submit="updateSettings">
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" placeholder="John Doe" aria-describedby="defaultFormControlHelp"
            wire:model="settings.name" />

    </div>
    @error('settings.name')
        <span class="text-danger">{{ $message }}</span>
    @enderror
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" placeholder="example@example.com" wire:model="settings.email" />
    </div>
    @error('settings.email')
        <span class="text-danger">{{ $message }}</span>
    @enderror
    <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input type="text" class="form-control" placeholder="01010101010" wire:model="settings.phone" />
    </div>
    @error('settings.phone')
        <span class="text-danger">{{ $message }}</span>
    @enderror
    <div class="col-md-6 mt-3">
        <label class="form-label">Address</label>
        <input type="text" class="form-control" placeholder="123 Main St, Anytown, USA"
            wire:model="settings.address" />
    </div>
    @error('settings.address')
        <span class="text-danger">{{ $message }}</span>
    @enderror
    <div class="col-md-6 mt-3">
        <label class="form-label">Facebook</label>
        <input type="text" class="form-control" placeholder="https://www.facebook.com/example"
            wire:model="settings.facebook" />
    </div>
    @error('settings.facebook')
        <span class="text-danger">{{ $message }}</span>
    @enderror
    <div class="col-md-6 mt-3">
        <label class="form-label">Twitter</label>
        <input type="text" class="form-control" placeholder="https://www.twitter.com/example"
            wire:model="settings.twitter" />
    </div>
    @error('settings.twitter')
        <span class="text-danger">{{ $message }}</span>
    @enderror
    <div class="col-md-6 mt-3">
        <label class="form-label">Instagram</label>
        <input type="text" class="form-control" placeholder="https://www.instagram.com/example"
            wire:model="settings.instagram" />
    </div>
    @error('settings.instagram')
        <span class="text-danger">{{ $message }}</span>
    @enderror
    <div class="col-md-6 mt-3 mt-3">
        <label class="form-label">LinkedIn</label>
        <input type="text" class="form-control" placeholder="https://www.linkedin.com/example"
            wire:model="settings.linkedin" />
    </div>
    @error('settings.linkedin')
        <span class="text-danger">{{ $message }}</span>
    @enderror
    <div class="col-md-6 mt-3 mt-3">
        <button class="btn btn-primary" type="submit">Save</button>
    </div>
</form>
