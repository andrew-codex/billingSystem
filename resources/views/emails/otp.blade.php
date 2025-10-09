<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset OTP</title>
</head>
<body style="font-family: Arial, sans-serif; background-color:#f9fafb; padding:20px;">
    <div style="max-width:600px; margin:auto; background:#fff; border-radius:10px; padding:30px; text-align:center;">
        <h2 style="color:#4F46E5;">Password Reset Request</h2>
        <p style="font-size:16px; color:#333;">Use the following One-Time Password (OTP) to reset your password:</p>
        <h1 style="letter-spacing:4px; font-size:36px; color:#1F2937; margin:20px 0;">{{ $otp }}</h1>
        <p style="font-size:14px; color:#6B7280;">This OTP will expire in 5 minutes.</p>
        <p style="margin-top:30px; font-size:13px; color:#9CA3AF;">If you didn’t request this, please ignore this email.</p>
    </div>
</body>
</html>
