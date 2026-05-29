<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InquiSmart — Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, sans-serif;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 44px 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 32px rgba(0,0,0,0.08);
        }

        .logo {
            width: 52px; height: 52px;
            background: #1565C0;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
        }
        .logo i { color: white; font-size: 24px; }

        h1 {
            font-size: 22px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }

        .sub {
            font-size: 13.5px;
            color: #94A3B8;
            margin-bottom: 32px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .field {
            position: relative;
            margin-bottom: 18px;
        }

        .field i.icon {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: #CBD5E1;
            font-size: 16px;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 11px 13px 11px 38px;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            font-size: 14px;
            color: #0F172A;
            background: #F8FAFC;
            outline: none;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus {
            border-color: #1565C0;
            background: white;
            box-shadow: 0 0 0 3px rgba(21,101,192,0.08);
        }

        input::placeholder { color: #CBD5E1; }

        .eye-btn {
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: #CBD5E1; cursor: pointer; padding: 0;
            font-size: 16px;
        }
        .eye-btn:hover { color: #1565C0; }

        .btn-primary {
            width: 100%;
            padding: 12px;
            background: #1565C0;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14.5px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.2s, transform 0.15s;
            margin-top: 4px;
        }
        .btn-primary:hover {
            background: #0D47A1;
            transform: translateY(-1px);
        }

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
            font-size: 13.5px;
            color: #94A3B8;
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: #1565C0; }

        .error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: #DC2626;
            margin-bottom: 18px;
            display: flex; align-items: center; gap: 8px;
        }
    </style>
</head>
<body>
<div class="card">

    <div class="logo"><i class="bi bi-headset"></i></div>
    <h1>Create account</h1>
    <p class="sub">InquiSmart — NaN Cellphone Shop</p>

    @if($errors->any())
    <div class="error">
        <i class="bi bi-exclamation-circle-fill"></i>
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label>Full Name</label>
        <div class="field">
            <i class="bi bi-person icon"></i>
            <input type="text" name="name" placeholder="Enter your full name" value="{{ old('name') }}" required autofocus>
        </div>

        <label>Email Address</label>
        <div class="field">
            <i class="bi bi-envelope icon"></i>
            <input type="email" name="email" placeholder="you@email.com" value="{{ old('email') }}" required>
        </div>

        <label>Password</label>
        <div class="field">
            <i class="bi bi-lock icon"></i>
            <input type="password" name="password" id="pw1" placeholder="Min. 8 characters" required style="padding-right:38px;">
            <button type="button" class="eye-btn" onclick="togglePw('pw1','eye1')">
                <i class="bi bi-eye-slash" id="eye1"></i>
            </button>
        </div>

        <label>Confirm Password</label>
        <div class="field">
            <i class="bi bi-lock-fill icon"></i>
            <input type="password" name="password_confirmation" id="pw2" placeholder="Re-enter password" required style="padding-right:38px;">
            <button type="button" class="eye-btn" onclick="togglePw('pw2','eye2')">
                <i class="bi bi-eye-slash" id="eye2"></i>
            </button>
        </div>

        <button type="submit" class="btn-primary">Create Account</button>
    </form>

    <a href="{{ route('login') }}" class="back-link">
        <i class="bi bi-arrow-left"></i> Back to Sign In
    </a>

</div>

<script>
function togglePw(id, iconId) {
    const pw = document.getElementById(id);
    const ic = document.getElementById(iconId);
    pw.type = pw.type === 'password' ? 'text' : 'password';
    ic.className = pw.type === 'password' ? 'bi bi-eye-slash' : 'bi bi-eye';
}
</script>
</body>
</html>