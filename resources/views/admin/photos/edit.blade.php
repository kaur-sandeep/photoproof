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
        <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Photo</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                 <form method="POST" action="{{ route('admin.photo.update',$photo->id) }}" enctype="multipart/form-data">
                    @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="email">Random Id</label>
                    <input type="email" name ="random_id" class="form-control" id="randomid" value = "{{ $photo->random_id }}" readonly>
                  </div>
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input type="name" name ="name" class="form-control" id="name" placeholder="Enter Name"  value="{{ $photo->name}}">
                  </div>

                    <div class="mb-3">
                      @if($photo->photo)
                          <div class="mb-2">
                              <img src="{{ asset('storage/profile/'.$photo->photo) }}"
                                  alt="photo"
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
                      <input type="file" name="photo" class="form-control" id="inputGroupFile02">
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
