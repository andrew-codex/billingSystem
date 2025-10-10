<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Reset Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    * { box-sizing: border-box; }

    body {
      background-color: #f9fafb;
      font-family: 'Inter', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
    }

    .container {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
      padding: 2rem 2rem;
      width: 100%;
      max-width: 430px;
    }

    .icon-circle {
      background: linear-gradient(135deg, #7c3aed, #6366f1);
      color: #fff;
      width: 60px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      margin: 0 auto 1rem;
      font-size: 1.4rem;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    h2 {
      text-align: center;
      font-size: 1.7rem;
      font-weight: 700;
      color: #111827;
      margin-bottom: 0.5rem;
    }

    p {
      text-align: center;
      font-size: 0.9rem;
      color: #6b7280;
      margin-bottom: 1.5rem;
    }

    .relative {
      position: relative;
      margin-bottom: 1.2rem;
    }

    .relative i {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      color: #9ca3af;
      font-size: 0.9rem;
    }

    input[type="password"], input[type="text"], input[type="number"] {
      width: 100%;
      padding: 12px 38px;
      border: 1px solid #d1d5db;
      border-radius: 8px;
      font-size: 0.95rem;
      transition: all 0.2s;
    }

    input:focus {
      border-color: #6366f1;
      box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
      outline: none;
    }

    .toggle-btn {
      position: absolute;
      top: 50%;
      right: 24px;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      color: #6b7280;
      font-size: 1rem;
    }

    .toggle-btn:hover { color: #4f46e5; }

    .gradient-btn {
      width: 100%;
      background: linear-gradient(135deg, #7c3aed, #6366f1);
      border: none;
      color: #fff;
      padding: 12px;
      font-weight: 600;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s;
    }

    .gradient-btn:disabled {
      background: #d1d5db;
      cursor: not-allowed;
    }

    .gradient-btn:hover:not(:disabled) {
      background: linear-gradient(135deg, #6d28d9, #4f46e5);
      box-shadow: 0 4px 12px rgba(99,102,241,0.3);
    }

    .resend {
      margin-top: 1rem;
      text-align: center;
      font-size: 0.9rem;
      color: #6b7280;
    }

    .resend button {
      background: none;
      border: none;
      color: #4f46e5;
      font-weight: 500;
      cursor: pointer;
      font-size: 0.9rem;
      padding: 0;
    }

    .resend button:hover {
      text-decoration: underline;
    }

    .resend button:disabled {
      color: #9ca3af;
      cursor: not-allowed;
      text-decoration: none;
    }

    #passwordErrors, #matchMessage , #otpError {
      text-align: left;
      font-size: 0.8rem;
      margin-top: -2px;
      margin-bottom: 8px;
    }

    label {
      display: block;
      font-size: 0.9rem;
      color: #111827;
      margin-bottom: 0.5rem;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="icon-circle">
      <i class="fas fa-lock"></i>
    </div>
    <h2>Reset Password</h2>
    <p>Enter the OTP sent to your email and create a new password</p>

    <form method="POST" action="{{ route('password.reset') }}" id="resetForm">
      @csrf
      <input type="hidden" name="email" value="{{ session('reset_email') ?? old('email') }}">

      <label><b>OTP</b></label>
      <div class="relative">
        <i class="fas fa-key"></i>
        <input type="number" name="otp" id="otp" placeholder="Enter 6-digit OTP" required maxlength="6">
      </div>
      <p id="otpError" style="color:red;">
  @error('otp') {{ $message }} @enderror
</p>

      <label><b>New Password</b></label>
      <div class="relative">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" id="password" placeholder="Enter new password" required>
        <button type="button" id="togglePassword" class="toggle-btn"><i class="fas fa-eye"></i></button>
      </div>
      <p id="passwordErrors"></p>

      <label><b>Confirm Password</b></label>
      <div class="relative">
        <i class="fas fa-lock"></i>
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Re-enter new password" required>
        <button type="button" id="toggleConfirm" class="toggle-btn"><i class="fas fa-eye"></i></button>
      </div>
      <p id="matchMessage"></p>

      <button type="submit" class="gradient-btn" id="submitBtn" disabled>
        <i class="fas fa-unlock-alt"></i> Reset Password
      </button>
    </form>

    <div class="resend">
      <form id="resendForm" method="POST" action="{{ route('password.resend.otp') }}">
        @csrf
        <input type="hidden" name="email" value="{{ session('reset_email') ?? old('email') }}">
        <button type="button" id="resendButton"><span id="resendText">Resend OTP (60s)</span></button>
      </form>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const passwordErrors = document.getElementById('passwordErrors');
    const matchMessage = document.getElementById('matchMessage');
    const otpInput = document.getElementById('otp');
    const submitBtn = document.getElementById('submitBtn');

    const rules = [
      { regex: /.{8,}/, text: "At least 8 characters" },
      { regex: /[A-Z]/, text: "One uppercase letter" },
      { regex: /[a-z]/, text: "One lowercase letter" },
      { regex: /[0-9]/, text: "One number" },
      { regex: /[@$!%*#?&]/, text: "One special character" }
    ];

    function updatePasswordErrors(value) {
      if (!value) {
        passwordErrors.textContent = '';
        return false;
      }
      const passed = rules.every(r => r.regex.test(value));
      passwordErrors.textContent = passed ? '✔ Strong password' : '✖ Weak password';
      passwordErrors.style.color = passed ? 'green' : 'red';
      return passed;
    }

    function checkMatch() {
      if (!confirmInput.value) {
        matchMessage.textContent = '';
        return false;
      }
      const match = passwordInput.value === confirmInput.value;
      matchMessage.textContent = match ? 'Passwords match' : 'Passwords do not match';
      matchMessage.style.color = match ? 'green' : 'red';
      return match;
    }

    function validateForm() {
      const strong = updatePasswordErrors(passwordInput.value);
      const match = checkMatch();
      const otpValid = otpInput.value.length === 6;
      submitBtn.disabled = !(strong && match && otpValid);
    }

    passwordInput.addEventListener('input', validateForm);
    confirmInput.addEventListener('input', validateForm);
    otpInput.addEventListener('input', validateForm);
otpInput.addEventListener('input', () => {
  otpError.textContent = '';
  otpError.style.color = '';
});

    function toggleVisibility(btn, input) {
      const icon = btn.querySelector('i');
      if (input.type === "password") {
        input.type = "text";
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = "password";
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    }
    document.getElementById('togglePassword').addEventListener('click', e => toggleVisibility(e.currentTarget, passwordInput));
    document.getElementById('toggleConfirm').addEventListener('click', e => toggleVisibility(e.currentTarget, confirmInput));

   
    const resendButton = document.getElementById('resendButton');
    const resendText = document.getElementById('resendText');
    const resendForm = document.getElementById('resendForm');
    const DISABLE_SECS = 60;
    const STORAGE_KEY = "resendOtpEndTime";

    let resendEndTime = localStorage.getItem(STORAGE_KEY);
    if (!resendEndTime || Date.now() > resendEndTime) {
      resendEndTime = Date.now() + DISABLE_SECS * 1000;
      localStorage.setItem(STORAGE_KEY, resendEndTime);
    } else {
      resendEndTime = parseInt(resendEndTime);
    }

    function updateCountdown() {
      const remaining = Math.max(0, Math.floor((resendEndTime - Date.now()) / 1000));
      resendText.textContent = remaining > 0 ? `Resend OTP (${remaining}s)` : "Resend OTP";
      resendButton.disabled = remaining > 0;
      if (remaining <= 0) localStorage.removeItem(STORAGE_KEY);
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);

    resendButton.addEventListener('click', () => {
      resendEndTime = Date.now() + DISABLE_SECS * 1000;
      localStorage.setItem(STORAGE_KEY, resendEndTime);
      updateCountdown();
      resendForm.submit();
    });
  });
  </script>
</body>
</html>
