<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InquiSmart — Sign In</title>
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

        /* Background decorative circles */
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

        .login-wrapper {
            display: flex;
            width: 100%;
            max-width: 900px;
            min-height: 560px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.35);
            position: relative;
            z-index: 1;
        }

        /* Left Panel */
        .left-panel {
            flex: 1;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-right: 1px solid rgba(255,255,255,0.1);
        }

        .brand-logo {
            width: 64px; height: 64px;
            background: white;
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }
        .brand-logo i { font-size: 30px; color: #1565C0; }

        .brand-title {
            font-size: 28px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .brand-sub {
            font-size: 14px;
            color: rgba(255,255,255,0.65);
            margin-bottom: 40px;
            line-height: 1.5;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .feature-icon {
            width: 40px; height: 40px;
            background: rgba(255,255,255,0.12);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .feature-icon i { color: white; font-size: 18px; }

        .feature-text { color: rgba(255,255,255,0.8); font-size: 13.5px; line-height: 1.4; }
        .feature-text strong { color: white; display: block; font-size: 14px; margin-bottom: 2px; }

        /* Right Panel */
        .right-panel {
            flex: 1;
            background: #F8FAFC;
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

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
            margin-bottom: 32px;
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
            margin-bottom: 18px;
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
            font-size: 17px; padding: 0;
            z-index: 2;
        }
        .password-toggle:hover { color: #1565C0; }

        .btn-login {
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
        .btn-login:hover {
            background: linear-gradient(135deg, #0D47A1, #1565C0);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(21,101,192,0.35);
        }
        .btn-login:active { transform: translateY(0); }

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

        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 20px 0;
        }
        .divider hr { flex: 1; border: none; border-top: 1px solid #E2E8F0; }
        .divider span { font-size: 12px; color: #94A3B8; white-space: nowrap; }

        .badge-role {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        @media (max-width: 640px) {
            .left-panel { display: none; }
            .right-panel { padding: 36px 28px; }
            .login-wrapper { max-width: 420px; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <!-- Left Panel -->
    <div class="left-panel">
        <div class="brand-logo">
            <i class="bi bi-headset"></i>
        </div>
        <div class="brand-title">InquiSmart</div>
        <div class="brand-sub">Customer Helpdesk System<br>NaN Cellphone Shop — Greenhills</div>

        <div class="feature-item">
            <div class="feature-icon"><i class="bi bi-robot"></i></div>
            <div class="feature-text">
                <strong>AI Sentiment Analysis</strong>
                Auto-classifies tickets as Positive, Negative, or Neutral
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="bi bi-chat-dots"></i></div>
            <div class="feature-text">
                <strong>Chatbot Engine</strong>
                Rule-based NLP assistant for customer inquiries
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="bi bi-bar-chart-line"></i></div>
            <div class="feature-text">
                <strong>Service Analytics</strong>
                Predictive inquiry recommendations & trends
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="bi bi-ticket-perforated"></i></div>
            <div class="feature-text">
                <strong>Ticket Management</strong>
                Real-time tracking and staff response system
            </div>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
        <div class="form-title">Welcome back 👋</div>
        <div class="form-subtitle">Sign in to your InquiSmart account</div>

        {{-- Error Messages --}}
        @if($errors->any())
        <div class="error-msg">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first() }}
        </div>
        @endif

        @if(session('status'))
        <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;padding:10px 14px;font-size:13px;color:#166534;margin-bottom:16px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label class="form-label-custom">Email address</label>
            <div class="input-group-custom">
                <i class="bi bi-envelope input-icon"></i>
                <input
                    type="email"
                    name="email"
                    class="form-control-custom"
                    placeholder="admin@nancellphone.com"
                    value="{{ old('email') }}"
                    required autofocus
                >
            </div>

            <label class="form-label-custom">Password</label>
            <div class="input-group-custom" style="margin-bottom:8px;">
                <i class="bi bi-lock input-icon"></i>
                <input
                    type="password"
                    name="password"
                    id="passwordInput"
                    class="form-control-custom"
                    placeholder="Enter your password"
                    required
                    style="padding-right:42px;"
                >
                <button type="button" class="password-toggle" onclick="togglePassword()">
                    <i class="bi bi-eye-slash" id="eyeIcon"></i>
                </button>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="remember" style="accent-color:#1565C0;width:14px;height:14px;">
                    <span style="font-size:13px;color:#64748B;">Remember me</span>
                </label>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i>
                Sign In
            </button>
        </form>

        <div class="divider">
            <hr><span>Staff access only</span><hr>
        </div>

        <div style="background:#F1F5F9;border-radius:10px;padding:14px 16px;">
            <div style="font-size:12px;color:#64748B;margin-bottom:8px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Demo Accounts</div>
            <div style="display:flex;flex-direction:column;gap:6px;">
                <div style="font-size:12.5px;color:#374151;">
                    <span class="badge-role" style="background:#DBEAFE;color:#1E40AF;">Admin</span>
                    <span style="margin-left:8px;">admin@nancellphone.com / admin123</span>
                </div>
                <div style="font-size:12.5px;color:#374151;">
                    <span class="badge-role" style="background:#D1FAE5;color:#065F46;">Staff</span>
                    <span style="margin-left:8px;">staff@nancellphone.com / staff123</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
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