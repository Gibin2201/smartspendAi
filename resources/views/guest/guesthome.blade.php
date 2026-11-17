<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartSpend AI - Intelligent Expense Tracking</title>
    <!-- Updated CSS Asset Path -->
    <link rel="stylesheet" href="{{ asset('guest/assets/style.css') }}">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Floating Money Background Animation Container -->
    <div class="money-rain-container" id="moneyRain"></div>

    <!-- Navigation Bar -->
<nav class="navbar">
    <div class="nav-container">
        <a href="#home" class="logo"><i class="fa-solid fa-brain-circuit"></i> SmartSpend<span>AI</span></a>
        
        <!-- Everything that should collapse on mobile goes inside this wrapper -->
        <div class="nav-menu" id="navMenu">
            <ul class="nav-links">
                <li><a href="#home" class="active">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#ai-insights">AI</a></li>
                <li><a href="#contact">Contact</a></li>
                
            </ul>
            <div class="nav-buttons">
                <!-- <a href="#" class="btn btn-secondary">Login</a> -->
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            </div>
        </div>

        <!-- Three-bar hamburger button -->
        <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</nav>

    <!-- Hero Section -->
    <header id="home" class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <h1>Manage Your Money Smarter with <span class="text-gradient">AI</span></h1>
                <p>SmartSpend AI is an AI-powered expense tracking application that helps users manage income, expenses, monthly budgets, and financial reports while providing intelligent financial insights through a rule-based AI approach.</p>
                <div class="hero-cta">
                    <a href="#tracker" class="btn btn-primary btn-large">Get Started</a>
                    <a href="#about" class="btn btn-outline">Learn More</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80" alt="Financial Analytics Dashboard Dashboard">
            </div>
        </div>
    </header>

    <!-- About Section -->
    <section id="about" class="about-section" style="padding: 5rem 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2>About SmartSpend AI</h2>
            <p style="max-width: 800px; margin: 1rem auto 0; color: var(--text-light);">SmartSpend AI is a Laravel-based Expense Tracker developed using PHP and MySQL. It allows users to record income and expenses, monitor budgets, generate reports, and receive intelligent financial suggestions using Rule-Based Artificial Intelligence.</p>
        </div>
        <div class="features-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <div class="card" style="text-align: center; padding: 2rem;">
                <div style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 1rem;"><i class="fa-solid fa-wallet"></i></div>
                <h3>Income Management</h3>
            </div>
            <div class="card" style="text-align: center; padding: 2rem;">
                <div style="font-size: 2.5rem; color: var(--danger); margin-bottom: 1rem;"><i class="fa-solid fa-receipt"></i></div>
                <h3>Expense Tracking</h3>
            </div>
            <div class="card" style="text-align: center; padding: 2rem;">
                <div style="font-size: 2.5rem; color: var(--success); margin-bottom: 1rem;"><i class="fa-solid fa-calendar-check"></i></div>
                <h3>Budget Planning</h3>
            </div>
            <div class="card" style="text-align: center; padding: 2rem;">
                <div style="font-size: 2.5rem; color: var(--accent-ai); margin-bottom: 1rem;"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                <h3>AI Financial Insights</h3>
            </div>
        </div>
    </section>

    <!-- General Features Section -->
    <section id="features" class="features-section" style="padding: 5rem 2rem; background: var(--card-bg);">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 3rem;">
                <h2>Core Architecture Features</h2>
                <p style="color: var(--text-light); margin-top: 0.5rem;">Everything you need to orchestrate ledger integrity efficiently.</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div class="card" style="padding: 2rem;">
                    <i class="fa-solid fa-wallet" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>Income Management</h3>
                    <p style="color: var(--text-light); font-size: 0.9rem; margin-top: 0.5rem;">Log, arrange, and manage multi-channel monthly revenues seamlessly.</p>
                </div>
                <div class="card" style="padding: 2rem;">
                    <i class="fa-solid fa-receipt" style="font-size: 2rem; color: var(--danger); margin-bottom: 1rem;"></i>
                    <h3>Expense Tracking</h3>
                    <p style="color: var(--text-light); font-size: 0.9rem; margin-top: 0.5rem;">Monitor continuous ledger outputs and dynamic debit classifications instantly.</p>
                </div>
                <div class="card" style="padding: 2rem;">
                    <i class="fa-solid fa-calendar-check" style="font-size: 2rem; color: var(--success); margin-bottom: 1rem;"></i>
                    <h3>Budget Planning</h3>
                    <p style="color: var(--text-light); font-size: 0.9rem; margin-top: 0.5rem;">Configure baseline periodic restrictions mapped against custom ledger sets.</p>
                </div>
                <div class="card" style="padding: 2rem;">
                    <i class="fa-solid fa-chart-line" style="font-size: 2rem; color: var(--accent-ai); margin-bottom: 1rem;"></i>
                    <h3>Financial Reports</h3>
                    <p style="color: var(--text-light); font-size: 0.9rem; margin-top: 0.5rem;">Extract balance summaries and complete analytics records on demand.</p>
                </div>
                <div class="card" style="padding: 2rem;">
                    <i class="fa-solid fa-shield-halved" style="font-size: 2rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>Secure Login</h3>
                    <p style="color: var(--text-light); font-size: 0.9rem; margin-top: 0.5rem;">Robust authentication structures designed around secure backend environments.</p>
                </div>
                <div class="card" style="padding: 2rem;">
                    <i class="fa-solid fa-display" style="font-size: 2rem; color: var(--accent-ai); margin-bottom: 1rem;"></i>
                    <h3>Responsive Dashboard</h3>
                    <p style="color: var(--text-light); font-size: 0.9rem; margin-top: 0.5rem;">Unified viewport controls perfectly suited for desktop, tablet, and mobile configurations.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="statistics-section" style="padding: 5rem 2rem; max-width: 1200px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 2rem; text-align: center;">
            <div class="card" style="padding: 2.5rem; border-top: 4px solid var(--primary-color);">
                <h2 style="font-size: 2.5rem; color: var(--secondary-color); margin-bottom: 0.5rem;">1500+</h2>
                <p style="color: var(--text-light); font-weight: 600;">Users</p>
            </div>
            <div class="card" style="padding: 2.5rem; border-top: 4px solid var(--success);">
                <h2 style="font-size: 2.5rem; color: var(--secondary-color); margin-bottom: 0.5rem;">₹20L+</h2>
                <p style="color: var(--text-light); font-weight: 600;">Transactions</p>
            </div>
            <div class="card" style="padding: 2.5rem; border-top: 4px solid var(--accent-ai);">
                <h2 style="font-size: 2.5rem; color: var(--secondary-color); margin-bottom: 0.5rem;">99%</h2>
                <p style="color: var(--text-light); font-weight: 600;">Accuracy</p>
            </div>
            <div class="card" style="padding: 2.5rem; border-top: 4px solid var(--primary-color);">
                <h2 style="font-size: 2.5rem; color: var(--secondary-color); margin-bottom: 0.5rem;">24/7</h2>
                <p style="color: var(--text-light); font-weight: 600;">Support</p>
            </div>
        </div>
    </section>

   <!-- Intelligent AI Modules Section -->
    <section id="ai-insights" class="section-padding">
        <div class="ai-modules-wrapper">
            
            <div class="ai-modules-header">
                <h2>AI MODULES</h2>
            </div>

            <!-- Side-by-Side Grid Container -->
            <div class="ai-modules-grid">
                
                <!-- Module 1: Monthly Expense Comparison -->
                <div class="ai-module-card module-blue">
                    <div class="module-icon">
                        <!-- Stacked FontAwesome icons to match magnifying glass over chart -->
                        <span class="fa-stack fa-lg">
                            <i class="fa-solid fa-chart-column fa-stack-1x" style="margin-left: -10px;"></i>
                            <i class="fa-solid fa-magnifying-glass fa-stack-1x" style="margin-top: 10px; margin-left: 10px; font-size: 0.7em;"></i>
                        </span>
                    </div>
                    <div class="module-content">
                        <h3>1. Monthly Expense Comparison</h3>
                        <p>Compare current month expense with previous month and show % increase / decrease.</p>
                    </div>
                </div>

                <!-- Module 2: Personalized Financial Insights -->
                <div class="ai-module-card module-purple">
                    <div class="module-icon">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <div class="module-content">
                        <h3>2. Personalized Financial Insights</h3>
                        <p>Analyze spending patterns and provide personalized suggestions & insights.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Why Choose SmartSpend AI Section -->
    <section class="why-choose-section" style="padding: 5rem 2rem; background: var(--card-bg);">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 3rem;">
                <h2>Why Choose SmartSpend AI</h2>
                <p style="color: var(--text-light); margin-top: 0.5rem;">Engineered for high-reliability data indexing and analytical performance.</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; text-align: center;">
                <div class="card" style="padding: 2rem;">
                    <i class="fa-solid fa-face-smile" style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                    <h3>Easy to Use</h3>
                </div>
                <div class="card" style="padding: 2rem;">
                    <i class="fa-solid fa-microchip" style="font-size: 2.5rem; color: var(--accent-ai); margin-bottom: 1rem;"></i>
                    <h3>AI Powered</h3>
                </div>
                <div class="card" style="padding: 2rem;">
                    <i class="fa-solid fa-lock" style="font-size: 2.5rem; color: var(--success); margin-bottom: 1rem;"></i>
                    <h3>Secure</h3>
                </div>
                <div class="card" style="padding: 2rem;">
                    <i class="fa-solid fa-bolt" style="font-size: 2.5rem; color: var(--danger); margin-bottom: 1rem;"></i>
                    <h3>Fast Reports</h3>
                </div>
            </div>
        </div>
    </section>

