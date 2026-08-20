@extends('admin.layouts.master')
@section('title', 'Top-up Plans')
@section('content')
<div class="container-fluid">
     <div class="admin-page-header">
        <h3 class="card-title"><b>Top-up Plans</b></h3>
        <a class="btn btn-primary" href="{{ route('admin.billing.topups.create') }}">+ Add Top-up</a>
    </div>
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card"><div class="card-body"><div class="table-responsive"><table id="topups-table" class="table table-bordered table-striped align-middle w-100"><thead><tr><th>Name</th><th>Photos</th><th>Price</th><th>Purchased Users</th><th>State</th><th>Actions</th></tr></thead></table></div></div></div></div>
<script>document.addEventListener('DOMContentLoaded',function(){window.jQuery('#topups-table').DataTable({processing:true,serverSide:true,ajax:'{{ route('admin.billing.topups.data') }}',columns:[{data:'name',name:'name'},{data:'photo_quantity',name:'photo_quantity'},{data:'price',name:'price'},{data:'purchasers_count',name:'purchasers_count',searchable:false},{data:'state',name:'state',searchable:false},{data:'actions',name:'actions',orderable:false,searchable:false}]});});</script>
@endsection
