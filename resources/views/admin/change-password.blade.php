@extends('admin.layouts.master')
@section('content')

<div class="container-fluid">
     <div class="admin-page-header">
        <h3 class="card-title"><b>Update Password</b></h3>
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
                 <form method="POST" action="{{ route('admin.update.password') }}" enctype="multipart/form-data">
                    @csrf
                <div class="card-body row">
                  <div class="form-group col-md-4">
                    <label for="old_password">Enter Old Password <span class="text-danger">*</span></label>
                    <input type="password" name ="old_password" class="form-control" id="old_password" placeholder="Enter Old Password">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="new_password">Enter New Password <span class="text-danger">*</span></label>
                    <input type="password" name ="new_password" class="form-control" id="new_password" placeholder="Enter New Password">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="confirm_password">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Enter Confirm Password">
                  </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
    </div>

</div>

@endsection
