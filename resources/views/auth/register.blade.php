<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InquiSmart — Create Staff Account</title>
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

        .register-wrapper {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 1;
        }

        .brand-section {
            text-align: center;
            margin-bottom: 24px;
        }

        .brand-logo {
            width: 68px;
            height: 68px;
            background: rgba(255,255,255,0.15);
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            border: 1.5px solid rgba(255,255,255,0.25);
            backdrop-filter: blur(10px);
        }

        .brand-logo i { font-size: 30px; color: #fff; }

        .brand-name {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.5px;
            display: block;
        }

        .brand-sub {
            font-size: 12.5px;
            color: rgba(255,255,255,0.65);
            margin-top: 3px;
            display: block;
        }

        .register-card {
            background: #fff;
            border-radius: 20px;
            padding: 32px 36px 28px;
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
            margin-bottom: 22px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-control {
            height: 44px;
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

        .input-icon-wrap {
            position: relative;
        }

        .input-icon-wrap .bi {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 15px;
            pointer-events: none;
        }

        .input-icon-wrap .form-control {
            padding-left: 38px;
        }

        .input-icon-wrap .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            cursor: pointer;
            font-size: 15px;
            pointer-events: all;
        }

        .invalid-feedback-custom {
            font-size: 12px;
            color: #DC2626;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
            color: #DC2626;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Password strength */
        .password-strength {
            margin-top: 6px;
            display: flex;
            gap: 4px;
        }

        .strength-bar {
            height: 3px;
            flex: 1;
            border-radius: 2px;
            background: #e5e7eb;
            transition: background 0.3s;
        }

        .strength-bar.weak   { background: #EF4444; }
        .strength-bar.medium { background: #F59E0B; }
        .strength-bar.strong { background: #22C55E; }

        .btn-register {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #1549A3, #1B5FC4);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.2s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
        }

        .btn-register:hover {
            background: linear-gradient(135deg, #0f3d8a, #1549A3);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(21,73,163,0.35);
        }

        .btn-register:active { transform: translateY(0); }

        .login-link {
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
            color: #6b7280;
        }

        .login-link a {
            color: #1549A3;
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover { text-decoration: underline; }

        .login-footer {
            text-align: center;
            margin-top: 18px;
            font-size: 12px;
            color: rgba(255,255,255,0.5);
        }

        .row-fields { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        @media (max-width: 480px) { .row-fields { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<div class="register-wrapper">
    <!-- Brand -->
    <div class="brand-section">
        <div class="brand-logo">
            <i class="bi bi-headset"></i>
        </div>
        <span class="brand-name">InquiSmart</span>
        <span class="brand-sub">Customer Helpdesk System — NAN Cellphone Shop</span>
    </div>

    <!-- Card -->
    <div class="register-card">
        <div class="card-title">Create staff account 🛡️</div>
        <div class="card-subtitle">Register to access the InquiSmart admin panel</div>

        @if ($errors->any())
            <div class="alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-person"></i>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                        class="form-control" placeholder="Enter your full name"
                        required autofocus autocomplete="name">
                </div>
                @error('name')
                    <div class="invalid-feedback-custom"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-envelope"></i>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="form-control" placeholder="staff@nancellphone.com"
                        required autocomplete="username">
                </div>
                @error('email')
                    <div class="invalid-feedback-custom"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-lock"></i>
                    <input id="password" type="password" name="password"
                        class="form-control" placeholder="Create a strong password"
                        required autocomplete="new-password"
                        oninput="checkStrength(this.value)"
                        style="padding-right: 38px;">
                    <i class="bi bi-eye toggle-pw" onclick="togglePw('password', 'eye1');" id="eye1"></i>
                </div>
                <!-- Strength bars -->
                <div class="password-strength" id="strengthBars">
                    <div class="strength-bar" id="bar1"></div>
                    <div class="strength-bar" id="bar2"></div>
                    <div class="strength-bar" id="bar3"></div>
                    <div class="strength-bar" id="bar4"></div>
                </div>
                @error('password')
                    <div class="invalid-feedback-custom"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-0">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-lock-fill"></i>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="form-control" placeholder="Re-enter your password"
                        required autocomplete="new-password"
                        style="padding-right: 38px;">
                    <i class="bi bi-eye toggle-pw" onclick="togglePw('password_confirmation', 'eye2');" id="eye2"></i>
                </div>
                @error('password_confirmation')
                    <div class="invalid-feedback-custom"><i class="bi bi-x-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-register">
                <i class="bi bi-person-check"></i>
                Create Staff Account
            </button>
        </form>

        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Sign in here</a>
        </div>
    </div>

    <div class="login-footer">
        © 2026 InquiSmart · NAN Cellphone Shop · All rights reserved
    </div>
</div>

<script>
function togglePw(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash toggle-pw';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye toggle-pw';
    }
}

function checkStrength(val) {
    const bars  = ['bar1','bar2','bar3','bar4'];
    let score = 0;
    if (val.length >= 8)               score++;
    if (/[A-Z]/.test(val))             score++;
    if (/[0-9]/.test(val))             score++;
    if (/[^A-Za-z0-9]/.test(val))      score++;

    const colors = ['', 'weak', 'weak', 'medium', 'strong'];
    bars.forEach((id, i) => {
        const el = document.getElementById(id);
        el.className = 'strength-bar';
        if (i < score) el.classList.add(colors[score]);
    });
}
</script>

</body>
</html>