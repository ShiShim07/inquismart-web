<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InquiBot — Sign In</title>
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
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -200px; left: -200px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(0,229,196,0.12) 0%, transparent 65%);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: absolute;
            bottom: -150px; right: -150px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(59,91,246,0.15) 0%, transparent 65%);
            pointer-events: none;
        }

        .card {
            background: #fff;
            border-radius: 24px;
            padding: 44px 40px;
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
            width: 50px; height: 50px;
            background: #0F2A9E;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px;
        }
        .logo i { color: #00E5C4; font-size: 22px; }

        h1 {
            font-family: 'Syne', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: #0D1B3E;
            margin-bottom: 3px;
        }
        .sub {
            font-size: 13px;
            color: #9BA5C0;
            margin-bottom: 30px;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #5A6485;
            letter-spacing: 0.3px;
            margin-bottom: 6px;
        }

        .field {
            position: relative;
            margin-bottom: 16px;
        }
        .field .f-icon {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            color: #C7D2FE;
            font-size: 15px;
            pointer-events: none;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 11px 13px 11px 38px;
            border: 1.5px solid #E4E8F2;
            border-radius: 10px;
            font-size: 14px;
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
            right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: #C7D2FE; cursor: pointer; padding: 4px;
            font-size: 15px; line-height: 1;
            transition: color 0.15s;
        }
        .eye-btn:hover { color: #1A3FC4; }

        .btn-signin {
            width: 100%;
            padding: 12px;
            background: #0F2A9E;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            margin-top: 8px;
            letter-spacing: 0.2px;
        }
        .btn-signin:hover {
            background: #1A3FC4;
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(26,63,196,0.3);
        }

        .btn-register {
            width: 100%;
            padding: 11px;
            background: transparent;
            color: #1A3FC4;
            border: 1.5px solid #C7D2FE;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.18s;
            margin-top: 10px;
            text-align: center;
            display: block;
            text-decoration: none;
        }
        .btn-register:hover {
            background: #EEF2FF;
            border-color: #1A3FC4;
            color: #1A3FC4;
            text-decoration: none;
        }

        .error-box {
            background: #FFF0F2;
            border: 1px solid #FFD0D8;
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 13px;
            color: #C0123A;
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }

        .divider {
            display: flex; align-items: center; gap: 10px;
            margin: 22px 0 0;
        }
        .divider hr { flex: 1; border: none; border-top: 1px solid #F0F2F8; }
        .divider span { font-size: 12px; color: #C7D2FE; }
    </style>
</head>
<body>
<div class="card">

    <div class="logo"><i class="bi bi-headset"></i></div>
    <h1>Welcome back</h1>
    <p class="sub">InquiBot · NaN Cellphone Shop</p>

    @if($errors->any())
    <div class="error-box">
        <i class="bi bi-exclamation-circle-fill"></i>
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label>Email address</label>
        <div class="field">
            <i class="bi bi-envelope f-icon"></i>
            <input type="email" name="email" placeholder="admin@nancellphone.com"
                   value="{{ old('email') }}" required autofocus>
        </div>

        <label>Password</label>
        <div class="field">
            <i class="bi bi-lock f-icon"></i>
            <input type="password" name="password" id="pw"
                   placeholder="Enter your password" required style="padding-right:42px;">
            <button type="button" class="eye-btn" onclick="togglePw()">
                <i class="bi bi-eye-slash" id="eyeIcon"></i>
            </button>
        </div>

        <button type="submit" class="btn-signin">Sign In</button>
    </form>

    <div class="divider"><hr><span>or</span><hr></div>
    <a href="{{ route('register') }}" class="btn-register">Create an account</a>

</div>

<script>
function togglePw() {
    const pw = document.getElementById('pw');
    const ic = document.getElementById('eyeIcon');
    pw.type = pw.type === 'password' ? 'text' : 'password';
    ic.className = pw.type === 'password' ? 'bi bi-eye-slash' : 'bi bi-eye';
}
</script>
</body>
</html>
