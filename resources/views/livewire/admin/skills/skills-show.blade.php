<div>

    <div class="modal fade" id="skillShowModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Show Skill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            @if($skill)
                            <h5 class="card-title">Name: {{ $skill->name }}</h5>
                            <h5 class="card-title">Percentage: {{ $skill->percentage }}</h5>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('open-show-modal', () => {
                const el = document.getElementById('skillShowModal');
                if (!el) return;
                const modal = bootstrap.Modal.getOrCreateInstance(el);
                modal.show();
            });
        });
    </script>
</div>