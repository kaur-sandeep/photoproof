@extends('admin.layouts.master')
@section('content')

<div class="container-fluid">
   <div class="admin-page-header">
        <h3 class="card-title"><b>Edit Admin User </b></h3>
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
                 <form method="POST" action="{{ route('admin.update.users.data',$admin->id) }}" enctype="multipart/form-data">
                    @csrf
                <div class="card-body row">
                  <div class="form-group col-md-4">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="name" name ="name" class="form-control" id="name" placeholder="Enter Name"  value="{{ $admin->name}}">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name ="email" class="form-control" id="email" placeholder="Enter Email"  value = "{{ $admin->email }}" readonly>
                  </div>
                   <div class="form-group col-md-4">
                    <label for="number">Phone Number <span class="text-danger">*</span></label>
                    <input type="text" name ="phone_number" class="form-control" id="number" placeholder="Enter Number"  value = "{{ $admin->phone_number }}" >
                  </div>

                    <div class="mb-3 col-md-4">
                      @if($admin->profile_image)
                          <div class="mb-2">
                              <img src="{{ asset('storage/profile/'.$admin->profile_image) }}"
                                  alt="Profile Image"
                                  width="120"
                                  height="120"
                                  style="object-fit: cover; border-radius: 50%; border: 2px solid #ddd;">
                          </div>
                      @else
                          <div class="mb-2">
                              <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
                                  alt="Default Image"
                                  width="120"
                                  height="120"
                                  style="object-fit: cover; border-radius: 50%; border: 2px solid #ddd;">
                          </div>
                      @endif

                  <div class="input-group col-md-4">
                      <input type="file" name="profile_image" class="form-control" id="inputGroupFile02">
                      <label class="input-group-text" for="inputGroupFile02">Upload</label>
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
