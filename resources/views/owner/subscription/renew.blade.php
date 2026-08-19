@extends('admin.layouts.master')
@section('title', 'Renew Plan')
@section('content')
<style>
    .subscription-plans { max-width: 1120px; margin: 0 auto; padding: 20px 0 50px; }
    .subscription-heading { margin-bottom: 30px; text-align: center; }
    .subscription-heading h3 { color: #12253e; font-size: clamp(1.8rem, 3vw, 2.5rem); font-weight: 700; margin: 8px 0; }
    .subscription-heading p { color: #738092; margin: 0; }
    .subscription-eyebrow { color: #18b963; font-size: .75rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
    .billing-toggle { align-items: center; background: #eef3f7; border: 1px solid #dce5ec; border-radius: 999px; display: inline-flex; gap: 4px; margin-top: 20px; padding: 4px; }
    .billing-toggle__option { background: transparent; border: 0; border-radius: 999px; color: #66768a; cursor: pointer; font-size: .9rem; font-weight: 700; padding: 9px 18px; }
    .billing-toggle__option.is-active { background: #fff; box-shadow: 0 2px 6px rgba(18,37,62,.12); color: #14243b; }
    .billing-toggle__badge { background: #37d67a; border-radius: 999px; color: #0d4026; font-size: .68rem; font-weight: 800; margin-left: 5px; padding: 3px 6px; text-transform: uppercase; }
    .pricing-card { background: #fff; border: 1px solid #e0e8e3; border-radius: 16px; box-shadow: 0 12px 28px rgba(17,39,62,.1); color: #1d2a3b; cursor: pointer; display: block; height: 100%; overflow: hidden; transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
    .pricing-card:hover, .pricing-card.is-selected { border-color: #18b963; box-shadow: 0 18px 36px rgba(24,185,99,.18); transform: translateY(-4px); }
    .pricing-card__input { position: absolute; opacity: 0; pointer-events: none; }
    .pricing-card__top { background: linear-gradient(135deg, #f7fbf9, #eef8f3); border-bottom: 1px solid #e3eee8; padding: 28px 30px 24px; text-align: center; }
    .pricing-card__name { color: #12253e; font-size: 1.6rem; font-weight: 700; margin: 0; }
    .pricing-card__period { color: #738092; font-size: .72rem; font-weight: 800; letter-spacing: .12em; margin-top: 20px; text-transform: uppercase; }
    .pricing-card__price-row { align-items: baseline; display: flex; gap: 11px; justify-content: center; margin: 6px 0 5px; }
    .pricing-card__price { color: #18b963; font-size: 2.7rem; font-weight: 700; letter-spacing: -.06em; line-height: 1; }
    .pricing-card__price small { font-size: 1.2rem; letter-spacing: 0; vertical-align: 12%; }
    .pricing-card__original-price { color: #8290a1; font-size: 1.05rem; font-weight: 700; text-decoration: line-through; text-decoration-thickness: 2px; }
    .pricing-card__price-note { color: #738092; font-size: .88rem; margin: 0; }
    .pricing-card__body { display: flex; flex-direction: column; min-height: 180px; padding: 26px 30px 30px; }
    .pricing-limit { background: #f5f8fa; border-radius: 9px; padding: 12px 8px; text-align: center; }
    .pricing-limit strong { color: #1b2d45; display: block; font-size: 1.05rem; }
    .pricing-features { border-top: 1px solid #e8edf0; list-style: none; margin: 20px 0 0; padding: 14px 0 0; }
    .pricing-features li { color: #526174; font-size: .94rem; line-height: 1.45; padding: 5px 0 5px 25px; position: relative; }
    .pricing-features li::before { color: #1ec46a; content: '\F26A'; font-family: 'bootstrap-icons'; left: 0; position: absolute; }
    .renew-submit { background: #e94f2b; border: 0; border-radius: 7px; color: #fff; font-size: .95rem; font-weight: 700; margin-top: 10px; padding: 14px 24px; }
    .renew-submit:hover { background: #cf3f1e; color: #fff; }
</style>

<div class="container-fluid">
    <form method="post" action="{{ route('owner.renew.store') }}">
        @csrf
        <input type="hidden" name="billing_cycle" value="monthly" data-billing-cycle-input>
        <section class="subscription-plans">
            <div class="subscription-heading">
                <div class="subscription-eyebrow">Continue your subscription</div>
                <h3>Renew your plan</h3>
                <p>Choose the plan that best fits your team’s photo capacity.</p>
                <div class="billing-toggle" role="group" aria-label="Billing cycle">
                    <button class="billing-toggle__option is-active" type="button" data-billing-cycle="monthly" aria-pressed="true">Monthly</button>
                    <button class="billing-toggle__option" type="button" data-billing-cycle="yearly" aria-pressed="false">Yearly <span class="billing-toggle__badge">Save</span></button>
                </div>
            </div>

            <div class="row justify-content-center">
                @forelse($plans as $plan)
                    @php($features = array_values(array_filter(preg_split('/\r\n|\r|\n/', (string) $plan->description), fn ($feature) => trim($feature) !== '')))
                    <div class="col-md-6 col-lg-4 mb-4">
                        <label class="pricing-card" data-plan-card data-monthly-price="{{ number_format((float) $plan->monthly_price, 2, '.', '') }}" data-yearly-price="{{ $plan->yearly_price !== null ? number_format((float) $plan->yearly_price, 2, '.', '') : '' }}">
                            <input class="pricing-card__input" type="radio" name="subscription_plan_id" value="{{ $plan->id }}" required>
                            <div class="pricing-card__top">
                                <h2 class="pricing-card__name">{{ $plan->name }}</h2>
                                <div class="pricing-card__period" data-billing-period>1 MONTH</div>
                                <div class="pricing-card__price-row">
                                    <div class="pricing-card__price"><small>$</small><span data-price>{{ number_format($plan->monthly_price, 2) }}</span></div>
                                    <span class="pricing-card__original-price" data-original-price hidden></span>
                                </div>
                                <p class="pricing-card__price-note">Actual price $<span data-actual-price>{{ number_format($plan->monthly_price, 2) }}</span><span data-price-note> per month</span></p>
                            </div>
                            <div class="pricing-card__body">
                                <div class="pricing-limit"><strong>{{ number_format($plan->monthly_photo_limit) }} photos per month</strong></div>
                                @if($features)<ul class="pricing-features">@foreach($features as $feature)<li><b>{{ trim($feature) }}</b></li>@endforeach</ul>@endif
                            </div>
                        </label>
                    </div>
                @empty
                    <div class="col-12"><div class="alert alert-info">No renewal plans are currently available.</div></div>
                @endforelse
            </div>
            @error('subscription_plan_id')<div class="text-danger text-center mb-2">{{ $message }}</div>@enderror
            @error('billing_cycle')<div class="text-danger text-center mb-2">{{ $message }}</div>@enderror
            <div class="text-center"><button class="renew-submit" type="submit">Create Offline-Payment Order <i class="bi bi-arrow-right ms-1"></i></button></div>
        </section>
    </form>
</div>

<script>
    (() => {
        const cycleInput = document.querySelector('[data-billing-cycle-input]');
        const toggleOptions = document.querySelectorAll('[data-billing-cycle]');
        const cards = document.querySelectorAll('[data-plan-card]');
        const formatPrice = (price) => Number(price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const setBillingCycle = (cycle) => {
            cycleInput.value = cycle;
            toggleOptions.forEach((option) => { const active = option.dataset.billingCycle === cycle; option.classList.toggle('is-active', active); option.setAttribute('aria-pressed', active ? 'true' : 'false'); });
            cards.forEach((card) => {
                const yearlyPrice = card.dataset.yearlyPrice;
                const useYearly = cycle === 'yearly' && yearlyPrice !== '';
                const actualPrice = useYearly ? Number(yearlyPrice) : Number(card.dataset.monthlyPrice);
                const price = useYearly ? actualPrice / 12 : actualPrice;
                card.querySelector('[data-price]').textContent = formatPrice(price);
                card.querySelector('[data-billing-period]').textContent = useYearly ? '12 MONTHS' : '1 MONTH';
                card.querySelector('[data-actual-price]').textContent = formatPrice(actualPrice);
                const originalPrice = card.querySelector('[data-original-price]');
                originalPrice.hidden = !useYearly;
                originalPrice.textContent = useYearly ? `$${formatPrice(actualPrice)}` : '';
                card.querySelector('[data-price-note]').textContent = useYearly ? ` per month — billed $${formatPrice(actualPrice)} yearly` : ' per month';
            });
        };
        toggleOptions.forEach((option) => option.addEventListener('click', () => setBillingCycle(option.dataset.billingCycle)));
        cards.forEach((card) => card.querySelector('input').addEventListener('change', () => cards.forEach((item) => item.classList.toggle('is-selected', item.querySelector('input').checked))));
    })();
</script>
@endsection
