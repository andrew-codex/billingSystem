<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Forgot Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    :root{
      --bg1:#f6f8fb; --bg2:#eef2f7;
      --card:#ffffff; --text:#111827; --muted:#6b7280;
      --primary:#4f46e5; --primary-dark:#4338ca;
      --ring:#e5e7eb; --ring-focus:#c7d2fe;
      --danger:#ef4444; --danger-bg:#fdecec;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0; font-family: system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif;
      color:var(--text);
      background: radial-gradient(900px 600px at 0% 0%, #f0f4ff 0%, transparent 60%),
                  linear-gradient(180deg, var(--bg1), var(--bg2));
    }
    .wrap{
      min-height:100vh; display:flex; align-items:center; justify-content:center; padding:24px;
    }
    .card{
      width:100%; max-width:420px;
      background:var(--card); border:1px solid #e8ecf2; border-radius:16px;
      box-shadow: 0 20px 60px rgba(17,24,39,.08);
      padding:28px;
    }
    h2{ margin:0 0 18px; text-align:center; font-size:22px; font-weight:700; letter-spacing:.2px; }
    .alert{
      background:var(--danger-bg); color:var(--danger);
      border:1px solid rgba(239,68,68,.25);
      padding:12px 14px; border-radius:10px; font-size:14px; margin-bottom:14px;
    }
    .field{ margin-bottom:16px; }
    label{ display:block; font-size:14px; color:var(--muted); margin-bottom:8px; }
    .input{
      width:100%; padding:12px 14px; border-radius:12px;
      border:1px solid var(--ring); background:#fff; color:var(--text);
      outline:none; transition: box-shadow .18s ease, border-color .18s ease, transform .04s ease;
    }
 
    .input:focus{ border-color:var(--primary); box-shadow:0 0 0 4px var(--ring-focus); outline:none; }
    .btn{
      width:100%; padding:12px 14px; border:none; border-radius:12px;
      color:#fff; background:linear-gradient(135deg, var(--primary), var(--primary-dark));
      font-weight:600; letter-spacing:.2px; cursor:pointer;
      transition: filter .2s ease, transform .06s ease, box-shadow .2s ease;
      box-shadow: 0 14px 30px -12px rgba(79,70,229,.5);
    }
    .btn:hover{ filter:brightness(1.05) }
    .btn:active{ transform:translateY(1px) }
    .hint{ font-size:12px; color:var(--muted); text-align:center; margin-top:10px; }
    .footer{ text-align:center; font-size:14px; margin-top:18px; }
    .link{ color:#4b5563; text-decoration:none; }
    .link:hover{ color:#111827; text-decoration:underline; }
    ul{ margin:6px 0 0 18px; padding:0; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h2>Forgot Password</h2>

      @if ($errors->any())
        <div class="alert">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('password.request.otp') }}" id="otpForm">
        @csrf
        <div class="field">
          <label for="email">Email Address</label>
          <input
            type="email"
            class="input"
            name="email"
            id="email"
            value="{{ old('email') }}"
            placeholder="Enter your registered email"
            required
          />
        </div>

        <button type="submit" class="btn">Send OTP</button>
        <p class="hint">You’ll be redirected to the reset form to enter the OTP.</p>
      </form>

      <div class="footer">
        <a href="{{ route('login') }}" class="link">Back to login</a>
      </div>
    </div>
  </div>
</body>
</html>
