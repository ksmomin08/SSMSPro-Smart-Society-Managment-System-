<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Smart Society SaaS Console</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        :root {
            --bg-primary: #090d16;
            --bg-card: rgba(17, 24, 39, 0.75);
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --accent-color: #4f46e5;
            --accent-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --accent-glow: rgba(79, 70, 229, 0.25);
            --border-color: rgba(75, 85, 99, 0.35);
            --glass-blur: blur(16px);
            --card-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            --neon-red: #ef4444;
            --neon-green: #10b981;
            --transition-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            color: var(--text-main);
            font-weight: 600;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow: hidden;
            position: relative;
        }

        /* Ambient drifting background blobs */
        .glowing-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.35;
            z-index: 0;
            pointer-events: none;
            animation: drift 20s infinite alternate ease-in-out;
        }

        .blob-1 {
            width: 450px;
            height: 450px;
            background: #4f46e5;
            top: -100px;
            left: -100px;
        }

        .blob-2 {
            width: 550px;
            height: 550px;
            background: #a855f7;
            bottom: -150px;
            right: -100px;
            animation-delay: -6s;
        }

        .blob-3 {
            width: 350px;
            height: 350px;
            background: #06b6d4;
            top: 40%;
            left: 40%;
            animation-delay: -12s;
        }

        @keyframes drift {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(80px, 50px) scale(1.15); }
            100% { transform: translate(-50px, 90px) scale(0.9); }
        }

        .forgot-container {
            width: 100%;
            max-width: 480px;
            z-index: 10;
        }

        .glass-card {
            background: var(--bg-card);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--border-color);
            border-radius: 28px;
            box-shadow: var(--card-shadow);
            padding: 44px;
            position: relative;
            overflow: hidden;
        }

        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--accent-gradient);
        }

        .brand-logo {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2.1rem;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 8px;
            text-decoration: none;
        }

        .brand-logo .dot {
            width: 16px;
            height: 16px;
            background: var(--accent-gradient);
            border-radius: 50%;
            box-shadow: 0 0 16px var(--accent-color);
        }

        .form-control-glass {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            color: #ffffff;
            padding: 14px 18px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control-glass:focus {
            background: rgba(255, 255, 255, 0.07);
            border-color: #8b5cf6;
            color: #ffffff;
            box-shadow: 0 0 0 4px var(--accent-glow);
            outline: none;
        }

        .form-control-glass::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .btn-accent {
            background: var(--accent-gradient);
            color: #ffffff;
            border: none;
            padding: 15px 24px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1.05rem;
            width: 100%;
            box-shadow: 0 8px 24px var(--accent-glow);
            transition: all 0.25s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-accent:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px var(--accent-glow);
            opacity: 0.95;
        }

        .success-display {
            display: none;
            animation: fadeIn 0.4s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .success-checkmark {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(16,185,129,0.08);
            border: 2px solid var(--neon-green);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: var(--neon-green);
        }
    </style>
</head>
<body>

<!-- Glowing blur balls -->
<div class="glowing-blob blob-1"></div>
<div class="glowing-blob blob-2"></div>
<div class="glowing-blob blob-3"></div>

<div class="forgot-container">
    <div class="glass-card">
        <a href="/login" class="brand-logo mb-1">
            <span class="dot"></span>
            <span>Smart Society</span>
        </a>
        <p class="text-center text-muted mb-4" style="font-size: 0.9rem;">Reset Account Password Panel</p>

        <!-- PASSWORD RESET FORM -->
        <form id="resetForm" onsubmit="triggerReset(event)">
            <p class="text-muted text-center mb-4" style="font-size: 0.88rem;">Enter your registered account email address. We will dispatch a recovery ticket verification link.</p>

            <div class="mb-4">
                <label class="form-label text-muted font-weight-bold" style="font-size: 0.8rem; letter-spacing: 0.5px;">ACCOUNT EMAIL ADDRESS</label>
                <input type="email" id="email" class="form-control form-control-glass" placeholder="name@society.com" required>
            </div>

            <button type="submit" class="btn btn-accent mb-3">
                <i data-lucide="mail" style="width: 18px; height: 18px;"></i>
                <span>Send Verification Ticket</span>
            </button>

            <div class="text-center mt-3">
                <a href="/login" class="text-muted text-decoration-none" style="font-size: 0.85rem; font-weight: 500; transition: color 0.2s;"><i data-lucide="arrow-left" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 4px;"></i> Return to Login console</a>
            </div>
        </form>

        <!-- SUCCESS SCREEN -->
        <div class="success-display text-center py-3" id="resetSuccess">
            <div class="success-checkmark">
                <i data-lucide="check" style="width: 36px; height: 36px; stroke-width: 3px;"></i>
            </div>
            <h5 class="text-white mb-2">Verification Ticket Dispatched!</h5>
            <p class="text-muted mb-4" style="font-size: 0.88rem;">
                A secure recovery pathway link has been generated and pushed to <strong class="text-white" id="successEmail">name@society.com</strong>. Make sure to check your spam/junk vaults if it doesn't arrive within 2 minutes.
            </p>
            <a href="/login" class="btn btn-accent"><i data-lucide="log-in" style="width: 18px; height: 18px;"></i> Log In Console</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        lucide.createIcons();
    });

    function triggerReset(e) {
        e.preventDefault();
        const emailVal = document.getElementById('email').value;

        // Hide form fields
        document.getElementById('resetForm').style.display = 'none';

        // Set email text
        document.getElementById('successEmail').innerText = emailVal;

        // Show Success card
        document.getElementById('resetSuccess').style.display = 'block';
        lucide.createIcons();
    }
</script>

</body>
</html>