<!-- Contact + Footer -->
<footer id="contact" style="background:#0f172a;color:#cbd5e1;padding:60px 20px 20px;">

    <div style="max-width:1200px;margin:auto;display:grid;
    grid-template-columns:2fr 1fr 1fr;
    gap:50px;">

        <!-- Brand -->
        <div>
            <h2 style="color:#fff;margin-bottom:15px;">
                <i class="fa-solid fa-brain-circuit"></i>
                SmartSpend <span style="color:#38bdf8;">AI</span>
            </h2>

            <p style="font-size:15px;line-height:1.8;color:#94a3b8;">
                SmartSpend AI is an intelligent expense tracking platform
                developed using Laravel, PHP, and MySQL. It helps users
                manage income, expenses, budgets, and provides rule-based
                AI financial insights for smarter financial decisions.
            </p>

            <div style="display:flex;gap:18px;margin-top:25px;font-size:20px;">
                <a href="#" style="color:white;"><i class="fab fa-facebook-f"></i></a>
                <a href="#" style="color:white;"><i class="fab fa-instagram"></i></a>
                <a href="#" style="color:white;"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" style="color:white;"><i class="fab fa-github"></i></a>
            </div>
        </div>

        <!-- Quick Links -->
        <div>
            <h4 style="color:white;margin-bottom:18px;">Quick Links</h4>

            <p><a href="#home" style="color:#94a3b8;text-decoration:none;">Home</a></p>
            <p><a href="#about" style="color:#94a3b8;text-decoration:none;">About</a></p>
            <p><a href="#features" style="color:#94a3b8;text-decoration:none;">Features</a></p>
            <p><a href="#ai-insights" style="color:#94a3b8;text-decoration:none;">AI Insights</a></p>
            <p><a href="#how-it-works" style="color:#94a3b8;text-decoration:none;">How It Works</a></p>
        </div>

        <!-- Contact -->
        <div>
            <h4 style="color:white;margin-bottom:18px;">Contact</h4>

            <p style="margin-bottom:12px;">
                <i class="fa-solid fa-phone" style="color:#38bdf8;width:20px;"></i>
                +91 98765 43210
            </p>

            <p style="margin-bottom:12px;">
                <i class="fa-solid fa-envelope" style="color:#38bdf8;width:20px;"></i>
                support@smartspendai.com
            </p>

            <p style="margin-bottom:12px;">
                <i class="fa-solid fa-location-dot" style="color:#38bdf8;width:20px;"></i>
                Kerala, India
            </p>

            <p>
                <i class="fa-solid fa-clock" style="color:#38bdf8;width:20px;"></i>
                Mon - Fri | 9:00 AM - 6:00 PM
            </p>
        </div>

    </div>

    <hr style="margin:40px auto 20px;border:0;border-top:1px solid #334155;max-width:1200px;">

    <div style="max-width:1200px;margin:auto;display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:10px;
    font-size:14px;
    color:#94a3b8;">

        <span>© 2026 SmartSpend AI. All Rights Reserved.</span>

        <span>Developed using Laravel • PHP • MySQL • Bootstrap</span>

    </div>

</footer>

    <!-- Updated JS Asset Path -->
    <script src="{{ asset('guest/assets/app.js') }}"></script>
</body>
</html>