<div>
    <div class="card-body">
    <div class="d-flex gap-2 mb-3">
        <input type="text" class="form-control" placeholder="search skills" wire:model.live="search">
    </div>
    </div>
    <div class="table-responsive text-nowrap">
    @if($skills->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th width="40%" >Name</th>
                <th width="40%" >Percentage</th>
                <th width="40%">Actions</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach($skills as $skill)
            <tr>
                <td>{{ $skill->name }}</td>
                <td>{{ $skill->percentage }}</td>
             
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i>
                                Edit</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i>
                                Delete</a>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
       
        </tbody>
    </table>
    @if($skills->hasPages())
    {!! $skills->links() !!}
    @endif
    </div>
    @else
    <div class="alert alert-warning">No skills found</div>
    @endif
</div>