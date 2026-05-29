<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InquiSmart — Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0D47A1 0%, #1565C0 50%, #1976D2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
            top: -150px; right: -150px;
        }
        body::after {
            content: '';
            position: absolute;
            width: 350px; height: 350px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
            bottom: -100px; left: -100px;
        }

        .register-wrapper {
            width: 100%;
            max-width: 480px;
            background: #F8FAFC;
            border-radius: 24px;
            padding: 44px 44px;
            box-shadow: 0 32px 80px rgba(0,0,0,0.35);
            position: relative;
            z-index: 1;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-logo {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #1565C0, #1976D2);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 8px 24px rgba(21,101,192,0.3);
        }
        .brand-logo i { font-size: 28px; color: white; }

        .form-title {
            font-size: 22px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .form-subtitle {
            font-size: 14px;
            color: #64748B;
        }

        .form-label-custom {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            display: block;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 16px;
        }

        .input-icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 17px;
            z-index: 2;
        }

        .form-control-custom {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            font-size: 14px;
            color: #0F172A;
            background: white;
            transition: all 0.2s;
            outline: none;
            font-family: inherit;
        }

        .form-control-custom:focus {
            border-color: #1565C0;
            box-shadow: 0 0 0 3px rgba(21,101,192,0.1);
        }

        .form-control-custom::placeholder { color: #CBD5E1; }

        .password-toggle {
            position: absolute;
            right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: #94A3B8; cursor: pointer;
            font-size: 17px; padding: 0; z-index: 2;
        }
        .password-toggle:hover { color: #1565C0; }

        .btn-register {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #1565C0, #1976D2);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
            font-family: inherit;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-register:hover {
            background: linear-gradient(135deg, #0D47A1, #1565C0);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(21,101,192,0.35);
        }

        .error-msg {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #DC2626;
            margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13.5px;
            color: #64748B;
        }

        .login-link a {
            color: #1565C0;
            font-weight: 600;
            text-decoration: none;
        }
        .login-link a:hover { text-decoration: underline; }

        .field-hint {
            font-size: 11.5px;
            color: #94A3B8;
            margin-top: 4px;
        }

        @media (max-width: 520px) {
            .register-wrapper { padding: 32px 24px; }
        }
    </style>
</head>
<body>

<div class="register-wrapper">
    <div class="brand-header">
        <div class="brand-logo">
            <i class="bi bi-headset"></i>
        </div>
        <div class="form-title">Create account</div>
        <div class="form-subtitle">Join InquiSmart — NaN Cellphone Shop</div>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
    <div class="error-msg">
        <i class="bi bi-exclamation-circle-fill"></i>
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label class="form-label-custom">Full Name</label>
        <div class="input-group-custom">
            <i class="bi bi-person input-icon"></i>
            <input
                type="text"
                name="name"
                class="form-control-custom"
                placeholder="Enter your full name"
                value="{{ old('name') }}"
                required autofocus
            >
        </div>

        <label class="form-label-custom">Email Address</label>
        <div class="input-group-custom">
            <i class="bi bi-envelope input-icon"></i>
            <input
                type="email"
                name="email"
                class="form-control-custom"
                placeholder="you@nancellphone.com"
                value="{{ old('email') }}"
                required
            >
        </div>

        <label class="form-label-custom">Password</label>
        <div class="input-group-custom" style="margin-bottom:4px;">
            <i class="bi bi-lock input-icon"></i>
            <input
                type="password"
                name="password"
                id="passwordInput"
                class="form-control-custom"
                placeholder="Min. 8 characters"
                required
                style="padding-right:42px;"
            >
            <button type="button" class="password-toggle" onclick="togglePassword('passwordInput','eyeIcon1')">
                <i class="bi bi-eye-slash" id="eyeIcon1"></i>
            </button>
        </div>
        <div class="field-hint mb-3">At least 8 characters</div>

        <label class="form-label-custom">Confirm Password</label>
        <div class="input-group-custom">
            <i class="bi bi-lock-fill input-icon"></i>
            <input
                type="password"
                name="password_confirmation"
                id="passwordConfirm"
                class="form-control-custom"
                placeholder="Re-enter your password"
                required
                style="padding-right:42px;"
            >
            <button type="button" class="password-toggle" onclick="togglePassword('passwordConfirm','eyeIcon2')">
                <i class="bi bi-eye-slash" id="eyeIcon2"></i>
            </button>
        </div>

        <button type="submit" class="btn-register">
            <i class="bi bi-person-plus"></i>
            Create Account
        </button>
    </form>

    <div class="login-link">
        Already have an account?
        <a href="{{ route('login') }}">Sign in here</a>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye-slash';
    }
}
</script>
</body>
</html>