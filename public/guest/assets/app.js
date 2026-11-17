/**
 * SmartSpend AI - Guest Experience Scripts
 * Includes: Mobile Navigation & Dynamic 3D Money Flow Engine
 */

document.addEventListener('DOMContentLoaded', () => {
    initMobileNav();
    initMoneyFlow();
});

/* ==========================================================================
   1. MOBILE SIDE-DRAWER NAVIGATION
   ========================================================================== */
function initMobileNav() {
    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');
    const menuIcon = document.getElementById('menuIcon');

    if (!menuToggle || !navMenu) return;

    menuToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        navMenu.classList.toggle('active');
        
        if (navMenu.classList.contains('active')) {
            if (menuIcon) {
                menuIcon.classList.remove('fa-bars');
                menuIcon.classList.add('fa-xmark');
            }
        } else {
            if (menuIcon) {
                menuIcon.classList.remove('fa-xmark');
                menuIcon.classList.add('fa-bars');
            }
        }
    });

    // Auto-close menu when clicking a link inside
    const links = navMenu.querySelectorAll('a');
    links.forEach(function(link) {
        link.addEventListener('click', function() {
            navMenu.classList.remove('active');
            if (menuIcon) {
                menuIcon.classList.remove('fa-xmark');
                menuIcon.classList.add('fa-bars');
            }
        });
    });

    // Auto-close menu if clicking outside
    document.addEventListener('click', function(e) {
        if (!navMenu.contains(e.target) && !menuToggle.contains(e.target)) {
            navMenu.classList.remove('active');
            if (menuIcon) {
                menuIcon.classList.remove('fa-xmark');
                menuIcon.classList.add('fa-bars');
            }
        }
    });
}

/* ==========================================================================
   2. SIMPLE AMBIENT BACKGROUND MONEY FLOW (BILLS & 3D GOLD COINS)
   ========================================================================== */
