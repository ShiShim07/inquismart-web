<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InquiBot — Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: #0D1B3E;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'DM Sans', system-ui, sans-serif;
            padding: 24px 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -200px; right: -200px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(0,229,196,0.12) 0%, transparent 65%);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: absolute;
            bottom: -150px; left: -150px;
            width: 450px; height: 450px;
            background: radial-gradient(circle, rgba(59,91,246,0.15) 0%, transparent 65%);
            pointer-events: none;
        }

        .card {
            background: #fff;
            border-radius: 24px;
            padding: 40px 36px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 80px rgba(0,0,0,0.35);
            position: relative;
            z-index: 1;
            animation: slideUp 0.4s ease both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logo {
            width: 46px; height: 46px;
            background: #0F2A9E;
            border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px;
        }
        .logo i { color: #00E5C4; font-size: 21px; }

        h1 {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #0D1B3E;
            margin-bottom: 3px;
        }
        .sub {
            font-size: 12.5px;
            color: #9BA5C0;
            margin-bottom: 26px;
        }

        label {
            display: block;
            font-size: 11.5px;
            font-weight: 700;
            color: #5A6485;
            letter-spacing: 0.3px;
            margin-bottom: 5px;
        }

        .field {
            position: relative;
            margin-bottom: 14px;
        }
        .field .f-icon {
            position: absolute;
            left: 12px; top: 50%;
            transform: translateY(-50%);
            color: #C7D2FE;
            font-size: 14px;
            pointer-events: none;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 10px 12px 10px 36px;
            border: 1.5px solid #E4E8F2;
            border-radius: 10px;
            font-size: 13.5px;
            color: #0D1B3E;
            background: #F8FAFF;
            outline: none;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        input:focus {
            border-color: #1A3FC4;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26,63,196,0.1);
        }
        input::placeholder { color: #C7D2FE; }

        .eye-btn {
            position: absolute;
            right: 11px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: #C7D2FE; cursor: pointer; padding: 4px;
            font-size: 14px; line-height: 1;
            transition: color 0.15s;
        }
        .eye-btn:hover { color: #1A3FC4; }

        .btn-submit {
            width: 100%;
            padding: 11px;
            background: #0F2A9E;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            margin-top: 6px;
        }
        .btn-submit:hover {
            background: #1A3FC4;
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(26,63,196,0.3);
        }

        .btn-back {
            display: flex; align-items: center; justify-content: center; gap: 5px;
            width: 100%;
            padding: 10px;
            background: transparent;
            color: #1A3FC4;
            border: 1.5px solid #C7D2FE;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.18s;
            margin-top: 10px;
            text-decoration: none;
        }
        .btn-back:hover {
            background: #EEF2FF;
            border-color: #1A3FC4;
            color: #1A3FC4;
            text-decoration: none;
        }

        .error-box {
            background: #FFF0F2;
            border: 1px solid #FFD0D8;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 12.5px;
            color: #C0123A;
            margin-bottom: 18px;
            display: flex; align-items: center; gap: 8px;
        }
    </style>
</head>
<body>
<div class="card">

    <div class="logo"><i class="bi bi-headset"></i></div>
    <h1>Create account</h1>
    <p class="sub">InquiBot · NaN Cellphone Shop</p>

    @if($errors->any())
    <div class="error-box">
        <i class="bi bi-exclamation-circle-fill"></i>
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label>Full Name</label>
        <div class="field">
            <i class="bi bi-person f-icon"></i>
            <input type="text" name="name" placeholder="Enter your full name"
                   value="{{ old('name') }}" required autofocus>
        </div>

        <label>Email Address</label>
        <div class="field">
            <i class="bi bi-envelope f-icon"></i>
            <input type="email" name="email" placeholder="you@email.com"
                   value="{{ old('email') }}" required>
        </div>

        <label>Password</label>
        <div class="field">
            <i class="bi bi-lock f-icon"></i>
            <input type="password" name="password" id="pw1"
                   placeholder="Min. 8 characters" required style="padding-right:38px;">
            <button type="button" class="eye-btn" onclick="togglePw('pw1','eye1')">
                <i class="bi bi-eye-slash" id="eye1"></i>
            </button>
        </div>

        <label>Confirm Password</label>
        <div class="field">
            <i class="bi bi-lock-fill f-icon"></i>
            <input type="password" name="password_confirmation" id="pw2"
                   placeholder="Re-enter your password" required style="padding-right:38px;">
            <button type="button" class="eye-btn" onclick="togglePw('pw2','eye2')">
                <i class="bi bi-eye-slash" id="eye2"></i>
            </button>
        </div>

        <button type="submit" class="btn-submit">Create Account</button>
    </form>

    <a href="{{ route('login') }}" class="btn-back">
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
