@extends('user.layouts.master')

@section('content')

<style>
    .activation-page {
        padding: 50px 0 70px;
        background: #0b1424;
        min-height: calc(100vh - 150px);
    }

    .activation-container {
        max-width: 850px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .activation-card {
        background: #111a2c;
        border: 1px solid #22304a;
        border-radius: 18px;
        padding: 45px;
        color: #fff;
        text-align: center;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.20);
    }

    .activation-icon {
        width: 78px;
        height: 78px;
        margin: 0 auto 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .activation-icon.success {
        background: rgba(34, 197, 94, 0.12);
        border: 1px solid rgba(34, 197, 94, 0.35);
    }

    .activation-icon.failed {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.35);
    }

    .activation-icon svg {
        width: 38px;
        height: 38px;
    }

    .activation-title {
        font-size: 30px;
        font-weight: 700;
        margin-bottom: 14px;
    }

    .activation-title.success {
        color: #22c55e;
    }

    .activation-title.failed {
        color: #f87171;
    }

    .activation-message {
        max-width: 680px;
        margin: 0 auto 30px;
        color: #a7b1c2;
        font-size: 15px;
        line-height: 1.8;
    }

    .username {
        color: #fff;
        font-weight: 600;
    }

    .download-section {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 1px solid #26344d;
    }

    .download-title {
        font-size: 21px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .download-description {
        color: #8f9bad;
        font-size: 14px;
        line-height: 1.7;
        margin-bottom: 22px;
    }

    .app-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .app-button {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 190px;
        padding: 12px 18px;
        border-radius: 10px;
        background: #050a13;
        border: 1px solid #33415c;
        color: #fff;
        text-decoration: none;
        transition: all .2s ease;
    }

    .app-button:hover {
        color: #fff;
        border-color: #64748b;
        transform: translateY(-2px);
    }

    .app-button svg {
        width: 30px;
        height: 30px;
        flex-shrink: 0;
    }

    .app-button-text {
        text-align: left;
    }

    .app-button-small {
        display: block;
        color: #8f9bad;
        font-size: 10px;
        line-height: 1;
        margin-bottom: 4px;
    }

    .app-button-name {
        display: block;
        font-size: 15px;
        font-weight: 600;
    }

    .steps-section {
        margin-top: 35px;
        text-align: left;
    }

    .steps-title {
        text-align: center;
        font-size: 21px;
        font-weight: 600;
        margin-bottom: 22px;
    }

    .step {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 17px;
        margin-bottom: 12px;
        background: #0c1525;
        border: 1px solid #1e2c43;
        border-radius: 10px;
    }

    .step-number {
        width: 35px;
        height: 35px;
        min-width: 35px;
        border-radius: 50%;
        background: #1d4ed8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
    }

    .step-content h4 {
        margin: 0 0 5px;
        font-size: 15px;
        font-weight: 600;
    }

    .step-content p {
        margin: 0;
        color: #8f9bad;
        font-size: 13px;
        line-height: 1.6;
    }

    @media (max-width: 767px) {
        .activation-page {
            padding: 35px 0 50px;
        }

        .activation-card {
            padding: 32px 20px;
        }

        .activation-title {
            font-size: 24px;
        }

        .app-button {
            width: 100%;
        }
    }

    .first-login-section {
    margin-top: 30px;
    padding: 28px 24px;
    border-radius: 14px;
    background: #0c1525;
    border: 1px solid #2d405c;
    text-align: center;
}

.first-login-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 14px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(37, 99, 235, 0.12);
    border: 1px solid rgba(37, 99, 235, 0.35);
    color: #60a5fa;
}

.first-login-icon svg {
    width: 22px;
    height: 22px;
}

.first-login-title {
    color: #fff;
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 8px;
}

.first-login-description {
    max-width: 600px;
    margin: 0 auto 20px;
    color: #8f9bad;
    font-size: 14px;
    line-height: 1.7;
}

.otp-box {
    display: inline-block;
    min-width: 180px;
    padding: 14px 25px;
    margin: 5px 0 10px;
    border-radius: 10px;
    background: #050a13;
    border: 1px solid #3b82f6;
    color: #fff;
    font-size: 34px;
    font-weight: 700;
    letter-spacing: 8px;
}

.otp-expiry {
    color: #8f9bad;
    font-size: 12px;
    margin: 5px 0 16px;
}

.otp-expiry strong {
    color: #fbbf24;
}

