@extends('admin.layouts.master')

@section('title', 'Add Organization Employee')

@section('content')

<div class="container-fluid">
    <div class="admin-page-header">
        <h3 class="card-title"><b>Add Organization Employee</b></h3>
    </div>
    <div class="row">
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

         @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card card-primary">
              <!-- /.card-header -->
              <!-- form start -->
                 <form method="POST" action="{{ route('owner.employee.store') }}" enctype="multipart/form-data">
                    @csrf
                <div class="card-body row">
                  <div class="form-group col-md-4 mb-4">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="name" name ="name" class="form-control" id="name" placeholder="Enter Name">
                  </div>
                  <div class="form-group col-md-4 mb-4">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name ="email" class="form-control" id="email" placeholder="Enter Email">
                  </div>
                  
                   <div class="form-group col-md-4 mb-4">
                    <label for="number">Phone Number <span class="text-danger">*</span></label>
                    <input type="text" name ="phone_number" class="form-control" id="number" placeholder="Enter Phone Number">
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Invite Employee</button>
                    <a href="{{ route('owner.employee') }}" class="btn btn-secondary">
                        Back
                    </a>
                </div>
              </form>
            </div>
    </div>

</div>

@endsection
