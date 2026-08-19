@extends('admin.layouts.master')
@section('title', 'Owner Profile')
@section('content')

<div class="container-fluid">
    <div class="admin-page-header">
        <h3 class="card-title"><b>Profile Information </b></h3>
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

         <form id="editprofileForm" method="POST" action="{{route('owner.profile.update')}}"  enctype="multipart/form-data">
             @csrf
                    <!--begin::Body-->
                    <div class="card-body row">
                       <div class="mb-3 col-md-4">
                        <label for="exampleInputEmail1" class="form-label">Name <span class="text-danger"></span></label>
                        <input
                          type="text"
                          name ="name"
                          class="form-control"
                          id="name"
                          value="{{ $user->name ?? 'na' }}"
                        />
                      </div>
                      <div class="mb-3 col-md-4">
                        <label for="exampleInputEmail1" class="form-label">Email <span class="text-danger">*</span></label>
                        <input
                          type="email"
                          name="email"
                          class="form-control"
                          id="email"
                          value = "{{ $user->email }}"
                         readOnly/>
                      </div>
                      <div class="mb-3 col-md-4">
                        <label for="exampleInputEmail1" class="form-label">Phone Number <span class="text-danger"></span></label>
                        <input
                          type="text"
                          name="number"
                          class="form-control"
                          id="number"
                          value = "{{ $user->phone_number }}"
                         />
                      </div>

                    <div class="mb-3 col-md-4">
                      @if($user->profile_image)
                          <div class="mb-2">
                              <img src="{{ asset('storage/'.$user->profile_image) }}"
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
                  <!-- <div class="card-footer d-flex justify-content-center gap-3"> -->
                    <div class="card-footer  gap-3">
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>

                    <a href="{{ route('owner.change.password') }}" class="btn btn-warning">
                        Change Password
                    </a>

                     <a href="{{ route('owner.employee') }}" class="btn btn-secondary">
                        Back
                    </a>
                </div>
                  <!--end::Footer-->
                </form>

    </div>

</div>
<div class="container-fluid" style="margin-top: 20px;">
   <div class="admin-page-header">
        <h3 class="card-title"><b>Plan Details </b></h3>
    </div>
@if($subscription)
@php($monthly = max(0, $subscription->monthly_photo_limit - $subscription->monthly_photo_used))
@php($topup = max(0, $subscription->topup_photo_limit - $subscription->topup_photo_used))
<div class="card"><div class="card-body"><h4><strong>{{ $subscription->plan->name }}</strong></h4><div class="row">
<div class="col-md-4">Start: {{ $subscription->starts_at->format('d M Y') }}</div><div class="col-md-4">Expiry: {{ $subscription->expires_at->format('d M Y') }}</div><div class="col-md-4">Monthly: {{ $subscription->monthly_photo_used }} / {{ $subscription->monthly_photo_limit }}</div>
<div class="col-md-4 mt-2">Monthly remaining: {{ $monthly }}</div><div class="col-md-4 mt-2">Top-up: {{ $subscription->topup_photo_used }} / {{ $subscription->topup_photo_limit }}</div><div class="col-md-4 mt-2">Total remaining: {{ $monthly + $topup }}</div>
</div><div class="mt-3"><a class="btn btn-primary" href="{{ route('owner.renew') }}">Renew Plan</a> <a class="btn btn-outline-primary" href="{{ route('owner.topup') }}">Top Up Photos</a></div></div></div>
@if($scheduledRenewals->isNotEmpty())
<div class="alert alert-info mt-3 mb-0"><strong>Scheduled renewals</strong><div class="mt-2">
@foreach($scheduledRenewals as $scheduledRenewal)
<div class="alert alert-info mt-3 mb-0"><strong>Renewal scheduled</strong> — {{ $scheduledRenewal->plan->name }} starts {{ $scheduledRenewal->starts_at->format('d M Y') }} and ends {{ $scheduledRenewal->expires_at->format('d M Y') }}.</div>
@endforeach
</div></div>
@endif
@else <div class="alert alert-warning">No active subscription. Create a renewal order to restore access.</div><a class="btn btn-primary" href="{{ route('owner.renew') }}">Choose a Plan</a>@endif
</div>
@endsection
