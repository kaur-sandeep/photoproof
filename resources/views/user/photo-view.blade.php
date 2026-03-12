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
         <div class="left_photoID">#{{ $photo->random_id }}</div>	
         <div class="col-md-12 mx-auto">
			      <!-- <div class="verifyphoto"> -->
                   <form method="POST" action="{{ route('photo.search') }}"  class="verifyphoto">
					<label>Verify a New photo</label>
					<!-- <input type="text" name="photoid" class="form-control" placeholder="Enter Photo ID •  e.g. 9865XXXXX"> 
                    <a href="verify-photo.html" class="btn btn-lightgreen submit ">Verify</a> -->

                    
                        @csrf
                        <input type="text" name="random_id" class="form-control" value="{{ old('random_id') }}"  placeholder="Enter Photo ID •  e.g. 9865XXXXX" required >
                        <button  class="btn btn-lightgreen submit " type="submit">Verify</button>
                  
				  <!-- </div> -->

                </form>
                    @if(session('error'))
                        <p style="color:red">{{ session('error') }}</p>
                    @endif
               </div>
         <div class="row d-flex align-items-center">	
            	
			<div class="col-sm-12 col-lg-9">
                    
                    <div class="btn-cntr">
                         <span class="views-count">{{$photo->view_count}} Views</span>
                        <a href="{{route('photo.download', $photo->id)}}" class="download-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#38d762" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3v12"></path>
                            <path d="M7 10l5 5 5-5"></path>
                            <path d="M5 21h14"></path>
                        </svg>
                        </a>
                    </div>
                    <div id="mobile-image-box" class="photo_bx">
                        <div class="gallery-grid" id="galleryGrid">
                           <div class="gallery-item"  data-category="Landscape">
                              <img src="{{ $photo->photo_url }}"  loading="lazy">                               
                              <a href="javascript:;"  class="expand-icon">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#38d762" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="15 3 21 3 21 9"></polyline>
                                    <polyline points="9 21 3 21 3 15"></polyline>
                                    <line x1="21" y1="3" x2="14" y2="10"></line>
                                    <line x1="3" y1="21" x2="10" y2="14"></line>
                                 </svg>
                              </a>
							  <!-- <a href="{{ $photo->photo_url }}" class="download-icon"> -->                                
                           </div>
                        </div>
                     </div>
                   <!-- <div class="mt-3">
                    <a href="{{ $photo->photo_url }}" 
                    download 
                    class="btn btn-primary">
                    Download Image
                    </a>
                </div>		 -->
			</div>
             
			<div class="col-sm-12 col-lg-3">
                <div class="details-top">This photo is available for {{ $daysAvailable }} days.  <a href="{{ url('/report/' . $photo->random_id) }}" class=""/>Report This Photo</a></div>
                @if($photo->uploadTrack)	
                  @php
                    $track = $photo->uploadTrack;
                @endphp	
                <div class="ip-details-container">
                   
                    
                    <div class="photo_details">
                        <!-- <div class="right_photoID">#{{ $photo->random_id }}</div> -->
                        
                            @if(!empty($photo->meta_data))

                                @php
                                
                                    $width = $photo->meta_data['width'] ?? null;
                                    $height = $photo->meta_data['height'] ?? null;
                                    $orientation = $photo->meta_data['orientation'] ?? null;
                                    $make = $photo->meta_data['make'] ?? null;
                                    $model = $photo->meta_data['model'] ?? null;
                                    $exposure_time = $photo->meta_data['exposure_time'] ?? null;
                                    $f_number = $photo->meta_data['f_number'] ?? null;
                                    $iso = $photo->meta_data['iso'] ?? null;
                                    $focal_length = $photo->meta_data['focal_length'] ?? null;
                                @endphp
                                 <div class="right_photoID">Photo & Device Information 
                              
                                 </div>
<div class="image-meta">
                                <span>@if($width && $height)
                                    {{ $width }} x {{ $height }}
                                @endif</span>

                               <span> @if($orientation)
                                    {{ $orientation }}
                                @endif </span>
                                <div class="clear"></div>
