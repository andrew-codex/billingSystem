<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md transition-all">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Reset Password</h2>

        {{-- OTP Sent Message --}}
        <div id="otpSentMessage"
             class="hidden bg-green-100 text-green-800 p-3 rounded mb-4 text-sm text-center animate-fadeIn">
            We’ve sent a 6-digit OTP to your email — check your inbox!
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Reset Password Form --}}
        <form method="POST" action="{{ route('password.reset') }}" class="space-y-5" id="resetForm">
            @csrf
            <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">

        
            <div>
                <label for="otp" class="block font-semibold text-gray-700 mb-2">One-Time Password (OTP)</label>
                <input type="text" name="otp" id="otp"
                       class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="6-digit OTP" maxlength="6" required>
            </div>

       
            <div>
                <label for="password" class="block font-semibold text-gray-700 mb-2">New Password</label>
                <input type="password" name="password" id="password"
                       class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Enter new password" required>
                <div id="passwordErrors" class="mt-2 space-y-1 text-sm"></div>
            </div>

        
            <div>
                <label for="password_confirmation" class="block font-semibold text-gray-700 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="Re-enter new password" required>
                <p id="matchMessage" class="text-xs mt-1 text-gray-500"></p>
            </div>

            <button type="submit"
                    class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                Reset Password
            </button>
        </form>

   
        <div class="mt-4 text-center">
            <button type="button" id="resendButton"
                    class="bg-indigo-600 text-white px-4 py-2 rounded transition cursor-not-allowed"
                    disabled>
                Resend OTP (<span id="countdown">0</span>s)
            </button>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn { animation: fadeIn .4s ease-in-out; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const countdownEl = document.getElementById('countdown');
    const resendButton = document.getElementById('resendButton');
    const otpSentMessage = document.getElementById('otpSentMessage');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const passwordErrors = document.getElementById('passwordErrors');
    const matchMessage = document.getElementById('matchMessage');
    const resetForm = document.getElementById('resetForm');

    const DISABLE_SECS = 60;
    let timerId = null;

    // Password validation
    function validatePassword(password) {
        return [
            { regex: /.{8,}/, text: 'At least 8 characters' },
            { regex: /[A-Z]/, text: 'At least one uppercase letter (A-Z)' },
            { regex: /[a-z]/, text: 'At least one lowercase letter (a-z)' },
            { regex: /[0-9]/, text: 'At least one number (0-9)' },
            { regex: /[@$!%*#?&]/, text: 'At least one special character (@$!%*#?&)' },
        ];
    }

    function updatePasswordErrors(value) {
        const rules = validatePassword(value);
        passwordErrors.innerHTML = '';
        rules.forEach(rule => {
            const passed = rule.regex.test(value);
            const line = document.createElement('div');
            line.classList.add('flex', 'items-center', 'gap-2');
            line.innerHTML = `
                <span class="text-${passed ? 'green' : 'red'}-500 text-xs">${passed ? '✔' : '✖'}</span>
                <span class="text-${passed ? 'green' : 'red'}-600 text-xs">${rule.text}</span>
            `;
            passwordErrors.appendChild(line);
        });
    }

    passwordInput?.addEventListener('input', e => {
        updatePasswordErrors(e.target.value);
        checkMatch();
    });

    function checkMatch() {
        if (!confirmInput.value) { matchMessage.textContent = ''; return; }
        if (passwordInput.value === confirmInput.value) {
            matchMessage.textContent = 'Passwords match';
            matchMessage.classList.add('text-green-600');
            matchMessage.classList.remove('text-red-600');
        } else {
            matchMessage.textContent = 'Passwords do not match';
            matchMessage.classList.add('text-red-600');
            matchMessage.classList.remove('text-green-600');
        }
    }
    confirmInput?.addEventListener('input', checkMatch);


    function updateResendUI(remaining) {
        countdownEl.textContent = remaining;
        resendButton.disabled = remaining > 0;
        resendButton.classList.toggle('cursor-not-allowed', remaining > 0);
        resendButton.classList.toggle('bg-gray-300', remaining > 0);
        resendButton.classList.toggle('text-gray-700', remaining > 0);
        resendButton.classList.toggle('bg-indigo-600', remaining === 0);
        resendButton.classList.toggle('text-white', remaining === 0);
    }

    function startTimer(untilTs) {
        clearInterval(timerId);
        const tick = () => {
            const remaining = Math.max(0, Math.ceil((untilTs - Date.now()) / 1000));
            updateResendUI(remaining);
            if (remaining <= 0) {
                clearInterval(timerId);
                localStorage.removeItem('otpUntil');
            }
        };
        tick();
        timerId = setInterval(tick, 1000);
    }

    // Load existing countdown
    const storedUntil = parseInt(localStorage.getItem('otpUntil') || '0', 10);
    if (storedUntil && storedUntil > Date.now()) {
        startTimer(storedUntil);
        otpSentMessage.classList.remove('hidden');
    } else {
        updateResendUI(0);
    }

   resendButton?.addEventListener('click', function() {
    // Get email from hidden input in reset form
    const emailInput = resetForm.querySelector('input[name="email"]');
    const email = emailInput ? emailInput.value : null;

    if (!email) return alert('Email is missing!');

    const newUntil = Date.now() + DISABLE_SECS * 1000;
    localStorage.setItem('otpUntil', String(newUntil));
    localStorage.setItem('otpSent', '1');
    otpSentMessage.classList.remove('hidden');
    startTimer(newUntil);

    // AJAX POST request to resend OTP
    fetch("{{ route('password.request.otp') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": resetForm.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({ email })
    })
    .then(res => res.json())
    .then(data => console.log(data))
    .catch(err => console.error(err));
});

</script>
