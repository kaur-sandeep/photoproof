@extends('admin.layouts.master')
@section('content')

<div class="container-fluid">
   <div class="admin-page-header">
        <h3 class="card-title"><b>Edit Employee </b></h3>
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
                 <form method="POST" action="{{ route('owner.update.employee.data',$user->id) }}" enctype="multipart/form-data">
                    @csrf
                <div class="card-body row">
                  <div class="form-group col-md-4">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="name" name ="name" class="form-control" id="name" placeholder="Enter Name"  value="{{ $user->name}}">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name ="email" class="form-control" id="email" placeholder="Enter Email"  value = "{{ $user->email }}" readonly>
                  </div>
                   <div class="form-group col-md-4">
                    <label for="number">Phone Number <span class="text-danger">*</span></label>
                    <input type="text" name ="phone_number" class="form-control" id="number" placeholder="Enter Number"  value = "{{ $user->phone_number }}" >
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="{{ route('owner.employee') }}" class="btn btn-secondary">
                        Back
                    </a>
                </div>
              </form>
            </div>
    </div>

</div>

@endsection