function initMoneyFlow() {
    const container = document.getElementById('moneyRain');
    if (!container) return;

    // Vector templates for background banknotes and shiny 3D gold coins
    const TEMPLATES = {
        // Indian Rupee 500 Banknote
        inrBill: `
            <svg viewBox="0 0 100 48" xmlns="http://www.w3.org/2000/svg">
                <rect x="1" y="1" width="98" height="46" rx="3" fill="#1b4d3e" stroke="#34d399" stroke-width="1" stroke-opacity="0.6"/>
                <rect x="4" y="4" width="92" height="40" rx="2" fill="none" stroke="#6ee7b7" stroke-width="0.5" stroke-dasharray="2,2" opacity="0.4"/>
                <circle cx="50" cy="24" r="11" fill="#143e32" stroke="#34d399" stroke-width="0.6" opacity="0.6"/>
                <text x="50" y="29" font-family="'Segoe UI', sans-serif" font-weight="900" font-size="13" fill="#a7f3d0" text-anchor="middle">₹</text>
                <text x="12" y="17" font-family="'Segoe UI', sans-serif" font-weight="800" font-size="9" fill="#e2e8f0">₹500</text>
                <text x="88" y="38" font-family="'Segoe UI', sans-serif" font-weight="800" font-size="8" fill="#e2e8f0" text-anchor="end">500</text>
            </svg>`,

        // Indian Rupee 200 Banknote (Golden Amber)
        inr200Bill: `
            <svg viewBox="0 0 100 48" xmlns="http://www.w3.org/2000/svg">
                <rect x="1" y="1" width="98" height="46" rx="3" fill="#854d0e" stroke="#fbbf24" stroke-width="1" stroke-opacity="0.6"/>
                <rect x="4" y="4" width="92" height="40" rx="2" fill="none" stroke="#fde68a" stroke-width="0.5" stroke-dasharray="2,2" opacity="0.4"/>
                <circle cx="50" cy="24" r="11" fill="#713f12" stroke="#fbbf24" stroke-width="0.6" opacity="0.6"/>
                <text x="50" y="29" font-family="'Segoe UI', sans-serif" font-weight="900" font-size="13" fill="#fef08a" text-anchor="middle">₹</text>
                <text x="12" y="17" font-family="'Segoe UI', sans-serif" font-weight="800" font-size="9" fill="#fef9c3">₹200</text>
                <text x="88" y="38" font-family="'Segoe UI', sans-serif" font-weight="800" font-size="8" fill="#fef9c3" text-anchor="end">200</text>
            </svg>`,

        // US Dollar 100 Banknote
        usdBill: `
            <svg viewBox="0 0 100 48" xmlns="http://www.w3.org/2000/svg">
                <rect x="1" y="1" width="98" height="46" rx="3" fill="#173e30" stroke="#86efac" stroke-width="1" stroke-opacity="0.5"/>
                <rect x="4" y="4" width="92" height="40" rx="2" fill="none" stroke="#a7f3d0" stroke-width="0.5" stroke-dasharray="2,2" opacity="0.4"/>
                <circle cx="50" cy="24" r="11" fill="#123026" stroke="#86efac" stroke-width="0.6" opacity="0.6"/>
                <text x="50" y="29" font-family="'Segoe UI', sans-serif" font-weight="900" font-size="13" fill="#a7f3d0" text-anchor="middle">$</text>
                <text x="12" y="17" font-family="'Segoe UI', sans-serif" font-weight="800" font-size="9" fill="#e2e8f0">$100</text>
                <text x="88" y="38" font-family="'Segoe UI', sans-serif" font-weight="800" font-size="8" fill="#e2e8f0" text-anchor="end">100</text>
            </svg>`,

        // 3D Spinning Gold Indian Rupee Coin
        inrCoin: `
            <svg viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <radialGradient id="inrGoldGrad" cx="35%" cy="30%" r="65%">
                        <stop offset="0%" stop-color="#fffbeb"/>
                        <stop offset="35%" stop-color="#f59e0b"/>
                        <stop offset="75%" stop-color="#d97706"/>
                        <stop offset="100%" stop-color="#78350f"/>
                    </radialGradient>
                    <radialGradient id="inrInnerGrad" cx="40%" cy="35%" r="60%">
                        <stop offset="0%" stop-color="#fef08a"/>
                        <stop offset="60%" stop-color="#d97706"/>
                        <stop offset="100%" stop-color="#92400e"/>
                    </radialGradient>
                </defs>
                <circle cx="22" cy="22" r="20" fill="url(#inrGoldGrad)" stroke="#fde047" stroke-width="1.2"/>
                <circle cx="22" cy="22" r="17" fill="none" stroke="#fef08a" stroke-width="0.8" stroke-dasharray="2,1.5" opacity="0.8"/>
                <circle cx="22" cy="22" r="15" fill="url(#inrInnerGrad)"/>
                <text x="22.5" y="28.5" font-family="'Segoe UI', sans-serif" font-weight="900" font-size="17" fill="#78350f" text-anchor="middle">₹</text>
                <text x="21.5" y="27.5" font-family="'Segoe UI', sans-serif" font-weight="900" font-size="17" fill="#fffbeb" text-anchor="middle">₹</text>
                <text x="22" y="28" font-family="'Segoe UI', sans-serif" font-weight="900" font-size="17" fill="#f59e0b" text-anchor="middle">₹</text>
                <path d="M 11 16 A 14 14 0 0 1 26 9" fill="none" stroke="#ffffff" stroke-width="1.8" stroke-linecap="round" opacity="0.65"/>
            </svg>`,

        // 3D Spinning Gold US Dollar Coin
        usdCoin: `
            <svg viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <radialGradient id="usdGoldGrad" cx="35%" cy="30%" r="65%">
                        <stop offset="0%" stop-color="#fffbeb"/>
                        <stop offset="35%" stop-color="#f59e0b"/>
                        <stop offset="75%" stop-color="#d97706"/>
                        <stop offset="100%" stop-color="#78350f"/>
                    </radialGradient>
                    <radialGradient id="usdInnerGrad" cx="40%" cy="35%" r="60%">
                        <stop offset="0%" stop-color="#fef08a"/>
                        <stop offset="60%" stop-color="#d97706"/>
                        <stop offset="100%" stop-color="#92400e"/>
                    </radialGradient>
                </defs>
                <circle cx="22" cy="22" r="20" fill="url(#usdGoldGrad)" stroke="#fde047" stroke-width="1.2"/>
                <circle cx="22" cy="22" r="17" fill="none" stroke="#fef08a" stroke-width="0.8" stroke-dasharray="2,1.5" opacity="0.8"/>
                <circle cx="22" cy="22" r="15" fill="url(#usdInnerGrad)"/>
                <text x="22.5" y="28.5" font-family="'Segoe UI', sans-serif" font-weight="900" font-size="17" fill="#78350f" text-anchor="middle">$</text>
                <text x="21.5" y="27.5" font-family="'Segoe UI', sans-serif" font-weight="900" font-size="17" fill="#fffbeb" text-anchor="middle">$</text>
                <text x="22" y="28" font-family="'Segoe UI', sans-serif" font-weight="900" font-size="17" fill="#f59e0b" text-anchor="middle">$</text>
                <path d="M 11 16 A 14 14 0 0 1 26 9" fill="none" stroke="#ffffff" stroke-width="1.8" stroke-linecap="round" opacity="0.65"/>
            </svg>`
    };

    // Curated mix featuring prominent gold coins alongside banknotes
    const ITEMS = [
        { type: 'coin', html: TEMPLATES.inrCoin, opacity: 0.52 },
        { type: 'bill', html: TEMPLATES.inrBill, opacity: 0.28 },
        { type: 'coin', html: TEMPLATES.usdCoin, opacity: 0.48 },
        { type: 'bill', html: TEMPLATES.usdBill, opacity: 0.26 },
        { type: 'coin', html: TEMPLATES.inrCoin, opacity: 0.55 },
        { type: 'bill', html: TEMPLATES.inr200Bill, opacity: 0.30 },
        { type: 'coin', html: TEMPLATES.usdCoin, opacity: 0.50 }
    ];

    const isMobile = window.innerWidth <= 768;
    const count = isMobile ? 10 : 18;

    container.innerHTML = '';

    for (let i = 0; i < count; i++) {
        const spec = ITEMS[i % ITEMS.length];
        const el = document.createElement('div');
        el.className = `money-item ${spec.type}`;
        el.innerHTML = spec.html;

        // Varied positions, speeds and subtle drift
        const fallDuration = 13 + Math.random() * 9; // Slow, calm fall (13s - 22s)
        const leftPos = (Math.random() * 96 + 2).toFixed(1) + '%';
        const initialDelay = (-Math.random() * fallDuration).toFixed(1) + 's';
        const swayAmount = (18 + Math.random() * 26).toFixed(0) + 'px';
        const rotStart = (-15 + Math.random() * 10).toFixed(0) + 'deg';
        const rotMid = (5 + Math.random() * 15).toFixed(0) + 'deg';
        const rotEnd = (-12 + Math.random() * 10).toFixed(0) + 'deg';
        const scale = (0.8 + Math.random() * 0.35).toFixed(2);
        const itemOpacity = (spec.opacity * (0.85 + Math.random() * 0.3)).toFixed(2);

        el.style.left = leftPos;
        el.style.setProperty('--item-opacity', itemOpacity);
        el.style.setProperty('--sway', swayAmount);
        el.style.setProperty('--rot-start', rotStart);
        el.style.setProperty('--rot-mid', rotMid);
        el.style.setProperty('--rot-end', rotEnd);
        el.style.animationDuration = `${fallDuration.toFixed(1)}s`;
        el.style.animationDelay = initialDelay;
        el.style.transform = `scale(${scale})`;

        container.appendChild(el);
    }

    // Pause when tab is inactive to preserve CPU & battery
    document.addEventListener('visibilitychange', () => {
        container.style.animationPlayState = document.hidden ? 'paused' : 'running';
        const children = container.children;
        for (let j = 0; j < children.length; j++) {
            children[j].style.animationPlayState = document.hidden ? 'paused' : 'running';
        }
    });
}