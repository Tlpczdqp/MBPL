<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Reset Your Password</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI',
                         Roboto, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            padding: 40px 16px;
        }

        .wrapper    { max-width: 520px; margin: 0 auto; }

        .header {
            background-color: #1e293b;
            border-radius: 12px 12px 0 0;
            padding: 28px 32px;
            text-align: center;
        }
        .header h1  { color: #fff; font-size: 18px; font-weight: 700; }
        .header p   { color: #94a3b8; font-size: 12px; margin-top: 4px; }

        .card {
            background-color: #ffffff;
            border-radius: 0 0 12px 12px;
            padding: 36px 32px;
            border: 1px solid #e2e8f0;
            border-top: none;
        }

        .greeting   { font-size: 15px; color: #334155; margin-bottom: 12px; }
        .message    { font-size: 14px; color: #64748b; line-height: 1.6; margin-bottom: 28px; }

        /* Big reset button */
        .btn-wrap   { text-align: center; margin-bottom: 28px; }
        .btn {
            display: inline-block;
            background-color: #1e293b;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 15px;
            font-weight: 700;
            padding: 14px 36px;
            border-radius: 8px;
            letter-spacing: 0.3px;
        }

        /* Fallback URL box */
        .url-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 24px;
        }
        .url-box p  { font-size: 12px; color: #64748b; margin-bottom: 6px; }
        .url-box a  {
            font-size: 11px;
            color: #3b82f6;
            word-break: break-all;
            text-decoration: none;
        }

        /* Warning box */
        .warning-box {
            background-color: #fefce8;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 14px 16px;
            margin-bottom: 24px;
        }
        .warning-box p { font-size: 13px; color: #854d0e; line-height: 1.5; }

        .divider    { border: none; border-top: 1px solid #e2e8f0; margin: 24px 0; }

        .footer     { text-align: center; }
        .footer p   { font-size: 12px; color: #94a3b8; line-height: 1.6; }
        .footer .brand { font-weight: 600; color: #64748b; }
    </style>
</head>
<body>
    <div class="wrapper">

        {{-- Header --}}
        <div class="header">
            <h1>Business Permit System</h1>
            <p>Municipal Business Permit and Licensing Office</p>
        </div>

        {{-- Card --}}
        <div class="card">

            <p class="greeting">Hello, <strong>{{ $user->name }}</strong>!</p>

            <p class="message">
                We received a request to reset the password for your account.
                Click the button below to choose a new password.
                This link is valid for <strong>60 minutes</strong>.
            </p>

            {{-- Big Button --}}
            <div class="btn-wrap">
                {{-- $resetUrl is a public property from PasswordResetMail.php --}}
                <a href="{{ $resetUrl }}" class="btn">
                    Reset My Password
                </a>
            </div>

            {{-- Fallback URL in case button doesn't work --}}
            <div class="url-box">
                <p>If the button doesn't work, copy and paste this link into your browser:</p>
                <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
            </div>

            {{-- Warning --}}
            <div class="warning-box">
                <p>
                    <strong>⚠ Did not request this?</strong>
                    If you did not request a password reset, please ignore this email.
                    Your password will remain unchanged and your account is safe.
                </p>
            </div>

            <hr class="divider" />

            <div class="footer">
                <p>
                    This is an automated message from the<br />
                    <span class="brand">Municipal Business Permit and Licensing Office</span>
                </p>
                <p style="margin-top: 8px;">&copy; {{ date('Y') }} All rights reserved.</p>
            </div>

        </div>
    </div>
</body>
</html>