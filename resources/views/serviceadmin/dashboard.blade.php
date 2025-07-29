@extends('serviceadmin.home')

<div class="row">
    <div class="col-md-4 mb-4">
        @include('serviceadmin.cards', ['title' => 'Total Clients', 'count' => $totalClients, 'icon' => 'users', 'color' => 'primary'])
    </div>

    <div class="col-md-4 mb-4">
        @include('serviceadmin.cards', ['title' => 'Services Offered', 'count' => $totalServices, 'icon' => 'tools', 'color' => 'success'])
    </div>
</div>
<div>
    