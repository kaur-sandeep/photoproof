@extends('admin.layouts.master')
@section('content')

<div class="container-fluid">

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

         <form id="editprofileForm" method="POST" action="{{route('admin.profile.update')}}"  enctype="multipart/form-data">
             @csrf
                    <!--begin::Body-->
                    <div class="card-body">
                       <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Name</label>
                        <input
                          type="text"
                          name ="name"
                          class="form-control"
                          id="name"
                          value="{{ $user->name ?? 'na' }}"
                        />
                      </div>
                      <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email</label>
                        <input
                          type="email"
                          name="email"
                          class="form-control"
                          id="email"
                          value = "{{ $user->email }}"
                         readOnly/>
                      </div>
                      <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Phone Number</label>
                        <input
                          type="text"
                          name="number"
                          class="form-control"
                          id="number"
                          value = "{{ $user->phone_number }}"
                         />
                      </div>

                    <div class="mb-3">
                      @if($user->profile_image)
                          <div class="mb-2">
                              <img src="{{ asset('storage/profile/'.$user->profile_image) }}"
                                  alt="Profile Image"
                                  width="120"
                                  height="120"
                                  style="object-fit: cover; border-radius: 50%; border: 2px solid #ddd;">
                          </div>
                      @else
                          <div class="mb-2">
                              <img src="{{ asset('images/default-user.png') }}"
                                  alt="Default Image"
                                  width="120"
                                  height="120"
                                  style="object-fit: cover; border-radius: 50%; border: 2px solid #ddd;">
                          </div>
                      @endif

                  <div class="input-group">
                      <input type="file" name="image" class="form-control" id="inputGroupFile02">
                      <label class="input-group-text" for="inputGroupFile02">Upload</label>
                  </div>
                </div>
                </div>
                  <!--end::Body-->
                  <!--begin::Footer-->
                  <!-- <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div> -->
                  <div class="card-footer d-flex justify-content-center gap-3">
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>

                    <a href="{{ route('admin.change.password') }}" class="btn btn-warning">
                        Change Password
                    </a>
                </div>
                  <!--end::Footer-->
                </form>

    </div>

</div>

@endsection
