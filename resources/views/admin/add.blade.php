@extends('admin.layouts.master')

@section('title', 'Add Admin User')

@section('content')

<div class="container-fluid">
    <div class="admin-page-header">
        <h3 class="card-title"><b>Add Admin User </b></h3>
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
                 <form method="POST" action="{{ route('admin.store.users') }}" enctype="multipart/form-data">
                    @csrf
                <div class="card-body row">
                  <div class="form-group col-md-4">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="name" name ="name" class="form-control" id="name" placeholder="Enter Name">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name ="email" class="form-control" id="email" placeholder="Enter Email">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="password">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password">
                  </div>
                   <div class="form-group col-md-4">
                    <label for="number">Phone Number <span class="text-danger">*</span></label>
                    <input type="text" name ="phone_number" class="form-control" id="number" placeholder="Enter Phone Number">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="exampleInputFile">File input</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" name ="profile_image"class="custom-file-input" id="exampleInputFile">
                      </div>
                    </div>
                  </div>
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
