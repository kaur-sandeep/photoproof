@extends('user.layouts.master')
@section('title', 'Corporate Pricing')
@section('content')
<style>
    .pricing-page { padding: 150px 0 90px; min-height: 75vh; }
    .pricing-heading { max-width: 680px; margin: 0 auto 48px; text-align: center; }
    .pricing-eyebrow { color: #37d67a; font-size: .75rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; }
    .pricing-heading h1 { color: #fff; font-size: clamp(2rem, 4vw, 3.1rem); font-weight: 700; margin: 12px 0; }
    .pricing-heading p { color: rgba(255,255,255,.75); font-size: 1.1rem; margin: 0; }
    .pricing-card { background: #fff; border: 1px solid rgba(255,255,255,.6); border-radius: 16px; box-shadow: 0 20px 45px rgba(0,0,0,.24); color: #1d2a3b; height: 100%; overflow: hidden; transition: transform .2s ease, box-shadow .2s ease; }
    .pricing-card:hover { box-shadow: 0 28px 60px rgba(0,0,0,.34); transform: translateY(-6px); }
    .pricing-card__top { background: linear-gradient(135deg, #f7fbf9, #eef8f3); border-bottom: 1px solid #e3eee8; padding: 30px 30px 24px; text-align: center; }
    .pricing-card__name { color: #12253e; font-size: 1.6rem; font-weight: 700; margin: 0; }
    .pricing-card__price { color: #18b963; font-size: 2.7rem; font-weight: 700; letter-spacing: -.06em; line-height: 1; margin: 20px 0 6px; }
    .pricing-card__price small { font-size: 1.2rem; letter-spacing: 0; vertical-align: 12%; }
    .pricing-card__price-note { color: #738092; font-size: .88rem; margin: 0; }
    .pricing-card__body { display: flex; flex-direction: column; min-height: 310px; padding: 26px 30px 30px; }
    .pricing-card__limits { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 24px; }
    .pricing-limit { background: #f5f8fa; border-radius: 9px; padding: 12px 8px; text-align: center; }
    .pricing-limit strong { color: #1b2d45; display: block; font-size: 1.05rem; }
    .pricing-limit span { color: #748194; display: block; font-size: .75rem; margin-top: 3px; }
    .pricing-features { border-top: 1px solid #e8edf0; list-style: none; margin: 0 0 26px; padding: 18px 0 0; }
    .pricing-features li { color: #526174; font-size: .94rem; line-height: 1.45; padding: 6px 0 6px 25px; position: relative; }
    .pricing-features li::before { color: #1ec46a; content: '\F26A'; font-family: 'bootstrap-icons'; font-size: 1rem; left: 0; position: absolute; }
    .pricing-action { background: #e94f2b; border: 0; border-radius: 7px; color: #fff !important; font-size: .95rem; font-weight: 700; margin-top: auto; padding: 14px 20px; text-align: center; text-decoration: none; transition: background .2s ease, transform .2s ease; }
    .pricing-action:hover { background: #cf3f1e; transform: translateY(-1px); }
    @media (max-width: 767.98px) { .pricing-page { padding: 120px 0 60px; } .pricing-heading { margin-bottom: 30px; } .pricing-card__body { min-height: auto; } }
</style>

<section class="pricing-page">
    <div class="container">
        <div class="pricing-heading">
            <div class="pricing-eyebrow">Simple, transparent pricing</div>
            <h1>Plans that scale with your team</h1>
            <p>Choose a shared photo capacity plan for your company.</p>
        </div>

        <div class="row justify-content-center">
            @forelse($plans as $plan)
                @php($features = array_values(array_filter(preg_split('/\r\n|\r|\n/', (string) $plan->description), fn ($feature) => trim($feature) !== '')))
                <div class="col-md-6 col-lg-4 mb-4">
                    <article class="pricing-card">
                        <div class="pricing-card__top">
                            <h2 class="pricing-card__name">{{ $plan->name }}</h2>
                            <div class="pricing-card__price"><small>₹</small>{{ number_format($plan->price, 2) }}</div>
                            <p class="pricing-card__price-note">per month</p>
                        </div>
                        <div class="pricing-card__body">
                            <div class="pricing-card__limits">
                                <div class="pricing-limit"><strong>{{ number_format($plan->monthly_photo_limit) }}</strong><span>photos per month</span></div>
                                @if($plan->yearly_price !== null)<div class="pricing-limit"><strong>₹{{ number_format($plan->yearly_price, 2) }}</strong><span>per year</span></div>@endif
                            </div>

                            @if($features)
                                <ul class="pricing-features">
                                    @foreach($features as $feature)
                                        <li>{{ trim($feature) }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <a class="pricing-action" href="{{ route('organization', ['plan' => $plan->id, 'billing_cycle' => 'monthly']) }}">Choose monthly <i class="bi bi-arrow-right ms-1"></i></a>
                            @if($plan->yearly_price !== null)<a class="pricing-action mt-2" href="{{ route('organization', ['plan' => $plan->id, 'billing_cycle' => 'yearly']) }}">Choose yearly</a>@endif
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12"><div class="alert alert-info">Corporate plans are not available yet. Please contact us.</div></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
