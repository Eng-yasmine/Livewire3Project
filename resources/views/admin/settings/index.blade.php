@extends('admin.master')

@section('settings-menu', 'active')

@section('title', 'Settings')

@section('admin-content')


    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Settings /</span> General Settings</h4>

            <div class="card mb-4">
                <h5 class="card-header">Default</h5>
           @livewire('admin.settings.updatesettings')
            </div>

        </div>

    @endsection
