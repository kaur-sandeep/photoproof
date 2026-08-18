@extends('admin.layouts.master')

@section('title', 'Add Company')

@section('content')

<div class="container-fluid">
    <div class="admin-page-header">
        <h3 class="card-title"><b>Add Company</b></h3>
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
                 <form method="POST" action="{{ route('admin.store.organization') }}" enctype="multipart/form-data">
                    @csrf
                 <div class="card-body row">
                  <div class="form-group col-md-4 mb-4">
                    <label for="organization_name">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="organization_name" class="form-control" id="organization_name" placeholder="Enter Organization Name" value="{{ old('organization_name', request('name')) }}">
                  </div>
                  <div class="form-group col-md-4 mb-4">
                    <label for="business_type">Business Type <span class="text-danger"></span></label>
                    <input type="text" name="business_type" class="form-control" id="business_type" placeholder="Enter Business Type" value="{{ old('business_type') }}">
                  </div>
                  <div class="form-group col-md-4 mb-4">
                    <label for="owner_name">Contact Person Name <span class="text-danger"></span></label>
                    <input type="text" name="owner_name" class="form-control" id="owner_name" placeholder="Enter Contact Person Name" value="{{ old('owner_name') }}">
                  </div>
                   <div class="form-group col-md-4 mb-4">
                    <label for="mobile_number">Contact Person Mobile  Number <span class="text-danger"></span></label>
                    <input type="text" name="mobile_number" class="form-control" id="mobile_number" placeholder="Enter Contact Person Mobile Number" value="{{ old('mobile_number') }}">
                  </div>
                  <div class="form-group col-md-4 mb-4">
                    <label for="organization_email"> Contact Person Email Address <span class="text-danger">*</span></label>
                    <input type="email" name="organization_email" class="form-control" id="organization_email" placeholder="Enter Contact Person Email" value="{{ old('organization_email',request('email')) }}">
                  </div>
                  <div class="form-group col-md-4 mb-4">
                    <label for="password">Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password">
                  </div>

                  <div class="form-group col-md-4 mb-4">
                    <label for="subscription_plan">Subscription Plan<span class="text-danger">*</span></label>
                   <select name="subscription_plan" class="form-control" id="subscription_plan">
                    <option value="">Please Select Plan</option>
                      @foreach($allPlans as $plan)
                    <option value="{{ $plan->id }}" {{ old('subscription_plan') == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }}
                        </option>
                    @endforeach
                   </select>
                  </div>

                  <div class="form-group col-md-4 mb-4">
                    <label>Billing Cycle <span class="text-danger">*</span></label>
                    <div class="pt-2">
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="billing_cycle" id="billing_cycle_monthly" value="monthly" @checked(old('billing_cycle', 'monthly') === 'monthly')>
                        <label class="form-check-label" for="billing_cycle_monthly">Monthly</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="billing_cycle" id="billing_cycle_yearly" value="yearly" @checked(old('billing_cycle') === 'yearly')>
                        <label class="form-check-label" for="billing_cycle_yearly">Yearly</label>
                      </div>
                    </div>
                  </div>

                  <div class="form-group col-md-4 mb-4">
                    <label for="organization_logo">
                        Organization Logo
                    </label>

                    <div class="custom-file">
                        <input
                            type="file"
                            class="custom-file-input"
                            id="organization_logo"
                            name="organization_logo"
                            accept=".jpg,.jpeg,.png"
                        >
                        <!-- <label class="custom-file-label" for="organization_logo">
                            Choose Logo
                        </label> -->
                    </div>

                    <small class="text-muted">
                        JPG, PNG (Max 2MB)
                    </small>

                    <div class="mt-3">
                        <img id="logoPreview"
                            src="{{ asset('images/no-images.png') }}"
                            style="max-height:120px;display:none;border:1px solid #ddd;padding:5px;border-radius:8px;">
                    </div>

                  </div>

                  <div class="form-group col-md-12 mb-4">
                    <label for="message">Message</label>
                    <textarea name="message" class="form-control" id="message" rows="4" placeholder="Add a message or note">{{ old('message') }}</textarea>
                  </div>

                  <div class="form-group col-md-12 mb-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="state" id="state" value="1" @checked(old('state', true))>
                      <label class="form-check-label" for="state">Active</label>
                    </div>
                  </div>

                  <div class="form-group col-md-4 mb-4">
                      <label for="email_enabled"></label>

                      <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" name="email_enabled" id="email_enabled" value="1" {{ old('email_enabled') ? 'checked' : '' }}>
                          <label class="form-check-label" for="email_enabled">
                              Enable Email
                          </label>
                      </div>
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                    <!-- <a href="{{ route('admin.users.data') }}" class="btn btn-secondary">
                        Back
                    </a> -->
                </div>
              </form>
            </div>
    </div>

</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$('#organization_logo').change(function(e){

    let file = e.target.files[0];

    if(file){

        $('.custom-file-label').text(file.name);

        let reader = new FileReader();

        reader.onload = function(event){
            $('#logoPreview')
                .attr('src',event.target.result)
                .show();
        }

        reader.readAsDataURL(file);

    }

});
</script>
@endsection
