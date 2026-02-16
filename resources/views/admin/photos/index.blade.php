@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <x-datatable 
    id="usersTable"
    model="User"
    :columns="[
        ['label' => 'ID', 'field' => 's.no'],
        ['label' => 'Profile Image', 'field' => 'profile_image'],
        ['label' => 'Name', 'field' => 'name'],
        ['label' => 'Email', 'field' => 'email'],
        ['label' => 'Number', 'field' => 'phone_number'],
        ['label' => 'Photo Count', 'field' => 'photos_count']
        
   
    ]"
    route="{{ route('admin.users.') }}"
    addRoute="{{ route('admin.users.create') }}"
    addLabel="User"
/>

</div>

@endsection
