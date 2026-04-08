<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { background: white; max-width: 500px; margin: 0 auto; border-radius: 10px; padding: 30px; }
        .otp-box { background: #1e293b; color: white; font-size: 36px; font-weight: bold;
                   letter-spacing: 10px; text-align: center; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .footer { color: #999; font-size: 12px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="color:#1e293b;">Business Permit System</h2>
        <p>Hello, <strong>{{ $user->name }}</strong>!</p>
        <p>Your One-Time Password (OTP) for account verification is:</p>

        <div class="otp-box">{{ $otp }}</div>

        <p>This OTP is valid for <strong>10 minutes</strong>. Do not share it with anyone.</p>
        <p>If you did not request this, please ignore this email.</p>

        <div class="footer">
            &copy; {{ date('Y') }} Municipal Business Permit and Licensing Office
        </div>
    </div>
</body>
</html>