<form method="POST" action="{{ route('photo.search') }}">
    @csrf
    <input type="text" name="random_id"  value="{{ old('random_id') }}"  placeholder="Enter Photo Code" required>
    <button type="submit">Search</button>
</form>

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif