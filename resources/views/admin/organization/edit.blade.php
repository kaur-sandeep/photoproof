@extends('admin.layouts.master')

@section('title', 'Add Organization User')

@section('content')

<div class="container-fluid">
    <div class="admin-page-header">
        <h3 class="card-title"><b>Add Organization User </b></h3>
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
                 <form method="POST" action="{{ route('admin.update.organization', $organization->id) }}" enctype="multipart/form-data">
                    @csrf
                 <div class="card-body row">
                  <div class="form-group col-md-4">
                    <label for="organization_name">Organization Name <span class="text-danger">*</span></label>
                    <input type="text" name="organization_name" class="form-control" id="organization_name" placeholder="Enter Organization Name" value="{{ $organization->organization_name }}">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="business_type">Business Type <span class="text-danger"></span></label>
                    <input type="text" name="business_type" class="form-control" id="business_type" placeholder="Enter Business Type" value="{{ $organization->business_type}}">
                  </div>
                  <div class="form-group col-md-4">
                    <label for="owner_name">Owner Name <span class="text-danger"></span></label>
                    <input type="text" name ="owner_name" class="form-control" id="owner_name" placeholder="Enter Owner Name" value="{{ $organization->owner_name}}">  
                  </div>
                  <div class="form-group col-md-4">
                    <label for="organization_email"> Email Address <span class="text-danger">*</span></label>
                    <input type="email" name ="organization_email" class="form-control" id="organization_email" placeholder="Enter Email" value="{{ $organization->organization_email}}" readonly  >
                  </div>
                  
                   <div class="form-group col-md-4">
                    <label for="mobile_number">Mobile  Number <span class="text-danger"></span></label>
                    <input type="text" name ="mobile_number" class="form-control" id="mobile_number" placeholder="Enter Mobile Number" value="{{ $organization->mobile_number}}">
                  </div>
                 

                  <div class="form-group col-md-4">
                    <label for="subscription_plan">Subscription Plan<span class="text-danger">*</span></label>
                        <select name="subscription_plan" class="form-control" id="subscription_plan">
                            <option value="">Select Subscription Plan</option>
                            <option value="1" {{ old('subscription_plan', $organization->subscription_plan ?? '') == 1 ? 'selected' : '' }}>Basic</option>
                            <option value="2" {{ old('subscription_plan', $organization->subscription_plan ?? '') == 2 ? 'selected' : '' }}>Standard</option>
                            <option value="3" {{ old('subscription_plan', $organization->subscription_plan ?? '') == 3 ? 'selected' : '' }}>Premium</option>
                        </select>
                  </div>
                 
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Update</button>
                    <!-- <a href="{{ route('admin.users.data') }}" class="btn btn-secondary">
                        Back
                    </a> -->
                </div>
              </form>
            </div>
    </div>

</div>

@endsection
