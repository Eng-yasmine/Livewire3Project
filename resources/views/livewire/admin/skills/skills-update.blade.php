<div>
    <div class="modal fade" id="skillUpdateModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Skill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit="updateSkill">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" wire:model="name" />
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Percentage</label>
                            <input type="number" class="form-control" min="1" max="100" wire:model="percentage" />
                            @error('percentage') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('open-update-modal', () => {
                const el = document.getElementById('skillUpdateModal');
                if (!el) return;
                const modal = bootstrap.Modal.getOrCreateInstance(el);
                modal.show();
            });
        });
    </script>
</div>