.otp-instruction {
    padding: 12px 15px;
    border-radius: 8px;
    background: #111a2c;
    color: #8f9bad;
    font-size: 13px;
    line-height: 1.6;
}

.otp-instruction strong {
    color: #fff;
}

@media (max-width: 767px) {

    .first-login-section {
        padding: 24px 18px;
    }

    .otp-box {
        min-width: 160px;
        font-size: 28px;
        letter-spacing: 6px;
    }
}
</style>


{{-- Breadcrumb --}}
<section class="breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h2>Account Activated!</h2>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb-list">
                        <li>
                            <a href="/">Home</a>
                        </li>
                        <li>Account Activated!</li>
                    </ol>
                </nav>

            </div>
        </div>
    </div>
</section>


{{-- Activation Content --}}
<section class="activation-page">
    <div class="activation-container">

        <div class="activation-card">

            @if($success)

                {{-- Success Icon --}}
                <div class="activation-icon success">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="#22c55e"
                         stroke-width="2.5"
                         stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </div>

                {{-- Title --}}
                <div class="activation-title success">
                    Account Activated
                </div>

                {{-- Message --}}
                <p class="activation-message">
                    Dear
                    <span class="username">
                        {{ $employee->name ?? $employee->display_name ?? 'User' }}
                    </span>,
                    your PhotoProof account has been successfully activated.
                    You can now download the PhotoProof mobile application
                    and start using your corporate account.
                </p>

                @if(!empty($first_login_otp))

                    <div class="first-login-section">

                        <div class="first-login-icon">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="10" rx="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>

                        <div class="first-login-title">
                            Your First Login OTP
                        </div>

                        <p class="first-login-description">
                            Use the OTP below to complete your first login in the
                            PhotoProof mobile application.
                        </p>

                        <div class="otp-box">
                            {{ $first_login_otp }}
                        </div>

                        <p class="otp-expiry">
                            This OTP is valid for <strong>20 minutes</strong>.
                        </p>

                        <div class="otp-instruction">
                            Open the PhotoProof app, select
                            <strong>Login as Corporate Account</strong>,
                            enter your registered email address and use this OTP.
                        </div>

                    </div>

                @endif


                {{-- Download App --}}
                <div class="download-section">

                    <div class="download-title">
                        Get Started with PhotoProof
                    </div>

                    <p class="download-description">
                        Download the PhotoProof mobile application and log in
                        using your registered organization email address.
                    </p>

                    <div class="app-buttons">

                  <a href="{{ config('app.app_urls.ios') }}"
                        class="store"
                        target="_blank"
                        rel="noopener noreferrer">

                            <img class="appstore-white"
                                src="{{ asset('user/images/store_badges/appstore.png') }}"
                                width="155"
                                height="50"
                                alt="Download PhotoProof on the App Store">
                        </a>

                        <a href="{{ config('app.app_urls.android') }}"
                        class="store"
                        target="_blank"
                        rel="noopener noreferrer">

                            <img class="googleplay-white"
                                src="{{ asset('user/images/store_badges/googleplay.png') }}"
                                width="164"
                                height="50"
                                alt="Get PhotoProof on Google Play">
                        </a>
                    </div>
                </div>


                {{-- Steps --}}
                <div class="steps-section">

                    <div class="steps-title">
                        How to Get Started
                    </div>

                    <div class="step">
                        <div class="step-number">1</div>

                        <div class="step-content">
                            <h4>Download the App</h4>
                            <p>
                                Download PhotoProof from the App Store or
                                Google Play using the buttons above.
                            </p>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-number">2</div>

                        <div class="step-content">
                            <h4>Login as Corporate Account</h4>
                            <p>
                                Open the PhotoProof app, select
                                <strong>Login as Corporate Account</strong>,
                                enter your registered email address and
                                complete OTP verification.
                            </p>
                        </div>
                    </div>

                    <div class="step">
                        <div class="step-number">3</div>

                        <div class="step-content">
                            <h4>Start Using PhotoProof</h4>
                            <p>
                                Once logged in, you can capture and upload
                                photos through the PhotoProof app. Your
                                photos will automatically be linked to
                                your Company account.
                            </p>
                        </div>
                    </div>

                </div>

            @else

                {{-- Failed Icon --}}
                <div class="activation-icon failed">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="#f87171"
                         stroke-width="2.5"
                         stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </div>

                <div class="activation-title failed">
                    Activation Failed
                </div>

                <p class="activation-message">
                    {{ $message }}
                </p>

            @endif

        </div>

    </div>
</section>

@endsection