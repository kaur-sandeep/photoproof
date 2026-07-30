<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Organization | Photo Proof</title>
<style>
  :root{
    --green:#22c55e;
    --green-dark:#16a34a;
    --bg-dark:#0b1220;
    --bg-card:#111a2c;
    --border:#22304a;
    --text-primary:#ffffff;
    --text-secondary:#9aa5b8;
  }
  *{box-sizing:border-box;margin:0;padding:0;}
  body{
    font-family:'Segoe UI',Arial,sans-serif;
    background:var(--bg-dark);
    color:var(--text-primary);
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:40px 16px;
  }
  .wrapper{
    width:100%;
    max-width:760px;
  }
  .brand{
    display:flex;
    align-items:center;
    gap:10px;
    justify-content:center;
    margin-bottom:28px;
  }
  .brand .logo{
    width:36px;height:36px;border-radius:8px;
    background:var(--green);
    display:flex;align-items:center;justify-content:center;
    font-weight:700;color:#062012;
  }
  .brand h1{
    font-size:20px;
    font-weight:700;
    letter-spacing:0.5px;
  }
  .brand h1 span{color:var(--green);}

  .card{
    background:var(--bg-card);
    border:1px solid var(--border);
    border-radius:16px;
    padding:36px 40px;
  }
  .card-title{
    font-size:24px;
    font-weight:700;
    margin-bottom:6px;
  }
  .card-subtitle{
    color:var(--text-secondary);
    font-size:14px;
    margin-bottom:28px;
  }

  .alert{
    padding:12px 16px;
    border-radius:10px;
    font-size:14px;
    margin-bottom:20px;
  }
  .alert-success{
    background:rgba(34,197,94,0.12);
    border:1px solid rgba(34,197,94,0.4);
    color:var(--green);
  }
  .alert-danger{
    background:rgba(239,68,68,0.12);
    border:1px solid rgba(239,68,68,0.4);
    color:#f87171;
  }
  .alert ul{margin-left:18px;}

  form{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px 24px;
  }
  .form-group{display:flex;flex-direction:column;gap:8px;}
  .form-group.full{grid-column:1 / -1;}
  label{
    font-size:13px;
    font-weight:600;
    color:var(--text-secondary);
    letter-spacing:0.3px;
  }
  label .req{color:var(--green);margin-left:3px;}

  input, select{
    background:#0b1424;
    border:1px solid var(--border);
    border-radius:10px;
    padding:12px 14px;
    color:var(--text-primary);
    font-size:14px;
    outline:none;
    transition:border-color .15s, box-shadow .15s;
  }
  input::placeholder{color:#5b6579;}
  input:focus, select:focus{
    border-color:var(--green);
    box-shadow:0 0 0 3px rgba(34,197,94,0.18);
  }
  select{
    appearance:none;
    background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%239aa5b8' stroke-width='2'><polyline points='6 9 12 15 18 9'/></svg>");
    background-repeat:no-repeat;
    background-position:right 14px center;
    padding-right:36px;
  }

  .footer{
    grid-column:1 / -1;
    display:flex;
    justify-content:flex-end;
    gap:12px;
    margin-top:8px;
    border-top:1px solid var(--border);
    padding-top:24px;
  }
  .btn{
    padding:12px 28px;
    border-radius:10px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    border:none;
  }
  .btn-primary{
    background:var(--green);
    color:#062012;
  }
  .btn-primary:hover{background:var(--green-dark);}
  .btn-secondary{
    background:transparent;
    border:1px solid var(--border);
    color:var(--text-secondary);
  }
  .btn-secondary:hover{border-color:#3a4a68;color:var(--text-primary);}

  @media (max-width:640px){
    form{grid-template-columns:1fr;}
    .card{padding:28px 22px;}
  }
</style>
</head>
<body>

<div class="wrapper">
  <div class="brand">
    <div class="logo">P</div>
    <h1>PHOTO <span>PROOF</span></h1>
  </div>

  <div class="card">
    <div class="card-title">Create organization</div>
    <div class="card-subtitle">Fill in the details below to register your organization with Photo Proof.</div>

    <!-- <div class="alert alert-success">Organization created successfully.</div> -->
    <!-- <div class="alert alert-danger"><ul><li>Organization email is required.</li></ul></div> -->

    <form method="POST" action="#" enctype="multipart/form-data">

      <div class="form-group">
        <label>Organization name<span class="req">*</span></label>
        <input type="text" name="organization_name" placeholder="Enter organization name">
      </div>

      <div class="form-group">
        <label>Business type</label>
        <input type="text" name="business_type" placeholder="Enter business type">
      </div>

      <div class="form-group">
        <label>Owner name</label>
        <input type="text" name="owner_name" placeholder="Enter owner name">
      </div>

      <div class="form-group">
        <label>Email address<span class="req">*</span></label>
        <input type="email" name="organization_email" placeholder="Enter email">
      </div>

      <div class="form-group">
        <label>Mobile number</label>
        <input type="text" name="mobile_number" placeholder="Enter mobile number">
      </div>

      <div class="form-group">
        <label>Password<span class="req">*</span></label>
        <input type="password" name="password" placeholder="Enter password">
      </div>

      <div class="footer">
        <button type="button" class="btn btn-secondary">Cancel</button>
        <button type="submit" class="btn btn-primary">Create organization</button>
      </div>

    </form>
  </div>
</div>

</body>
</html>