<h2>Photo</h2>

<img src="{{ $photo->photo_url }}" width="400">

<h3>User Information</h3>
<p>Name: {{ $photo->user->name ?? '' }}</p>
<p>Email: {{ $photo->user->email ?? '' }}</p>

<p>Uploaded Location: {{ $photo->location }}</p>
<p>Total Views: {{ $photo->view_count }}</p>