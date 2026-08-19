@extends('admin.layouts.master')
@section('title', 'Top Up Photos')
@section('content')
<style>
    .topup-plans { max-width: 1120px; margin: 0 auto; padding: 20px 0 50px; }
    .topup-heading { margin-bottom: 30px; text-align: center; }
    .topup-heading h3 { color: #12253e; font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 700; margin: 8px 0; }
    .topup-heading p { color: #738092; margin: 0; }
    .topup-eyebrow { color: #18b963; font-size: .75rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
    .topup-card { background: #fff; border: 1px solid #e0e8e3; border-radius: 16px; box-shadow: 0 12px 28px rgba(17,39,62,.1); cursor: pointer; display: block; height: 100%; overflow: hidden; transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
    .topup-card:hover, .topup-card.is-selected { border-color: #18b963; box-shadow: 0 18px 36px rgba(24,185,99,.18); transform: translateY(-4px); }
    .topup-card__input { position: absolute; opacity: 0; pointer-events: none; }
    .topup-card__top { background: linear-gradient(135deg, #f7fbf9, #eef8f3); border-bottom: 1px solid #e3eee8; padding: 28px 30px 24px; text-align: center; }
    .topup-card__name { color: #12253e; font-size: 1.6rem; font-weight: 700; margin: 0; }
    .topup-card__label { color: #738092; font-size: .72rem; font-weight: 800; letter-spacing: .12em; margin-top: 20px; text-transform: uppercase; }
    .topup-card__price { color: #18b963; font-size: 2.7rem; font-weight: 700; letter-spacing: -.06em; line-height: 1; margin: 6px 0 5px; }
    .topup-card__price small { font-size: 1.2rem; letter-spacing: 0; vertical-align: 12%; }
    .topup-card__note { color: #738092; font-size: .88rem; margin: 0; }
    .topup-card__body { min-height: 130px; padding: 26px 30px 30px; }
    .topup-limit { background: #f5f8fa; border-radius: 9px; padding: 14px 8px; text-align: center; }
    .topup-limit strong { color: #1b2d45; display: block; font-size: 1.1rem; }
    .topup-limit span { color: #748194; display: block; font-size: .78rem; margin-top: 3px; }
    .topup-submit { background: #e94f2b; border: 0; border-radius: 7px; color: #fff; font-size: .95rem; font-weight: 700; margin-top: 10px; padding: 14px 24px; }
    .topup-submit:hover { background: #cf3f1e; color: #fff; }
</style>

<div class="container-fluid">
    <form method="post" action="{{ route('owner.topup.store') }}">
        @csrf
        <section class="topup-plans">
            <div class="topup-heading">
                <div class="topup-eyebrow">Add photo capacity</div>
                <h3>Top up your photos</h3>
                <p>Choose a photo pack to add capacity to your current plan.</p>
            </div>
            <div class="row justify-content-center">
                @forelse($topups as $topup)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <label class="topup-card" data-topup-card>
                            <input class="topup-card__input" type="radio" name="topup_plan_id" value="{{ $topup->id }}" required>
                            <div class="topup-card__top">
                                <h2 class="topup-card__name">{{ $topup->name }}</h2>
                                <div class="topup-card__label">One-time top up</div>
                                <div class="topup-card__price"><small>$</small>{{ number_format($topup->price, 2) }}</div>
                                <p class="topup-card__note">One-time payment</p>
                            </div>
                            <div class="topup-card__body"><div class="topup-limit"><strong>{{ number_format($topup->photo_quantity) }} photos</strong><span>added to your account</span></div></div>
                        </label>
                    </div>
                @empty
                    <div class="col-12"><div class="alert alert-info">No top-up plans are currently available.</div></div>
                @endforelse
            </div>
            @error('topup_plan_id')<div class="text-danger text-center mb-2">{{ $message }}</div>@enderror
            <div class="text-center"><button class="topup-submit" type="submit">Create Offline-Payment Order <i class="bi bi-arrow-right ms-1"></i></button></div>
        </section>
    </form>
</div>
<script>
    (() => {
        const cards = document.querySelectorAll('[data-topup-card]');
        cards.forEach((card) => card.querySelector('input').addEventListener('change', () => cards.forEach((item) => item.classList.toggle('is-selected', item.querySelector('input').checked))));
    })();
</script>
@endsection
