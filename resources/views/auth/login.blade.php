<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InquiSmart — Staff Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f2e6e 0%, #1549A3 50%, #1B5FC4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            padding: 20px;
        }

        /* Animated background dots */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(255,255,255,0.05) 1px, transparent 1px),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 1;
        }

        /* Brand header */
        .brand-section {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-logo {
            width: 72px;
            height: 72px;
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            border: 1.5px solid rgba(255,255,255,0.25);
            backdrop-filter: blur(10px);
        }

        .brand-logo i {
            font-size: 32px;
            color: #fff;
        }

        .brand-name {
            font-size: 26px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.5px;
            display: block;
        }

        .brand-sub {
            font-size: 13px;
            color: rgba(255,255,255,0.65);
            margin-top: 3px;
            display: block;
        }

        /* Card */
        .login-card {
            background: #fff;
            border-radius: 20px;
            padding: 36px 36px 32px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.25), 0 4px 16px rgba(0,0,0,0.1);
        }

        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 4px;
        }

        .card-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 26px;
        }

        /* Form */
        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-control {
            height: 46px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            color: #1a1a2e;
            padding: 0 14px;
            transition: all 0.2s;
            background: #f9fafb;
        }

        .form-control:focus {
            border-color: #1549A3;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(21,73,163,0.1);
            outline: none;
        }

        .input-group .form-control {
            border-right: none;
            border-radius: 10px 0 0 10px;
        }

        .input-group-text {
            border: 1.5px solid #e5e7eb;
            border-left: none;
            border-radius: 0 10px 10px 0;
            background: #f9fafb;
            cursor: pointer;
            padding: 0 14px;
            color: #6b7280;
            transition: all 0.2s;
        }

        .input-group:focus-within .form-control,
        .input-group:focus-within .input-group-text {
            border-color: #1549A3;
            background: #fff;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 3px rgba(21,73,163,0.1);
            border-radius: 10px;
        }

        .input-group:focus-within .form-control {
            box-shadow: none;
        }

        /* Remember me */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 16px 0 22px;
        }

        .form-check-input {
            width: 16px;
            height: 16px;
            border: 1.5px solid #d1d5db;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #1549A3;
            border-color: #1549A3;
        }

        .form-check-label {
            font-size: 13px;
            color: #6b7280;
            margin-left: 6px;
            cursor: pointer;
        }

        /* Button */
        .btn-login {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #1549A3, #1B5FC4);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.2px;
            transition: all 0.2s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #0f3d8a, #1549A3);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(21,73,163,0.35);
        }

        .btn-login:active { transform: translateY(0); }

        /* Divider */
        .divider {
            text-align: center;
            position: relative;
            margin: 22px 0;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0; right: 0; top: 50%;
            height: 1px;
            background: #e5e7eb;
        }

        .divider span {
            background: #fff;
            padding: 0 12px;
            font-size: 12px;
            color: #9ca3af;
            position: relative;
        }

        /* Staff badge */
        .staff-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 12.5px;
            color: #1E40AF;
            font-weight: 500;
        }

        /* Error */
        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
            color: #DC2626;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .invalid-feedback-custom {
            font-size: 12px;
            color: #DC2626;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: rgba(255,255,255,0.5);
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <!-- Brand -->
    <div class="brand-section">
        <div class="brand-logo">
            <i class="bi bi-headset"></i>
        </div>
        <span class="brand-name">InquiSmart</span>
        <span class="brand-sub">Customer Helpdesk System — NAN Cellphone Shop</span>
    </div>

    <!-- Card -->
    <div class="login-card">
        <div class="card-title">Welcome back 👋</div>
        <div class="card-subtitle">Sign in to your staff account to continue</div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert-error">
                <i class="bi bi-info-circle-fill"></i>
                {{ session('status') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-right:none;border-left:1.5px solid #e5e7eb;border-radius:10px 0 0 10px;order:-1;">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="form-control" placeholder="staff@nancellphone.com"
                        required autofocus autocomplete="username"
                        style="border-left:none;border-radius:0 10px 10px 0;">
                </div>
                @error('email')
                    <div class="invalid-feedback-custom"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-0">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text" style="border-right:none;border-left:1.5px solid #e5e7eb;border-radius:10px 0 0 10px;order:-1;">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input id="password" type="password" name="password"
                        class="form-control" placeholder="Enter your password"
                        required autocomplete="current-password"
                        style="border-left:none;border-right:none;border-radius:0;">
                    <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer;">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>
                @error('password')
                    <div class="invalid-feedback-custom"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="remember-row">
                <div class="form-check d-flex align-items-center gap-2">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label for="remember_me" class="form-check-label">Remember me</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size:13px;color:#1549A3;text-decoration:none;font-weight:500;">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i>
                Sign In to Dashboard
            </button>
        </form>

        <div class="divider"><span>Staff access only</span></div>

        <div class="staff-badge">
            <i class="bi bi-shield-check-fill" style="color:#1549A3;font-size:16px;"></i>
            <span>This portal is for authorized NAN CS staff members only. Customer access is via the mobile app.</span>
        </div>
    </div>

    <div class="login-footer">
        © 2026 InquiSmart · NAN Cellphone Shop · All rights reserved
    </div>
</div>

<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        pwd.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>

</body>
</html>