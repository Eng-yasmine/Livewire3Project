<div>
    <div class="modal fade" id="skillDeleteModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Skill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete this skill?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteSkill" data-bs-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('open-delete-modal', () => {
                const el = document.getElementById('skillDeleteModal');
                if (!el) return;
                const modal = bootstrap.Modal.getOrCreateInstance(el);
                modal.show();
            });
        });
    </script>
</div>
