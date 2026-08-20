<form method="post" action="{{ route('admin.billing.topups.state', [$topup, $topup->state ? 0 : 1]) }}">
    @csrf
    <button class="btn btn-sm {{ $topup->state ? 'btn-success' : 'btn-secondary' }}">{{ $topup->state ? 'Active' : 'Inactive' }}</button>
</form>
