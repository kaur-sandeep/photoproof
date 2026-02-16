<h2>Photo</h2>

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
@endif


