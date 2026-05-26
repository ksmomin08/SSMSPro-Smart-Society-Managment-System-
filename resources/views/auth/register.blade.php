<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Society - Smart Society SaaS Console</title>
    
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
            padding: 40px 20px;
            overflow-y: auto;
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

        .register-container {
            width: 100%;
            max-width: 680px;
            z-index: 10;
        }

        .glass-card {
            background: var(--bg-card);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--border-color);
            border-radius: 28px;
            box-shadow: var(--card-shadow);
            padding: 40px;
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

        /* Step Progress indicators */
        .step-indicators {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            position: relative;
        }

        .step-indicators::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 2px;
            background: rgba(255,255,255,0.08);
            z-index: 1;
            transform: translateY(-50%);
        }

        .step-progress-line {
            position: absolute;
            top: 50%;
            left: 0;
            width: 0%;
            height: 2px;
            background: var(--accent-gradient);
            z-index: 2;
            transform: translateY(-50%);
            transition: width 0.4s ease;
        }

        .step-indicator {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #111827;
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--text-muted);
            z-index: 3;
            transition: all 0.3s ease;
        }

        .step-indicator.active {
            background: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
            box-shadow: 0 0 14px var(--accent-glow);
        }

        .step-indicator.completed {
            background: var(--neon-green);
            border-color: var(--neon-green);
            color: white;
        }

        /* Step Forms switcher */
        .step-form {
            display: none;
            animation: fadeIn 0.4s ease forwards;
        }

        .step-form.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
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

        /* Pricing Card Grid */
        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .pricing-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px 16px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .pricing-card:hover {
            background: rgba(255, 255, 255, 0.04);
            border-color: #8b5cf6;
            transform: translateY(-4px);
        }

        .pricing-card.selected {
            background: rgba(79, 70, 229, 0.07);
            border-color: #4f46e5;
            box-shadow: 0 10px 25px -10px var(--accent-glow);
        }

        .pricing-card .plan-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: var(--text-muted);
            transition: all 0.3s;
        }

        .pricing-card.selected .plan-icon {
            background: var(--accent-gradient);
            color: white;
            box-shadow: 0 0 12px var(--accent-color);
        }

        .pricing-card .plan-price {
            font-size: 1.6rem;
            font-weight: 800;
            color: white;
            margin: 10px 0;
        }

        .pricing-card .plan-price span {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        .pricing-card .plan-name {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--text-main);
        }

        .pricing-card .plan-desc {
            font-size: 0.78rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        .pricing-card .popular-badge {
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--accent-gradient);
            color: white;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 12px;
            box-shadow: 0 4px 10px var(--accent-glow);
            letter-spacing: 0.5px;
        }

        .btn-action-nav {
            background: var(--accent-gradient);
            color: #ffffff;
            border: none;
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 700;
            box-shadow: 0 8px 20px var(--accent-glow);
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-action-nav:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px var(--accent-glow);
            opacity: 0.95;
        }

        .btn-secondary-nav {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 600;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-secondary-nav:hover {
            background: rgba(255,255,255,0.06);
            border-color: #8b5cf6;
        }

        .success-checkmark {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(16,185,129,0.08);
            border: 2px solid var(--neon-green);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: var(--neon-green);
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(16,185,129,0.4); }
            70% { box-shadow: 0 0 0 15px rgba(16,185,129,0); }
            100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); }
        }
    </style>
</head>
<body>

<!-- Glowing blur balls -->
<div class="glowing-blob blob-1"></div>
<div class="glowing-blob blob-2"></div>
<div class="glowing-blob blob-3"></div>

