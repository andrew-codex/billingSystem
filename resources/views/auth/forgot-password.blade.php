<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Forgot Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    * {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }

    body {
      background-color: #f4f5f7;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .forgot-container {
      background-color: #ffffff;
      width: 100%;
      max-width: 400px;
      padding: 2.5rem 2rem;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
      text-align: center;
    }

    .icon-circle {
      background: linear-gradient(135deg, #7c3aed, #6366f1);
      color: white;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin: 0 auto 1.2rem;
      position: relative;
      box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
    }

    .forgot-container h2 {
      font-size: 1.6rem;
      font-weight: 700;
      color: #111827;
      margin-bottom: 0.4rem;
    }

    .forgot-container p {
      font-size: 0.9rem;
      color: #6b7280;
      margin-bottom: 1.8rem;
    }

    .form-group {
      text-align: left;
      margin-bottom: 1.5rem;
    }

    .form-group label {
      font-weight: 600;
      font-size: 0.9rem;
      color: #374151;
      display: block;
      margin-bottom: 0.4rem;
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper i {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      color: #9ca3af;
      font-size: 0.9rem;
    }

    input[type="email"] {
      width: 100%;
      padding: 12px 40px;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      font-size: 0.95rem;
      color: #111827;
      transition: all 0.2s;
    }

    input[type="email"]:focus {
      border-color: #6366f1;
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
      outline: none;
    }

    .btn {
      width: 100%;
      padding: 12px;
      font-size: 1rem;
      font-weight: 600;
      border: none;
      border-radius: 8px;
      color: #fff;
      background: linear-gradient(135deg, #7c3aed, #6366f1);
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn:hover {
      background: linear-gradient(135deg, #6d28d9, #4f46e5);
      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .error-box, .success-box {
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 0.85rem;
      margin-bottom: 1rem;
      text-align: left;
    }

    .error-box {
      background-color: #fee2e2;
      color: #b91c1c;
    }

    .success-box {
      background-color: #dcfce7;
      color: #166534;
      text-align: center;
    }

    .back-link {
      margin-top: 1.5rem;
      display: inline-block;
      font-size: 0.9rem;
      color: #4f46e5;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .forgot-container {
        padding: 2rem 1.5rem;
      }
    }
  </style>
</head>

<body>
  <div class="forgot-container">
    <div class="icon-circle">
      <i class="fas fa-envelope"></i>
    </div>

    <h2>Forgot Password?</h2>
    <p>No worries! Enter your email and we'll send you reset instructions</p>

    @if ($errors->any())
      <div class="error-box">
        <ul style="margin: 0; padding-left: 20px;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if(session('success'))
      <div class="success-box">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('password.request.otp') }}">
      @csrf
      @method('POST')

      <div class="form-group">
        <label for="email">Email Address</label>
        <div class="input-wrapper">
          <i class="fas fa-envelope"></i>
          <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Enter your email" required>
        </div>
      </div>

      <button type="submit" class="btn">Send OTP</button>
    </form>

    <a href="{{ route('login') }}" class="back-link">
      <i class="fas fa-arrow-left"></i> Back to Login
    </a>
  </div>
</body>
</html>
