@extends('admin.layouts.master')

@section('title', 'Edit Organization')

@section('content')

<div class="container-fluid">
    <div class="admin-page-header">
        <h3 class="card-title"><b>Edit Organization</b></h3>
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
                  <div class="form-group col-md-4  mb-4">
                    <label for="organization_name">Organization Name <span class="text-danger">*</span></label>
                    <input type="text" name="organization_name" class="form-control" id="organization_name" placeholder="Enter Organization Name" value="{{ $organization->organization_name }}">
                  </div>
                  <div class="form-group col-md-4  mb-4">
                    <label for="business_type">Business Type <span class="text-danger"></span></label>
                    <input type="text" name="business_type" class="form-control" id="business_type" placeholder="Enter Business Type" value="{{ $organization->business_type}}">
                  </div>
                  <div class="form-group col-md-4  mb-4">
                    <label for="owner_name">Owner Name <span class="text-danger"></span></label>
                    <input type="text" name ="owner_name" class="form-control" id="owner_name" placeholder="Enter Owner Name" value="{{ $user_data->name ?? '' }}">  
                  </div>
                  <div class="form-group col-md-4  mb-4">
                    <label for="organization_email"> Email Address <span class="text-danger">*</span></label>
                    <input type="email" name ="organization_email" class="form-control" id="organization_email" placeholder="Enter Email" value="{{ $user_data->email}}" readonly  >
                  </div>

                 
                  
                   <div class="form-group col-md-4  mb-4">
                    <label for="mobile_number">Mobile  Number <span class="text-danger"></span></label>
                    <input type="text" name ="mobile_number" class="form-control" id="mobile_number" placeholder="Enter Mobile Number" value="{{ $user_data->phone_number}}">
                  </div>
                 

                  <div class="form-group col-md-4  mb-4">
                    <label for="subscription_plan">Subscription Plan<span class="text-danger">*</span></label>
                        <select name="subscription_plan" class="form-control" id="subscription_plan">
                          <option value="">Please Select Plan</option>
                             @foreach($allPlans as $plan)
                            <option value="{{ $plan->id }}"
                                {{ old('subscription_plan', $organization->subscription_plan ?? '') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }}
                            </option>
                          @endforeach
                        </select>
                  </div>

                    <div class="form-group col-md-4 mb-4">
                      <label for="organization_logo">
                          Organization Logo
                      </label>

                      <input
                          type="file"
                          class="form-control"
                          id="organization_logo"
                          name="organization_logo"
                          accept=".jpg,.jpeg,.png">

                      <small class="text-muted d-block mt-2">
                          JPG, PNG (Max 2MB)
                      </small>

                      <div class="mt-3">
                          <img id="logoPreview"
                              src="{{ $organization->organization_logo ? asset('storage/'.$organization->organization_logo) : asset('images/no-image.png') }}"
                              class="img-thumbnail"
                              style="width:120px;height:120px;object-fit:contain;">
                      </div>
                  </div>

                  <div class="form-group col-md-4  mb-4">
                    <div class="form-check mt-2">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="email_enabled"
                            id="email_enabled"
                            value="1"
                            {{ old('email_enabled', $organization->enable_photo_email) ? 'checked' : '' }}>

                        <label class="form-check-label" for="email_enabled">
                            Enable Email
                        </label>
                    </div>
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
<script>
$('#organization_logo').on('change', function(e){

    let file = e.target.files[0];

    if(file){

        let reader = new FileReader();

        reader.onload = function(event){

            $('#logoPreview')
                .attr('src', event.target.result);

        }

        reader.readAsDataURL(file);
    }

});
</script>
@endsection