<!-- </div>

 
<div class="image-meta"> -->
  @if(!empty($photo->device_name ) && $photo->device_name != 0)					
					<span> {{ $photo->device_name }}</span> 
                     @endif
 @if(!empty($photo->device_type) && $photo->device_type != 0)	
					<span>{{ $photo->device_type }}   @if(!empty($photo->android_version ) && $photo->android_version != 0)					
					/ {{ $photo->android_version }}
                     @endif</span>


                      @if(!empty($photo->ios_system_version ) && $photo->ios_system_version != 0)					
					<span> {{ $photo->ios_system_version }}</span>
                     @endif

                      @if(!empty($photo->ios_identifier ) && $photo->ios_identifier != 0)					
					<span>IOS Identifier:</strong> {{ $photo->ios_identifier }}</span> 
                     @endif

                    @endif
                      @if(!empty($photo->device_brand) && $photo->device_brand != 0)	
					<!-- <li><strong>Device Brand:</strong> {{ $photo->device_brand }}</li>
                     @endif
                    @if(!empty($photo->device_model) && $photo->device_model != 0)	
					    <li><strong>Device Model:</strong> {{ $photo->device_model }}</li>	
                    @endif  -->

                    

                    <!--@if(!empty($photo->device_manufacturer ) && $photo->device_manufacturer != 0)					
					<li><strong>Device Manufacturer:</strong> {{ $photo->device_manufacturer }}</li> 
                     @endif -->

                    


                     <!--  @if(!empty($photo->android_sdk) && $photo->android_sdk != 0)					
					<li><strong>Android Sdk :</strong> {{ $photo->android_sdk }}</li> 
                     @endif -->

@php
function exifFraction($value) {
    if (!$value) return null;

    if (str_contains($value, '/')) {
        [$num, $den] = explode('/', $value);
        if ($den != 0) {
            return round($num / $den, 2);
        }
    }

    return $value;
}
@endphp

                                <span>@if($make)
                                    {{ $make }}
</span>
<span> 
                                @endif
                                  @if($model)
                                    {{ $model }}
</span>
<div class="clear"></div>
                              <span>  @endif
                                   @if($exposure_time)
                                    {{ $exposure_time }}sec
</span>
<span>
                                @endif
                                   @if($f_number)
                                   f{{ exifFraction($f_number) }}
</span>
<span>
                                @endif
                                   @if($iso)
                                    ISO{{ $iso }} 
</span>
<span>

                                @endif
                                   @if($focal_length)
                                    {{ exifFraction($focal_length) }} mm
                                @endif
</span>





</div>
                    

                                @endif
                        
<!-- 
 <div class="right_photoID">Capture Details</div>   
 
                   <div class="image-meta">
 <div class="clear"></div>
                       @if(!empty($photo->word_api_date_time))
                                    Date & Time : {{$photo->word_api_date_time}}
                                @endif 
                                      <div class="clear"></div>
                </div> -->
                   <div class="right_photoID">Location</div>   
                   <div class="image-meta">
                   
                   <span> 
                    
                   @if(!empty($track->isp) && $track->isp != 0)					
					ISP : {{ $track->isp }}@endif
                    <div class="clear"></div>
                    Location : 
                    {{$photo->location}}</span>
                    <div class="clear"></div>
                   <span>
                    Timezone : 
                     @if(isset($photo->timezone, $photo->timezone))
                       {{$photo->timezone }} ,
                    @elseif(isset($track->timezone, $track->timezone))
                       {{$track->timezone}}
                    @endif

</span>
<div class="clear"></div>
<span>
 @if(isset($photo->latitude, $photo->longitude))
                       
                             Lat : {{ number_format($photo->latitude, 8) }}° N, 
                             Long: {{ number_format($photo->longitude, 8) }}° E
                      
                    @elseif(isset($track->latitude, $track->longitude))
                     
                           Lat : {{ $track->latitude }}° N, Long{{ $track->longitude }}° E
                       
                    @endif
                     @php
                        $lat = $photo->latitude ?? $track->latitude ?? null;
                        $lng = $photo->longitude ?? $track->longitude ?? null;
                    @endphp

                  
</span>
                		@if(!empty($lat) && !empty($lng))
                    <!-- @if(!empty($track->ip_address) && $track->ip_address != 0)				
				    <li><strong>IP Address:</strong> {{ $track->ip_address }}</li>
                    @endif -->

