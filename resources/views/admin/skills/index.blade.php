@extends('admin.master')

@section('skills-active', 'active')

@section('title', 'Skills')

@section('admin-content')

    <div class="card mb-4 mt-5">
        <h5 class="card-header">Skills</h5>
        @livewire('admin.skills.skills-create')

        @livewire('admin.skills.skills-data')

        @livewire('admin.skills.skills-update')
        {{-- delete modal --}}
        @livewire('admin.skills.skills-delete')
        {{-- show modal --}}
        @livewire('admin.skills.skills-show')
    </div>

@endsection