<div class="register-container">
    <div class="glass-card">
        <a href="/login" class="brand-logo mb-1">
            <span class="dot"></span>
            <span>Smart Society</span>
        </a>
        <p class="text-center text-muted mb-4" style="font-size: 0.9rem;">SaaS Tenant Onboarding Panel</p>

        <!-- Progress Track HUD -->
        <div class="step-indicators">
            <div class="step-progress-line" id="stepLine"></div>
            <div class="step-indicator active" id="ind-1">1</div>
            <div class="step-indicator" id="ind-2">2</div>
            <div class="step-indicator" id="ind-3">3</div>
        </div>

        <form id="onboardingForm" onsubmit="submitOnboarding(event)">
            <!-- STEP 1: CHOOSE PLAN -->
            <div class="step-form active" id="step-1">
                <h5 class="mb-2 text-center text-white">Select a subscription framework</h5>
                <p class="text-muted text-center mb-4" style="font-size: 0.88rem;">Choose the perfect plan built for your society housing scope.</p>
                
                <div class="pricing-grid">
                    <!-- Card 1 -->
                    <div class="pricing-card selected" onclick="selectPlan('Basic', 99, this)">
                        <div class="plan-icon"><i data-lucide="home"></i></div>
                        <div class="plan-name">Basic / Wing</div>
                        <div class="plan-price">$99<span>/mo</span></div>
                        <div class="plan-desc">For smaller societies up to 2 wings, security controls & visitorlogs.</div>
                    </div>
                    <!-- Card 2 -->
                    <div class="pricing-card" onclick="selectPlan('Premium', 199, this)">
                        <span class="popular-badge">POPULAR</span>
                        <div class="plan-icon"><i data-lucide="building"></i></div>
                        <div class="plan-name">Premium SaaS</div>
                        <div class="plan-price">$199<span>/mo</span></div>
                        <div class="plan-desc">Up to 8 wings. Full complaints desk, AI categorization, bill simulator & Razorpay integration.</div>
                    </div>
                    <!-- Card 3 -->
                    <div class="pricing-card" onclick="selectPlan('Elite', 399, this)">
                        <div class="plan-icon"><i data-lucide="gem"></i></div>
                        <div class="plan-name">Elite Estate</div>
                        <div class="plan-price">$399<span>/mo</span></div>
                        <div class="plan-desc">Unlimited capacity, multiple societies admin panel, premium analytics reports.</div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="/login" class="btn-secondary-nav"><i data-lucide="arrow-left"></i> Back to Login</a>
                    <button type="button" class="btn-action-nav" onclick="changeStep(2)">Proceed Next <i data-lucide="arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 2: SOCIETY DETAILS -->
            <div class="step-form" id="step-2">
                <h5 class="mb-2 text-center text-white">Society Infrastructure Profile</h5>
                <p class="text-muted text-center mb-4" style="font-size: 0.88rem;">Fill in configuration details to generate isolated databases.</p>

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">SOCIETY NAME</label>
                        <input type="text" id="soc_name" class="form-control form-control-glass" placeholder="e.g. Royal Heights Co-op Housing" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">TOTAL WINGS / BLOCKS</label>
                        <input type="number" id="soc_wings" class="form-control form-control-glass" placeholder="e.g. 4" min="1" value="2" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">FLATS PER WING</label>
                        <input type="number" id="soc_flats" class="form-control form-control-glass" placeholder="e.g. 24" min="1" value="20" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">SOCIETY STREET ADDRESS</label>
                        <input type="text" id="soc_addr" class="form-control form-control-glass" placeholder="e.g. 21 Main Silicon Boulevard" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="button" class="btn-secondary-nav" onclick="changeStep(1)"><i data-lucide="arrow-left"></i> Previous</button>
                    <button type="button" class="btn-action-nav" onclick="changeStep(3)">Step 3 Credentials <i data-lucide="arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 3: ADMIN CREDENTIALS -->
            <div class="step-form" id="step-3">
                <h5 class="mb-2 text-center text-white">Generate Chief Administrator Account</h5>
                <p class="text-muted text-center mb-4" style="font-size: 0.88rem;">Create credentials for your Society Admin master dashboard access.</p>

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">CHIEF ADMINISTRATOR NAME</label>
                        <input type="text" id="admin_name" class="form-control form-control-glass" placeholder="e.g. Charles Xavier" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">PRIMARY ADMIN EMAIL</label>
                        <input type="email" id="admin_email" class="form-control form-control-glass" placeholder="admin@societyname.com" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">PASSWORD</label>
                        <input type="password" id="admin_pass" class="form-control form-control-glass" placeholder="••••••••" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted font-weight-bold" style="font-size:0.8rem;">CONFIRM PASSWORD</label>
                        <input type="password" id="admin_pass_conf" class="form-control form-control-glass" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="button" class="btn-secondary-nav" onclick="changeStep(2)"><i data-lucide="arrow-left"></i> Previous</button>
                    <button type="submit" class="btn-action-nav" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 8px 20px rgba(16,185,129,0.25);">
                        <i data-lucide="sparkles"></i> Deploy Society
                    </button>
                </div>
            </div>
        </form>

        <!-- SUCCESS ANIMATION INTERACTION -->
        <div class="step-form text-center py-4" id="step-success">
            <div class="success-checkmark">
                <i data-lucide="check" style="width: 44px; height: 44px; stroke-width: 3px;"></i>
            </div>
            <h4 class="text-white mb-2">Deploying your isolated SaaS ecosystem!</h4>
            <p class="text-muted px-4" style="font-size: 0.9rem;">
                Our setup engines are initializing structures for <strong class="text-main" id="finalSocName">Royal Heights</strong>. Isolated database tables created, default admin account provisioned under basic plan.
            </p>
            <div class="p-3.5 rounded-4 border border-secondary border-opacity-10 text-start font-size-sm mb-4 mx-auto" style="max-width: 420px; background: rgba(255,255,255,0.01);">
                <div class="mb-1 text-muted">🏡 Society Code: <strong class="text-white" id="finalCode">SOC-RH4491</strong></div>
                <div class="mb-1 text-muted">👑 Admin Account: <strong class="text-white" id="finalEmail">admin@society.com</strong></div>
                <div class="text-muted">⚡ Package: <strong class="text-success" id="finalPlan">Basic / Wing</strong></div>
            </div>
            <a href="/login" class="btn-action-nav"><i data-lucide="log-in"></i> Return to Login console</a>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    let selectedPlanName = 'Basic';
    let selectedPlanPrice = 99;

    document.addEventListener("DOMContentLoaded", function() {
        lucide.createIcons();
        updateHUD();
    });

    function selectPlan(name, price, element) {
        selectedPlanName = name;
        selectedPlanPrice = price;

        // Reset cards
        document.querySelectorAll('.pricing-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Activate clicked card
        element.classList.add('selected');
    }

    function changeStep(step) {
        // Validation check for inputs
        if(step > currentStep) {
            const currentPane = document.getElementById('step-' + currentStep);
            const inputs = currentPane.querySelectorAll('input[required]');
            let isValid = true;
            inputs.forEach(input => {
                if(!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            if(!isValid) {
                alert("Please fill all required parameters to proceed.");
                return;
            }
        }

        currentStep = step;

        // Hide all steps
        document.querySelectorAll('.step-form').forEach(pane => {
            pane.classList.remove('active');
        });

        // Show targeted step
        document.getElementById('step-' + step).classList.add('active');

        updateHUD();
    }

    function updateHUD() {
        // Line calculations
        const percentage = ((currentStep - 1) / 2) * 100;
        document.getElementById('stepLine').style.width = percentage + '%';

        // Indicators triggers
        for(let i = 1; i <= 3; i++) {
            const ind = document.getElementById('ind-' + i);
            ind.className = 'step-indicator';
            if(i < currentStep) {
                ind.classList.add('completed');
                ind.innerHTML = '<i data-lucide="check" style="width: 16px; height: 16px;"></i>';
            } else if(i === currentStep) {
                ind.classList.add('active');
                ind.innerText = i;
            } else {
                ind.innerText = i;
            }
        }
        lucide.createIcons();
    }

    function submitOnboarding(e) {
        e.preventDefault();

        // Admin passwords match check
        const pass = document.getElementById('admin_pass').value;
        const passConf = document.getElementById('admin_pass_conf').value;
        if(pass !== passConf) {
            alert("Passwords do not match. Please verify.");
            return;
        }

        // Simulating society database creation
        const socName = document.getElementById('soc_name').value;
        const adminEmail = document.getElementById('admin_email').value;
        const generatedCode = 'SOC-' + socName.substring(0, 3).toUpperCase() + Math.floor(1000 + Math.random() * 9000);

        // Hide form fields
        document.getElementById('onboardingForm').style.display = 'none';
        document.getElementById('stepLine').parentElement.style.display = 'none';

        // Bind final results
        document.getElementById('finalSocName').innerText = socName;
        document.getElementById('finalCode').innerText = generatedCode;
        document.getElementById('finalEmail').innerText = adminEmail;
        document.getElementById('finalPlan').innerText = selectedPlanName + ' SaaS (Monthly: $' + selectedPlanPrice + ')';

        // Show Success card
        document.getElementById('step-success').classList.add('active');
        lucide.createIcons();
    }
</script>

</body>
</html>