</div>
</div>
                    
                  
                    
                    
                    	<!--<ul class="ip-details">
				 <li><strong>Name:</strong>{{ $photo->name }}</li> -->
					<!-- <li><strong>Photo ID:</strong> {{ $photo->random_id }}</li> -->
				
                     <!--  @if(!empty($photo->word_api_date_time))
					    <li><strong>Date & Time:</strong> {{$photo->word_api_date_time}}</li> -->
                   <!--  @endif
                     @if(!empty($photo->location))
					    <li><strong>Location:</strong> {{$photo->location}}</li>
                    @endif -->

                    <!-- @if(isset($photo->country, $photo->country))
                        <li><strong>Country :</strong>  {{$photo->country }}  </li>
                    @elseif(isset($track->country, $track->country))
                         <li><strong>Country:</strong> {{$track->country}}</li>
                    @endif

                     @if(isset($photo->region_name, $photo->region_name))
                        <li><strong>Region : </strong>  {{$photo->region_name }}  </li>
                    @elseif(isset($track->region_name, $track->region_name))
                         <li><strong>Region:</strong> {{$track->region_name}}</li>
                    @endif

                    @if(isset($photo->city, $photo->city))
                        <li><strong>City: </strong>  {{$photo->city }}  </li>
                    @elseif(isset($track->city, $track->city))
                         <li><strong>City:</strong> {{$track->city}}</li>
                    @endif

                     @if(isset($photo->zip, $photo->zip))
                        <li><strong>Zip: </strong>  {{$photo->zip }}  </li>
                    @elseif(isset($track->zip, $track->zip))
                         <li><strong>Zip:</strong> {{$track->zip}}</li>
                    @endif -->

                     <!--  @if(isset($photo->timezone, $photo->timezone))
                        <li><strong>Timezone: </strong>  {{$photo->timezone }}  </li>
                    @elseif(isset($track->timezone, $track->timezone))
                         <li><strong>Timezone:</strong> {{$track->timezone}}</li>
                    @endif -->
                   

                  <!-- @if(isset($photo->latitude, $photo->longitude))
                        <li>
                            <strong>Latitude & Longitude:</strong> 
                             {{ number_format($photo->latitude, 8) }}° N, 
                            {{ number_format($photo->longitude, 8) }}° E
                        </li>
                    @elseif(isset($track->latitude, $track->longitude))
                        <li>
                            <strong>Latitude & Longitude:</strong> 
                            {{ $track->latitude }}° N, {{ $track->longitude }}° E
                        </li>
                    @endif -->
                    <!-- @if(!empty($track->ip_address) && $track->ip_address != 0)				
				    <li><strong>IP Address:</strong> {{ $track->ip_address }}</li>
                    @endif -->
                   
                    <!-- @if(!empty($photo->device_type) && $photo->device_type != 0)	
					<li><strong>Device Type:</strong> {{ $photo->device_type }}</li>
                    @endif
                      @if(!empty($photo->device_brand) && $photo->device_brand != 0)	
					<li><strong>Device Brand:</strong> {{ $photo->device_brand }}</li>
                     @endif
                    @if(!empty($photo->device_model) && $photo->device_model != 0)	
					    <li><strong>Device Model:</strong> {{ $photo->device_model }}</li>	
                    @endif  

                      @if(!empty($photo->device_name ) && $photo->device_name != 0)					
					<li><strong>Device Name:</strong> {{ $photo->device_name }}</li> 
                     @endif

                    @if(!empty($photo->device_manufacturer ) && $photo->device_manufacturer != 0)					
					<li><strong>Device Manufacturer:</strong> {{ $photo->device_manufacturer }}</li> 
                     @endif

                      @if(!empty($photo->android_version ) && $photo->android_version != 0)					
					<li><strong>Android Version:</strong> {{ $photo->android_version }}</li> 
                     @endif


                      @if(!empty($photo->android_sdk) && $photo->android_sdk != 0)					
					<li><strong>Android Sdk :</strong> {{ $photo->android_sdk }}</li> 
                     @endif -->


                     <!--  @if(!empty($photo->ios_system_version ) && $photo->ios_system_version != 0)					
					<li><strong>IOS System Version:</strong> {{ $photo->ios_system_version }}</li> 
                     @endif

                      @if(!empty($photo->ios_identifier ) && $photo->ios_identifier != 0)					
					<li><strong>IOS Identifier:</strong> {{ $photo->ios_identifier }}</li> 
                     @endif

                      @if(!empty($track->isp) && $track->isp != 0)					
					<li><strong>ISP:</strong> {{ $track->isp }}</li> 
                     @endif 

       

				    </ul>-->
                   
                </div>
                    <div class="map">
                       <div class="map-preview" 
                            data-lat="{{ $lat }}" 
                            data-lng="{{ $lng }}"
                            style="position:relative; cursor:pointer;">

                            <iframe 
                                width="100%" 
                                height="250" 
                                style="border:0;"
                                loading="lazy"
                                src="https://maps.google.com/maps?q={{ $lat }},{{ $lng }}&z=15&output=embed">
                            </iframe>

                            <div style="
                                position:absolute;
                                top:0;
                                left:0;
                                width:100%;
                                height:100%;
                                z-index:10;">
                            </div>
                        </div>
                    </div>
                      </div>
                @endif
                @else
                    <p>No upload tracking data found.</p>
                @endif
			</div>	
         </div>
         </div>
         <!-- End container -->
    </section>


<div id="lightbox">
        <div class="lb-topbar">
            <div class="lb-counter" id="lbCounter"></div>
            <div class="lb-title"   id="lbTitle"></div>
            <button class="lb-close" id="lbClose"><i class="fas fa-times"></i></button>
        </div>
        <div class="lb-main" id="lbMain">
            <button class="lb-arrow prev" id="lbPrev"><i class="fas fa-chevron-left"></i></button>
            <img id="lbImg" src="" alt="">
            <button class="lb-arrow next" id="lbNext"><i class="fas fa-chevron-right"></i></button>
        </div>
        <div class="lb-thumbs" id="lbThumbs"></div>
</div>










@endsection