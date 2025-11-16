<div>
    <div class="col-lg-4 col-md-6">
        <div class="mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#skillCreateModal">
                Add New Skill
            </button>

            <form wire:submit="createSkill">
                <div class="modal fade" id="skillCreateModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Create Skill</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" placeholder="Enter Name" wire:model="name" />
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Percentage</label>
                                    <input type="number" class="form-control" placeholder="Enter Percentage" min="1" max="100" wire:model="percentage" />
                                    @error('percentage') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
