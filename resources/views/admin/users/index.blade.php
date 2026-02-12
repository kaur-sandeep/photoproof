@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <x-datatable 
    id="usersTable"
    model="User"
    :columns="[
        ['label' => 'ID', 'field' => 'id'],
        ['label' => 'Name', 'field' => 'name'],
        ['label' => 'Email', 'field' => 'email']
    ]"
    route="{{ route('admin.users.list') }}"
    addRoute="{{ route('admin.users.create') }}"
    addLabel="User"
/>

</div>

@endsection
