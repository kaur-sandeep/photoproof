@extends('user.layouts.master')

@section('content')

<style>
  :root{
    --green:#22c55e;
    --green-dark:#16a34a;
    --bg-card:#111a2c;
    --border:#22304a;
    --text-secondary:#9aa5b8;
  }
  .org-page.division{
    padding:150px 16px 40px !important;
    display:flex;
    justify-content:center;
  }
  .org-wrapper{
    width:100%;
    max-width:760px;
  }
  .org-card{
    background:var(--bg-card);
    border:1px solid var(--border);
    border-radius:16px;
    padding:36px 40px;
    color:#fff;
  }
  .org-card-title{
    font-size:24px;
    font-weight:700;
    margin-bottom:6px;
  }
  .org-card-subtitle{
    color:var(--text-secondary);
    font-size:14px;
    margin-bottom:28px;
  }
  .org-alert{
    padding:12px 16px;
    border-radius:10px;
    font-size:14px;
    margin-bottom:20px;
  }
  .org-alert-success{
    background:rgba(34,197,94,0.12);
    border:1px solid rgba(34,197,94,0.4);
    color:var(--green);
  }
  .org-alert-danger{
    background:rgba(239,68,68,0.12);
    border:1px solid rgba(239,68,68,0.4);
    color:#f87171;
  }
  .org-alert ul{margin-left:18px;}
  .org-form{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px 24px;
  }
  .org-form-group{display:flex;flex-direction:column;gap:8px;}
  .org-form-group.full{grid-column:1 / -1;}
  .org-form-group label{
    font-size:13px;
    font-weight:600;
    color:var(--text-secondary);
    letter-spacing:0.3px;
  }
  .org-form-group label .req{color:var(--green);margin-left:3px;}
  .org-form-group input, .org-form-group select, .org-form-group textarea{
    background:#0b1424;
    border:1px solid var(--border);
    border-radius:10px;
    padding:12px 14px;
    color:#fff;
    font-size:14px;
    outline:none;
    font-family:inherit;
    transition:border-color .15s, box-shadow .15s;
  }
  .org-form-group textarea{
    resize:vertical;
    min-height:100px;
  }
  .org-form-group input::placeholder, .org-form-group textarea::placeholder{color:#5b6579;}
  .org-form-group input:focus, .org-form-group select:focus, .org-form-group textarea:focus{
    border-color:var(--green);
    box-shadow:0 0 0 3px rgba(34,197,94,0.18);
  }
  .org-form-group select{
    appearance:none;
    background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%239aa5b8' stroke-width='2'><polyline points='6 9 12 15 18 9'/></svg>");
    background-repeat:no-repeat;
    background-position:right 14px center;
    padding-right:36px;
  }
  .org-footer{
    grid-column:1 / -1;
    display:flex;
    justify-content:flex-end;
    gap:12px;
    margin-top:8px;
    border-top:1px solid var(--border);
    padding-top:24px;
  }
  .org-btn{
    padding:12px 28px;
    border-radius:10px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    border:none;
  }
  .org-btn-primary{
    background:var(--green);
    color:#062012;
  }
  .org-btn-primary:hover{background:var(--green-dark);}
  .org-btn-secondary{
    background:transparent;
    border:1px solid var(--border);
    color:var(--text-secondary);
  }
  .org-btn-secondary:hover{border-color:#3a4a68;color:#fff;}
  @media (max-width:640px){
    .org-form{grid-template-columns:1fr;}
    .org-card{padding:28px 22px;}
  }
</style>


<section class="second-row no-bg wide-50 division org-page">
  <div class="container">
    <div class="org-wrapper mx-auto">

      <div class="org-card">
        <div class="org-card-title">Create Organization</div>
        <div class="org-card-subtitle">Fill in the details below to register your organization with Photo Proof.</div>
@if(session('success'))
    <div id="flash-message" class="org-alert org-alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div id="flash-message" class="org-alert org-alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
       <form method="POST" action="{{ route('organization.store') }}">
          @csrf

          <div class="org-form">
            <div class="org-form-group">
              <label>Organization name<span class="req">*</span></label>
              <input type="text" name="organization_name" placeholder="Enter organization name" value="{{ old('organization_name') }}">
            </div>

            <div class="org-form-group">
              <label>Business type</label>
              <input type="text" name="business_type" placeholder="Enter business type" value="{{ old('business_type') }}">
            </div>

            <div class="org-form-group">
              <label>Owner name</label>
              <input type="text" name="owner_name" placeholder="Enter owner name" value="{{ old('owner_name') }}">
            </div>

            <div class="org-form-group">
              <label>Email address<span class="req">*</span></label>
              <input type="email" name="organization_email" placeholder="Enter email" value="{{ old('organization_email') }}">
            </div>

            <div class="org-form-group">
              <label>Mobile number</label>
              <input type="text" name="mobile_number" placeholder="Enter mobile number" value="{{ old('mobile_number') }}">
            </div>

            <div class="org-form-group">
              <label>Password<span class="req">*</span></label>
              <input type="password" name="password" placeholder="Enter password">
            </div>

            <div class="org-form-group full">
              <label>Message</label>
              <textarea name="message" placeholder="Enter your message" rows="4">{{ old('message') }}</textarea>
            </div>

            <div class="org-footer">
              <button type="button" class="org-btn org-btn-secondary" onclick="window.history.back()">Cancel</button>
              <button type="submit" class="org-btn org-btn-primary">Create organization</button>
            </div>
          </div>

        </form>
      </div>

    </div>
  </div>
</section>

<script>
    setTimeout(function () {
        const flash = document.getElementById('flash-message');
        if (flash) {
            flash.style.transition = 'opacity 0.5s ease';
            flash.style.opacity = '0';

            setTimeout(() => {
                flash.remove();
            }, 500);
        }
    }, 3000); // Hide after 3 seconds
</script>
@endsection