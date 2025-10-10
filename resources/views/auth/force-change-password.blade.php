<style>
    body {
        font-family: 'Inter', sans-serif;
        background: #f9fafb;
        margin: 0;
    }
    .page-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 16px;
    }
    .card {
        width: 100%;
        max-width: 420px;
        background: #fff;
        padding: 28px;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }
    .card-title {
        font-size: 20px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 24px;
        color: #111827;
    }
    .error-box {
        background: #fee2e2;
        color: #b91c1c;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-size: 14px;
    }
    .form-group {
        margin-bottom: 18px;
        position: relative;
    }
    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        font-size: 14px;
        color: #374151;
    }
    .form-group input {
        width: 100%;
        padding: 10px 40px 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        background: #f9fafb;
    }
    .form-group input:focus {
        outline: none;
        border-color: #9ca3af;
        box-shadow: 0 0 0 2px rgba(156,163,175,0.2);
        background: #fff;
    }
    .toggle-password {
        position: absolute;
        right: 12px;
        top: 36px;
        cursor: pointer;
        color: #9ca3af;
        transition: color 0.2s;
    }
    .toggle-password:hover { color: #111827; }
    .form-actions { margin-top: 20px; }
    .form-actions button {
        width: 100%;
        background: #111827;
        color: #fff;
        padding: 12px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        transition: background 0.2s;
    }
    .form-actions button:hover { background: #374151; }
    .password-hint {
        font-size: 12px;
        margin-top: 6px;
        color: #6b7280;
    }
    .password-strength {
        margin-top: 8px;
        font-size: 13px;
        font-weight: 600;
        display: none; 
    }
    .weak { color: #dc2626; }
    .medium { color: #d97706; }
    .strong { color: #16a34a; }
</style>

<div class="page-container">
    <div class="card">
        <h2 class="card-title">Change Your Password</h2>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.change.update') }}">
            @csrf

            <div class="form-group">
                <label for="password">New Password</label>
                <input id="password" type="password" name="password" required>
                <i class="toggle-password fas fa-eye" onclick="togglePassword('password', this)"></i>
                <div class="password-hint">At least 8 chars, 1 uppercase, 1 number, 1 special</div>
                <div id="password-strength" class="password-strength"></div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required>
                <i class="toggle-password fas fa-eye" onclick="togglePassword('password_confirmation', this)"></i>
            </div>

            <div class="form-actions">
                <button type="submit">Update Password</button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


<script>
    function togglePassword(fieldId, icon) {
        const field = document.getElementById(fieldId);
        if (field.type === "password") {
            field.type = "text";
            icon.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            field.type = "password";
            icon.classList.replace("fa-eye-slash", "fa-eye");
        }
    }

   
    const passwordInput = document.getElementById("password");
    const strengthDisplay = document.getElementById("password-strength");

    passwordInput.addEventListener("input", function() {
        const value = passwordInput.value;

        if (!value) {
            strengthDisplay.style.display = "none";
            return;
        }

        strengthDisplay.style.display = "block"; 
        let strength = 0;

        if (value.length >= 8) strength++;
        if (/[A-Z]/.test(value)) strength++;
        if (/[a-z]/.test(value)) strength++;
        if (/[0-9]/.test(value)) strength++;
        if (/[@$!%*?&]/.test(value)) strength++;

        if (strength <= 2) {
            strengthDisplay.textContent = "Weak password";
            strengthDisplay.className = "password-strength weak";
        } else if (strength === 3 || strength === 4) {
            strengthDisplay.textContent = "Medium password";
            strengthDisplay.className = "password-strength medium";
        } else if (strength === 5) {
            strengthDisplay.textContent = "Strong password";
            strengthDisplay.className = "password-strength strong";
        }
    });
</script>








