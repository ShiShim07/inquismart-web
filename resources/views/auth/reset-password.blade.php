<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InquiSmart — Set New Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { min-height:100vh;background:#0D1B3E;display:flex;align-items:center;justify-content:center;font-family:'DM Sans',sans-serif;padding:24px;position:relative;overflow:hidden; }
        body::before { content:'';position:absolute;bottom:-180px;right:-180px;width:480px;height:480px;background:radial-gradient(circle,rgba(59,91,246,0.15) 0%,transparent 65%);pointer-events:none; }
        .card { background:#fff;border-radius:24px;padding:40px 36px;width:100%;max-width:400px;box-shadow:0 20px 80px rgba(0,0,0,0.35);position:relative;z-index:1;animation:slideUp 0.4s ease both; }
        @keyframes slideUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        .logo { width:46px;height:46px;background:#0F2A9E;border-radius:13px;display:flex;align-items:center;justify-content:center;margin-bottom:18px; }
        .logo i { color:#00E5C4;font-size:21px; }
        h1 { font-family:'Syne',sans-serif;font-size:22px;font-weight:800;color:#0D1B3E;margin-bottom:3px; }
        .sub { font-size:12.5px;color:#9BA5C0;margin-bottom:26px; }
        label { display:block;font-size:11.5px;font-weight:700;color:#5A6485;letter-spacing:0.3px;margin-bottom:5px; }
        .field { position:relative;margin-bottom:14px; }
        .field .f-icon { position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#C7D2FE;font-size:14px;pointer-events:none; }
        input[type="email"], input[type="password"] { width:100%;padding:10px 12px 10px 36px;border:1.5px solid #E4E8F2;border-radius:10px;font-size:13.5px;color:#0D1B3E;background:#F8FAFF;outline:none;font-family:'DM Sans',sans-serif;transition:all 0.2s; }
        input:focus { border-color:#1A3FC4;background:#fff;box-shadow:0 0 0 3px rgba(26,63,196,0.1); }
        input::placeholder { color:#C7D2FE; }
        .eye-btn { position:absolute;right:11px;top:50%;transform:translateY(-50%);background:none;border:none;color:#C7D2FE;cursor:pointer;padding:4px;font-size:14px;line-height:1;transition:color 0.15s; }
        .eye-btn:hover { color:#1A3FC4; }
        .btn-submit { width:100%;padding:11px;background:#0F2A9E;color:white;border:none;border-radius:12px;font-size:14px;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all 0.2s;margin-top:6px; }
        .btn-submit:hover { background:#1A3FC4;transform:translateY(-1px);box-shadow:0 6px 24px rgba(26,63,196,0.3); }
        .error-box { background:#FFF0F2;border:1px solid #FFD0D8;border-radius:10px;padding:10px 14px;font-size:12.5px;color:#C0123A;margin-bottom:18px;display:flex;align-items:center;gap:8px; }
    </style>
</head>
<body>
<div class="card">
    <div class="logo"><i class="bi bi-key"></i></div>
    <h1>Set new password</h1>
    <p class="sub">Choose a strong password for your account.</p>

    @if($errors->any())
        <div class="error-box">
            <i class="bi bi-exclamation-circle-fill"></i> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <label>Email address</label>
        <div class="field">
            <i class="bi bi-envelope f-icon"></i>
            <input type="email" name="email" placeholder="admin@nancellphone.com"
                   value="{{ old('email', $request->email) }}" required>
        </div>

        <label>New Password</label>
        <div class="field">
            <i class="bi bi-lock f-icon"></i>
            <input type="password" name="password" id="pw1"
                   placeholder="Min. 8 characters" required style="padding-right:38px;" autocomplete="new-password">
            <button type="button" class="eye-btn" onclick="tp('pw1','e1')">
                <i class="bi bi-eye-slash" id="e1"></i>
            </button>
        </div>

        <label>Confirm New Password</label>
        <div class="field">
            <i class="bi bi-lock-fill f-icon"></i>
            <input type="password" name="password_confirmation" id="pw2"
                   placeholder="Re-enter new password" required style="padding-right:38px;" autocomplete="new-password">
            <button type="button" class="eye-btn" onclick="tp('pw2','e2')">
                <i class="bi bi-eye-slash" id="e2"></i>
            </button>
        </div>

        <button type="submit" class="btn-submit">Reset Password</button>
    </form>
</div>

<script>
function tp(id, iconId) {
    const pw = document.getElementById(id);
    const ic = document.getElementById(iconId);
    pw.type = pw.type === 'password' ? 'text' : 'password';
    ic.className = pw.type === 'password' ? 'bi bi-eye-slash' : 'bi bi-eye';
}
</script>
</body>
</html>
