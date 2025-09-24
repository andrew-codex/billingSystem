<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Billing System - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('/CSS_Styles/mainCss/welcome.css') }}">
    <link rel="stylesheet" href="{{asset('/CSS_Styles/mainCss/base.css')}}">
</head>
<body>
 @include('includes.loading')

    <div class="login-container">
        <div class="login-image">
            <img src="{{ url('/images/logo.jpg') }}" alt="Electricity Meter">
        </div>  

        <div class="login-form">
            <h2>Welcome Back</h2>
            <form method="POST" id="loginForm" action="{{ route('login.perform') }}">
                @csrf
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>

                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" placeholder="Enter your password" oninput="toggleEyeVisibility()">
                    <i id="eyeIcon" class="fa-solid fa-eye-slash" onclick="togglePassword()" style="display: none;"></i>
                </div>

           

                <div class="extra-links">
                <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                    <a href="">Forgot Password?</a>
                </div>

                <button type="submit">Login</button>
            </form>



        </div>
    </div>

    
<script>
function togglePassword() {
    const passwordInput = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
    } else {
        passwordInput.type = "password";
        eyeIcon.classList.replace("fa-eye", "fa-eye-slash");
    }
}


function toggleEyeVisibility() {
    const passwordInput = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");

    if (passwordInput.value.length > 0) {
        eyeIcon.style.display = "block";
    } else {
        eyeIcon.style.display = "none";
       
        if (eyeIcon.classList.contains("fa-eye-slash")) {
            eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
        }
        passwordInput.type = "password";
    }
}
</script>



</body>
</html>
