@extends('user.layouts.master')



@section('content')


<!-- phonto variable

    "id" => 5
    "random_id" => "4464bg6h4j5b6asda"
    "user_id" => 2
    "name" => "My Holiday Photo"
    "location" => "New York"
    "photo" => "photos/l6Sl4fqyCSH7lLDeW0ugF62D4AXmhoX9ce4s3IZx.jpg"
    "state" => 1
    "view_count" => 1
    "created_at" => "2026-02-19 07:05:08"
    "updated_at" => "2026-02-19 07:05:27"
    "word_api_date_time" => null
    "latitude" => null
    "longitude" => null
    "device_type" => "IOS"
    "device_brand" => "Iphone"
    "device_model" => "Iphone 13"
    "device_name" => "iphone 13"
    "device_manufacturer" => "calafornia"
    "android_version" => null
    "android_sdk" => null
    "ios_system_version" => "13.0.1"
    "ios_identifier" => "sdf345d" 


    track data:
     "id" => 3
    "photo_detail_id" => 5
    "user_id" => 2
    "ip_address" => "202.164.57.197"
    "browser" => "0"
    "platform" => "0"
    "device" => "0"
    "device_type" => "Desktop"
    "user_agent" => "PostmanRuntime/7.51.1"
    "referer" => null
    "country" => "India"
    "country_code" => "IN"
    "region" => "PB"
    "region_name" => "Punjab"
    "city" => "Sangrur"
    "zip" => "148001"
    "latitude" => "30.2446"
    "longitude" => "75.848"
    "timezone" => "Asia/Kolkata"
    "isp" => "Cogniter Technologies PVT LTD"
    "org" => "Cogniter Technologies PVT LTD"
    "as_name" => "AS17917 Quadrant Televentures Limited"
    "ip_query" => "202.164.57.197"
    
    
    
    
    
    
    -->
<!-- <h2>Photo</h2>

<img src="{{ $photo->photo_url }}" width="400">

<p><strong>Uploaded Name:</strong> {{ $photo->name }}</p>
<p><strong>Uploaded Location:</strong> {{ $photo->location }}</p>

<hr>

<h3>Upload Tracking Information</h3>

@if($photo->uploadTrack)

    @php
        $track = $photo->uploadTrack;
    @endphp

    @if(!empty($track->ip_address) && $track->ip_address != 0)
        <p><strong>IP Address:</strong> {{ $track->ip_address }}</p>
    @endif

    @if(!empty($track->browser) && $track->browser != 0)
        <p><strong>Browser:</strong> {{ $track->browser }}</p>
    @endif

    @if(!empty($track->platform) && $track->platform != 0)
        <p><strong>Platform:</strong> {{ $track->platform }}</p>
    @endif

    @if(!empty($track->device) && $track->device != 0)
        <p><strong>Device:</strong> {{ $track->device }}</p>
    @endif

    @if(!empty($track->device_type) && $track->device_type != 0)
        <p><strong>Device Type:</strong> {{ $track->device_type }}</p>
    @endif

    @if(!empty($track->country))
        <p><strong>Country:</strong> {{ $track->country }}</p>
    @endif

    @if(!empty($track->region_name))
        <p><strong>Region:</strong> {{ $track->region_name }}</p>
    @endif

    @if(!empty($track->city))
        <p><strong>City:</strong> {{ $track->city }}</p>
    @endif

    @if(!empty($track->zip))
        <p><strong>Zip:</strong> {{ $track->zip }}</p>
    @endif

    @if(!empty($track->timezone))
        <p><strong>Timezone:</strong> {{ $track->timezone }}</p>
    @endif

    @if(!empty($track->isp))
        <p><strong>ISP:</strong> {{ $track->isp }}</p>
    @endif


@else
    <p>No upload tracking data found.</p>
@endif -->



<section class="breadcrumb">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
					    <h2>Tracking Information</h2>
						 <nav aria-label="breadcrumb">
							<ol class="breadcrumb-list">
								<li><a href="/">Home</a></li>
								
								<li>Verify Photo</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
</section>

<section class="second-row  wide-50 division ">
      <div class="container-fluid px-5">
         <div class="row d-flex align-items-center">			
			<div class="col-sm-12 col-lg-9">
				<div class="photo_bx">
					<img src="{{ $photo->photo_url }}" alt="photo"/>
				</div>			
			</div>
			<div class="col-sm-12 col-lg-3">		
                @if($photo->uploadTrack)	
                  @php
                    $track = $photo->uploadTrack;
                @endphp	
              
				<ul class="ip-details">
					<li><strong>Name:</strong>{{ $photo->name }}</li>

					<li><strong>Photo ID:</strong> {{ $photo->random_id }}</li>
					<li><strong>Date & Time:</strong> Feb 17, 2027  04:57:14 PM </li>
                    @if(!empty($track->country))
					    <li><strong>Country:</strong> {{$track->country}}</li>
                    @endif
                    @if(!empty($track->region_name))
					<li><strong>Region:</strong>{{ $track->region_name }}</li>
                    @endif
                     @if(!empty($track->city))
					<li><strong>City:</strong> {{ $track->city }}</li>
                     @endif
                    @if(!empty($track->zip))
					    <li><strong>Zip:</strong>  {{ $track->zip }}</li>
                    @endif
                     @if(!empty($track->timezone))
					<li><strong>Timezone:</strong> {{ $track->timezone }}</li>
                      @endif
                    @if(!empty($track->latitude) && !empty($track->longitude))
					        <li><strong>Latitude & Longitude:</strong> {{ $track->latitude }}° N, {{ $track->longitude }}° E</li>	
                     @endif
                    @if(!empty($track->ip_address) && $track->ip_address != 0)				
				    <li><strong>IP Address:</strong> {{ $track->ip_address }}</li>
                    @endif
                   
                    @if(!empty($photo->device_type) && $photo->device_type != 0)	
					<li><strong>Device Type:</strong>{{ $photo->device_type }}</li>
                    @endif
                      @if(!empty($photo->device_brand) && $photo->device_brand != 0)	
					<li><strong>Device Brand:</strong> {{ $photo->device_brand }}</li>
                     @endif
                    @if(!empty($photo->device_model) && $photo->device_model != 0)	
					    <li><strong>Device Model:</strong> {{ $photo->device_model }}</li>	
                    @endif  
                    @if(!empty($track->isp) && $track->isp != 0)					
					<li><strong>ISP:</strong> {{ $track->isp }}</li> 
                     @endif

					@if(!empty($track->latitude) && !empty($track->longitude))
                        <li class="map">
                                <iframe 
                                    width="100%" 
                                    height="250" 
                                    style="border:0;"
                                    loading="lazy"
                                    allowfullscreen
                                    referrerpolicy="no-referrer-when-downgrade"
                                    src="https://maps.google.com/maps?q={{ $track->latitude }},{{ $track->longitude }}&z=15&output=embed">
                                </iframe>
                            </li>
                        @endif
					
				</ul>
                @else
                    <p>No upload tracking data found.</p>
                @endif
			</div>			
         </div>
         </div>
         <!-- End container -->
    </section>













@endsection