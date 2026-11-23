<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// If Composer dependencies are installed, boot Laravel framework normally
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
        require $maintenance;
    }
    require __DIR__.'/../vendor/autoload.php';
    (require_once __DIR__.'/../bootstrap/app.php')->handleRequest(Request::capture());
    exit;
}

// ─────────────────────────────────────────────────────────────────────────────
// Standalone G4ce BPO Services — Complete Enterprise Landing Pages & Booking
// Includes: Home, Services, About Us, Why G4ce, FAQ, Contact, and Booking Wizard
// ─────────────────────────────────────────────────────────────────────────────
?>
<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>G4ce BPO Services — World-Class Dedicated Outsourcing Teams</title>
    <meta name="description" content="Scale your business operations with G4ce BPO. Enterprise customer support, technical helpdesk, back-office data processing, and dedicated staff augmentation.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                            950: '#0f172a',
                        }
                    },
                    boxShadow: {
                        'card': '0 1px 3px 0 rgb(0 0 0 / 0.05), 0 1px 2px -1px rgb(0 0 0 / 0.05)',
                        'card-md': '0 4px 6px -1px rgb(0 0 0 / 0.07), 0 2px 4px -2px rgb(0 0 0 / 0.07)',
                        'card-lg': '0 10px 15px -3px rgb(0 0 0 / 0.08), 0 4px 6px -4px rgb(0 0 0 / 0.08)',
                        'card-xl': '0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .nav-link {
            font-size: 0.875rem; font-weight: 500; color: #64748b;
            padding: 0.5rem 0.75rem; border-radius: 0.5rem; transition: all 0.15s ease;
        }
        .nav-link:hover, .nav-link.active { color: #1e293b; background: #f1f5f9; }
        .step-dot {
            width: 2.25rem; height: 2.25rem; border-radius: 9999px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.875rem; font-weight: 700; transition: all 0.2s ease;
        }
        .step-dot.active { background: #2563eb; color: #fff; box-shadow: 0 0 0 4px #dbeafe; }
        .step-dot.completed { background: #10b981; color: #fff; }
        .step-dot.pending { background: #f1f5f9; color: #94a3b8; }
        .service-card {
            position: relative; display: flex; flex-direction: column; gap: 0.5rem; padding: 1rem;
            border-radius: 0.75rem; border: 2px solid #e2e8f0; background: #fff; cursor: pointer;
            transition: all 0.2s ease; text-align: left;
        }
        .service-card:hover { border-color: #93c5fd; transform: translateY(-2px); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.07); }
        .service-card.selected { border-color: #2563eb; background: #eff6ff; }
        .headcount-pill {
            padding: 0.625rem 1.25rem; border-radius: 0.5rem; border: 1.5px solid #e2e8f0;
            background: #fff; font-size: 0.875rem; font-weight: 600; color: #334155;
            cursor: pointer; transition: all 0.15s ease;
        }
        .headcount-pill:hover { border-color: #93c5fd; background: #f8fafc; }
        .headcount-pill.selected { border-color: #2563eb; background: #2563eb; color: #fff; }
        .tz-chip {
            padding: 0.25rem 0.625rem; border-radius: 0.375rem; border: 1px solid #e2e8f0;
            font-size: 0.75rem; font-weight: 600; color: #475569; background: #fff; cursor: pointer;
        }
        .tz-chip.active { border-color: #2563eb; background: #2563eb; color: #fff; }
        .cal-day {
            width: 2.25rem; height: 2.25rem; border-radius: 0.5rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8125rem; font-weight: 600; color: #1e293b;
            transition: all 0.15s ease;
        }
        .cal-day:not(.disabled):hover { background: #dbeafe; color: #1d4ed8; }
        .cal-day.selected { background: #2563eb !important; color: #fff !important; }
        .cal-day.disabled { color: #cbd5e1; cursor: not-allowed; }
        .cal-day.today { border: 1.5px solid #2563eb; }
        .time-slot {
            padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1.5px solid #e2e8f0;
            background: #fff; font-size: 0.8125rem; font-weight: 600; color: #334155;
            text-align: center; cursor: pointer; transition: all 0.15s ease;
        }
        .time-slot:hover { border-color: #93c5fd; background: #eff6ff; }
        .time-slot.selected { border-color: #2563eb; background: #2563eb; color: #fff; }
        .gradient-text {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 50%, #38bdf8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="min-h-full flex flex-col antialiased text-slate-800">

    <!-- Top Navigation Bar -->
    <header class="border-b border-slate-200 bg-white/95 backdrop-blur-md sticky top-0 z-50">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <!-- Brand Logo -->
            <a href="#home" class="flex items-center gap-3 group">
                <img src="images/logo.webp" alt="G4ce BPO Services" class="h-10 sm:h-11 w-auto object-contain transition-transform group-hover:scale-105" onerror="this.onerror=null; this.src='images/4a87ef88e3c8d652c89e01aa05b9f25a_G4CE-Logo.webp'">
            </a>

            <!-- Desktop Navigation Menu -->
            <nav class="hidden md:flex items-center gap-1">
                <a href="#home" class="nav-link active">Home</a>
                <a href="#services" class="nav-link">Services</a>
                <a href="#about" class="nav-link">About Us</a>
                <a href="#why-us" class="nav-link">Why G4ce</a>
                <a href="#faq" class="nav-link">FAQ</a>
                <a href="#contact" class="nav-link">Contact</a>
            </nav>

            <!-- CTA Actions -->
            <div class="hidden sm:flex items-center gap-3">
                <a href="#booking" onclick="scrollToBooking(1)" class="bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold px-4 py-2.5 rounded-lg transition-all shadow-sm hover:shadow hover:-translate-y-0.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Book a Call
                </a>
            </div>

            <!-- Mobile Hamburger Toggle -->
            <button type="button" onclick="toggleMobileMenu()" class="md:hidden p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-100 bg-white px-4 py-3 space-y-1 shadow-lg">
            <a href="#home" onclick="toggleMobileMenu()" class="block py-2 text-sm font-semibold text-slate-700 hover:text-brand-600">Home</a>
            <a href="#services" onclick="toggleMobileMenu()" class="block py-2 text-sm font-semibold text-slate-700 hover:text-brand-600">Services</a>
            <a href="#about" onclick="toggleMobileMenu()" class="block py-2 text-sm font-semibold text-slate-700 hover:text-brand-600">About Us</a>
            <a href="#why-us" onclick="toggleMobileMenu()" class="block py-2 text-sm font-semibold text-slate-700 hover:text-brand-600">Why G4ce</a>
            <a href="#faq" onclick="toggleMobileMenu()" class="block py-2 text-sm font-semibold text-slate-700 hover:text-brand-600">FAQ</a>
            <a href="#contact" onclick="toggleMobileMenu()" class="block py-2 text-sm font-semibold text-slate-700 hover:text-brand-600">Contact</a>
            <div class="pt-2">
                <a href="#booking" onclick="toggleMobileMenu(); scrollToBooking(1);" class="w-full block text-center bg-brand-600 text-white text-xs font-bold py-2.5 rounded-lg">
                    Book a Discovery Call
                </a>
            </div>
        </div>
    </header>

    <!-- MAIN BODY SECTIONS -->
    <main class="flex-1">

        <!-- ═══════════════════════════════════════════════════════════════════
             SECTION 1: HOME / HERO
        ═══════════════════════════════════════════════════════════════════ -->
        <section id="home" class="relative overflow-hidden bg-gradient-to-b from-slate-900 via-slate-900 to-slate-950 text-white pt-20 pb-28 px-4 sm:px-6 lg:px-8">
            <!-- Background Glow Blobs -->
            <div class="absolute -top-40 -right-40 h-[600px] w-[600px] rounded-full bg-brand-600 opacity-20 blur-[130px] pointer-events-none"></div>
            <div class="absolute top-1/2 -left-40 h-[500px] w-[500px] rounded-full bg-indigo-600 opacity-20 blur-[130px] pointer-events-none"></div>

            <div class="relative mx-auto max-w-5xl text-center">
                <!-- Eyebrow Badge -->
                <div class="inline-flex items-center gap-2 rounded-full bg-brand-900/60 border border-brand-500/30 px-4 py-1.5 text-xs font-semibold text-brand-300 mb-6 backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                    </span>
                    Premier BPO Outsourcing & Dedicated Offshore Teams
                </div>

                <!-- Main Punchy Title -->
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-6xl lg:text-7xl leading-tight">
                    Scale Your Enterprise With <br class="hidden sm:inline">
                    <span class="bg-gradient-to-r from-blue-400 via-indigo-300 to-sky-400 bg-clip-text text-transparent">
                        High-Performance BPO Teams
                    </span>
                </h1>

                <p class="mt-6 text-base sm:text-xl text-slate-300 max-w-3xl mx-auto leading-relaxed font-normal">
                    We deliver managed customer care, tier 1/2 technical support, back-office operations, and dedicated staff augmentation. Reduce operational costs by up to <strong>65%</strong> with zero compromise on quality.
                </p>

                <!-- Action CTAs -->
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="#booking" onclick="scrollToBooking(1)" class="w-full sm:w-auto bg-brand-600 hover:bg-brand-500 text-white font-bold text-sm px-8 py-3.5 rounded-xl shadow-lg shadow-brand-600/30 hover:shadow-brand-600/50 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        <span>Schedule a 30-Min Discovery Call</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="#services" class="w-full sm:w-auto border border-slate-700 bg-slate-800/80 hover:bg-slate-700 text-slate-200 font-semibold text-sm px-7 py-3.5 rounded-xl transition-all flex items-center justify-center gap-2 backdrop-blur-sm">
                        <span>Explore Our Services</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                </div>

                <!-- Key Metrics Bar -->
                <div class="mt-16 grid grid-cols-2 lg:grid-cols-4 gap-4 max-w-4xl mx-auto text-left">
                    <div class="p-5 rounded-xl bg-slate-800/60 border border-slate-700/60 backdrop-blur-sm">
                        <div class="text-3xl font-extrabold text-blue-400">65%</div>
                        <div class="text-xs font-semibold text-slate-300 mt-1 uppercase tracking-wider">Avg Cost Reduction</div>
                        <p class="text-[11px] text-slate-400 mt-1">Compared to onshore US/UK/AU staffing</p>
                    </div>
                    <div class="p-5 rounded-xl bg-slate-800/60 border border-slate-700/60 backdrop-blur-sm">
                        <div class="text-3xl font-extrabold text-emerald-400">99.4%</div>
                        <div class="text-xs font-semibold text-slate-300 mt-1 uppercase tracking-wider">SLA Adherence</div>
                        <p class="text-[11px] text-slate-400 mt-1">Strict CSAT & resolution benchmarks</p>
                    </div>
                    <div class="p-5 rounded-xl bg-slate-800/60 border border-slate-700/60 backdrop-blur-sm">
                        <div class="text-3xl font-extrabold text-indigo-400">500+</div>
                        <div class="text-xs font-semibold text-slate-300 mt-1 uppercase tracking-wider">Deployed Specialists</div>
                        <p class="text-[11px] text-slate-400 mt-1">Trained across Zendesk, Salesforce & ERPs</p>
                    </div>
                    <div class="p-5 rounded-xl bg-slate-800/60 border border-slate-700/60 backdrop-blur-sm">
                        <div class="text-3xl font-extrabold text-amber-400">24/7/365</div>
                        <div class="text-xs font-semibold text-slate-300 mt-1 uppercase tracking-wider">Omnichannel Coverage</div>
                        <p class="text-[11px] text-slate-400 mt-1">Voice, live chat, email, and ticketing</p>
                    </div>
                </div>

                <!-- Enterprise Logo Badges -->
                <div class="mt-14 pt-8 border-t border-slate-800/80">
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-6">Trusted By Growth-Stage Startups & Global Enterprises</p>
                    <div class="flex flex-wrap justify-center items-center gap-8 sm:gap-14 opacity-60 text-slate-300 text-sm font-bold tracking-wider">
                        <span class="flex items-center gap-2">🌐 FINTECH SCALE</span>
                        <span class="flex items-center gap-2">⚡ SAAS GLOBAL</span>
                        <span class="flex items-center gap-2">📦 E-COMMERCE PLUS</span>
                        <span class="flex items-center gap-2">🏥 MEDTECH CARE</span>
                        <span class="flex items-center gap-2">✈️ TRAVELPORTAL</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════════════
             SECTION 2: SERVICES
        ═══════════════════════════════════════════════════════════════════ -->
        <section id="services" class="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200 px-3 py-1 rounded-full">
                    Our Core Capabilities
                </span>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 mt-4 tracking-tight">
                    Comprehensive BPO Solutions Built for Growth
                </h2>
                <p class="text-base text-slate-500 mt-4 leading-relaxed">
                    Whether you need a full turnkey contact center or a tailored team of 3 specialists, our vetted professionals integrate directly into your operations.
                </p>
            </div>

            <!-- Service Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Service 1 -->
                <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-card hover:shadow-card-lg transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-6 group-hover:bg-brand-600 group-hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Inbound & Outbound Customer Care</h3>
                        <p class="text-sm text-slate-500 leading-relaxed mb-6">
                            24/7 omnichannel voice, email, chat, and social media support. We handle retention, dispute resolution, and elevate your customer satisfaction (CSAT) scores.
                        </p>
                        <ul class="space-y-2.5 text-xs text-slate-600 mb-8">
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Omnichannel Voice, Chat & Email Support
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Zendesk, Freshdesk, Intercom & Salesforce
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Real-Time Quality Assurance & CSAT Tracking
                            </li>
                        </ul>
                    </div>
                    <button onclick="preselectService(1)" class="w-full text-center py-2.5 px-4 rounded-lg bg-slate-50 hover:bg-brand-600 hover:text-white text-brand-600 font-semibold text-xs border border-slate-200 hover:border-brand-600 transition-all">
                        Book Customer Care Team &rarr;
                    </button>
                </div>

                <!-- Service 2 -->
                <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-card hover:shadow-card-lg transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9v-9z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Technical Support & Helpdesk L1/L2</h3>
                        <p class="text-sm text-slate-500 leading-relaxed mb-6">
                            Technical customer troubleshooting, software bug triage, hardware diagnostics, and ITSM escalation management to free up your core engineering teams.
                        </p>
                        <ul class="space-y-2.5 text-xs text-slate-600 mb-8">
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Tier 1 & Tier 2 SLA-Driven Troubleshooting
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Jira Service Management, ServiceNow & Datadog
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Incident Triage & Bug Verification
                            </li>
                        </ul>
                    </div>
                    <button onclick="preselectService(2)" class="w-full text-center py-2.5 px-4 rounded-lg bg-slate-50 hover:bg-brand-600 hover:text-white text-brand-600 font-semibold text-xs border border-slate-200 hover:border-brand-600 transition-all">
                        Book Tech Support Team &rarr;
                    </button>
                </div>

                <!-- Service 3 -->
                <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-card hover:shadow-card-lg transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Back-Office & Data Processing</h3>
                        <p class="text-sm text-slate-500 leading-relaxed mb-6">
                            High-accuracy data entry, document validation, financial reconciliation, content moderation, and operational auditing performed with 99.8% precision.
                        </p>
                        <ul class="space-y-2.5 text-xs text-slate-600 mb-8">
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Financial & Invoice Reconciliation
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Document Verification & KYC Validation
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> 99.8% Dual-Verification Accuracy
                            </li>
                        </ul>
                    </div>
                    <button onclick="preselectService(3)" class="w-full text-center py-2.5 px-4 rounded-lg bg-slate-50 hover:bg-brand-600 hover:text-white text-brand-600 font-semibold text-xs border border-slate-200 hover:border-brand-600 transition-all">
                        Book Back-Office Team &rarr;
                    </button>
                </div>

                <!-- Service 4 -->
                <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-card hover:shadow-card-lg transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Virtual Assistance & Executive Ops</h3>
                        <p class="text-sm text-slate-500 leading-relaxed mb-6">
                            Dedicated executive assistants for founders, leadership, and operational managers. Complex calendar coordination, flight bookings, inbox triage, and research.
                        </p>
                        <ul class="space-y-2.5 text-xs text-slate-600 mb-8">
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Executive Inbox & Calendar Management
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> CRM Hygiene & Lead Sourcing
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Confidential Operations & NDA-Compliant
                            </li>
                        </ul>
                    </div>
                    <button onclick="preselectService(4)" class="w-full text-center py-2.5 px-4 rounded-lg bg-slate-50 hover:bg-brand-600 hover:text-white text-brand-600 font-semibold text-xs border border-slate-200 hover:border-brand-600 transition-all">
                        Book Virtual Assistant &rarr;
                    </button>
                </div>

                <!-- Service 5 -->
                <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-card hover:shadow-card-lg transition-all duration-300 flex flex-col justify-between group hover:-translate-y-1">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-6 group-hover:bg-amber-600 group-hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Dedicated Staff Augmentation</h3>
                        <p class="text-sm text-slate-500 leading-relaxed mb-6">
                            Expand your engineering, QA testing, design, or business development teams with dedicated talent embedded directly into your company's Slack and Jira.
                        </p>
                        <ul class="space-y-2.5 text-xs text-slate-600 mb-8">
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Direct Integration into Your Slack & Jira
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Top 3% Pre-Vetted University Graduates
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="text-emerald-500 font-bold">✓</span> Seamless Shift Synchronization (US/UK/AU)
                            </li>
                        </ul>
                    </div>
                    <button onclick="preselectService(5)" class="w-full text-center py-2.5 px-4 rounded-lg bg-slate-50 hover:bg-brand-600 hover:text-white text-brand-600 font-semibold text-xs border border-slate-200 hover:border-brand-600 transition-all">
                        Book Dedicated Team &rarr;
                    </button>
                </div>

                <!-- Custom Enterprise Pod Banner -->
                <div class="bg-gradient-to-br from-brand-900 to-indigo-950 rounded-2xl p-8 text-white flex flex-col justify-between shadow-card-lg">
                    <div>
                        <div class="inline-block px-3 py-1 rounded-full bg-brand-500/20 border border-brand-400/30 text-brand-300 text-[10px] font-bold uppercase tracking-widest mb-4">
                            Enterprise Scale
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Custom Outsourcing Pods (20+ Agents)</h3>
                        <p class="text-sm text-slate-300 leading-relaxed mb-6">
                            Require a turnkey delivery department with dedicated Team Leads, QA Specialists, and Operations Managers? We build bespoke setups within 14 business days.
                        </p>
                        <div class="space-y-2 text-xs text-brand-200 mb-6">
                            <div>• Custom hardware/VDI infrastructure</div>
                            <div>• ISO 27001 physical & network security</div>
                            <div>• Dedicated shift management & reporting</div>
                        </div>
                    </div>
                    <a href="#booking" onclick="scrollToBooking(1); selectHeadcount('20+', document.querySelector('[onclick*=\'20+\']'));" class="w-full text-center py-3 px-4 rounded-lg bg-white text-slate-900 hover:bg-brand-50 font-bold text-xs transition-all shadow-md">
                        Request Custom Enterprise Proposal &rarr;
                    </a>
                </div>

            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════════════
             SECTION 3: ABOUT US
        ═══════════════════════════════════════════════════════════════════ -->
        <section id="about" class="py-24 bg-white border-y border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <span class="text-xs font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200 px-3 py-1 rounded-full">
                            About G4ce BPO Services
                        </span>
                        <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 mt-4 tracking-tight leading-tight">
                            Bridging Global Talent With Enterprise Execution
                        </h2>
                        <p class="mt-6 text-base text-slate-600 leading-relaxed">
                            Founded to eliminate the traditional friction of offshore outsourcing, <strong>G4ce BPO Services</strong> delivers seamless staff integration, transparent reporting, and enterprise-grade SLA compliance.
                        </p>
                        <p class="mt-4 text-base text-slate-600 leading-relaxed">
                            Headquartered in the global BPO capital of Manila, Philippines, with liaison offices in North America, we provide our partners with bilingual, university-educated professionals who act as a direct extension of their core team.
                        </p>

                        <!-- Highlights List -->
                        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50">
                                <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                    <span class="text-brand-600">📍</span> Global Hubs
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Primary centers in Manila & Cebu with 100% redundant fiber power.</p>
                            </div>
                            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50">
                                <div class="font-bold text-slate-900 text-sm flex items-center gap-2">
                                    <span class="text-emerald-600">🛡️</span> Compliance First
                                </div>
                                <p class="text-xs text-slate-500 mt-1">SOC 2 Type II, ISO 27001, GDPR, and HIPAA compliant environments.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Consultant Leadership Cards -->
                    <div class="space-y-4">
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Meet Your Dedicated Solutions Consultants</div>
                        
                        <!-- Consultant 1 -->
                        <div class="bg-slate-50 rounded-xl border border-slate-200 p-5 flex items-center gap-4 hover:border-brand-300 transition-colors">
                            <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-brand-600 to-indigo-600 text-white font-extrabold flex items-center justify-center text-lg flex-shrink-0 shadow-sm">
                                MS
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-base font-bold text-slate-900">Maria Santos</h4>
                                    <span class="text-[10px] bg-blue-100 text-blue-800 font-semibold px-2 py-0.5 rounded-full">Asia/Manila (PHT)</span>
                                </div>
                                <p class="text-xs text-brand-600 font-medium">Senior BPO Solutions Consultant</p>
                                <p class="text-xs text-slate-500 mt-1">Specializes in 24/7 omnichannel contact centers and CSAT optimization.</p>
                            </div>
                        </div>

                        <!-- Consultant 2 -->
                        <div class="bg-slate-50 rounded-xl border border-slate-200 p-5 flex items-center gap-4 hover:border-brand-300 transition-colors">
                            <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 text-white font-extrabold flex items-center justify-center text-lg flex-shrink-0 shadow-sm">
                                JR
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-base font-bold text-slate-900">James Reyes</h4>
                                    <span class="text-[10px] bg-purple-100 text-purple-800 font-semibold px-2 py-0.5 rounded-full">Asia/Manila (PHT)</span>
                                </div>
                                <p class="text-xs text-brand-600 font-medium">Enterprise Solutions Consultant</p>
                                <p class="text-xs text-slate-500 mt-1">Focuses on high-volume back-office data processing and financial workflows.</p>
                            </div>
                        </div>

                        <!-- Consultant 3 -->
                        <div class="bg-slate-50 rounded-xl border border-slate-200 p-5 flex items-center gap-4 hover:border-brand-300 transition-colors">
                            <div class="w-14 h-14 rounded-full bg-gradient-to-tr from-emerald-600 to-teal-600 text-white font-extrabold flex items-center justify-center text-lg flex-shrink-0 shadow-sm">
                                AL
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-base font-bold text-slate-900">Ava Lin</h4>
                                    <span class="text-[10px] bg-emerald-100 text-emerald-800 font-semibold px-2 py-0.5 rounded-full">US West Coast (PST)</span>
                                </div>
                                <p class="text-xs text-brand-600 font-medium">North America Client Director</p>
                                <p class="text-xs text-slate-500 mt-1">Aligns US timezone workflows, staff augmentation, and NDA legal scoping.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════════════
             SECTION 4: WHY G4CE (COMPETITIVE ADVANTAGE)
        ═══════════════════════════════════════════════════════════════════ -->
        <section id="why-us" class="py-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-xs font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200 px-3 py-1 rounded-full">
                    The G4ce Advantage
                </span>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 mt-4 tracking-tight">
                    Why Leading Brands Choose G4ce Over Generic Agencies
                </h2>
                <p class="text-base text-slate-500 mt-4">
                    We combine rigorous recruitment, top-tier compensation, and cutting-edge security so your offshore team performs at peak efficiency.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-card">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-lg mb-5">
                        01
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Top 3% Vetted Talent</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Every candidate undergoes multi-stage assessments: C1/C2 English proficiency, technical cognitive testing, background checks, and client culture interviews.
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-card">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-lg mb-5">
                        02
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Zero Lock-In Contracts</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        We earn your partnership month over month. Scale up or adjust headcount on flexible monthly terms as your business seasonality demands.
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-card">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-lg mb-5">
                        03
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Bank-Grade Data Security</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Clean-desk policies, enterprise endpoint encryption, restricted VPN tunnels, and mutual NDAs executed before any client credential or data exchange.
                    </p>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════════════
             SECTION 5: INTERACTIVE BOOKING PORTAL (THE WIZARD)
        ═══════════════════════════════════════════════════════════════════ -->
        <section id="booking" class="py-20 bg-gradient-to-b from-slate-100 via-white to-slate-50 border-y border-slate-200">
            <div class="mx-auto max-w-4xl px-4">

                <!-- Section Header -->
                <div class="text-center max-w-2xl mx-auto mb-10">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 border border-brand-200 px-3.5 py-1 text-xs font-bold text-brand-700 mb-3">
                        <span class="w-2 h-2 rounded-full bg-brand-600 animate-pulse"></span>
                        Client Booking Portal
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Schedule Your 30-Minute Discovery Call
                    </h2>
                    <p class="text-sm text-slate-500 mt-2">
                        Select your service requirements, choose a convenient time slot, and receive an instant calendar invite.
                    </p>
                </div>

                <!-- Step Progress Indicator -->
                <div id="progress-container" class="mb-8 flex items-center justify-center max-w-md mx-auto">
                    <div class="flex flex-col items-center gap-1.5">
                        <div id="dot-1" class="step-dot active">1</div>
                        <span id="label-1" class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Service</span>
                    </div>
                    <div id="line-1" class="flex-1 mx-3 h-0.5 bg-slate-200 transition-colors duration-300"></div>
                    <div class="flex flex-col items-center gap-1.5">
                        <div id="dot-2" class="step-dot pending">2</div>
                        <span id="label-2" class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Schedule</span>
                    </div>
                    <div id="line-2" class="flex-1 mx-3 h-0.5 bg-slate-200 transition-colors duration-300"></div>
                    <div class="flex flex-col items-center gap-1.5">
                        <div id="dot-3" class="step-dot pending">3</div>
                        <span id="label-3" class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Details</span>
                    </div>
                </div>

                <!-- STEP 1: SERVICE & HEADCOUNT -->
                <div id="step-1" class="bg-white rounded-2xl border border-slate-200 shadow-card-md overflow-hidden">
                    <div class="px-6 pt-6 pb-4 border-b border-slate-100">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-brand-600 mb-1">Step 1 of 3</p>
                        <h3 class="text-xl font-bold text-slate-900">Select Your Service & Team Scale</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Choose the BPO service that best matches your operational requirements.</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-600 mb-2">BPO Service Category <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="service-options">
                                <!-- Injected by JS -->
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-600 mb-2">Headcount Requirement <span class="text-red-500">*</span></label>
                            <div class="flex flex-wrap gap-3">
                                <button type="button" class="headcount-pill selected" onclick="selectHeadcount('1-5', this)">1–5 Agents</button>
                                <button type="button" class="headcount-pill" onclick="selectHeadcount('6-20', this)">6–20 Agents</button>
                                <button type="button" class="headcount-pill" onclick="selectHeadcount('20+', this)">20+ Enterprise</button>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/70 flex justify-end">
                        <button type="button" onclick="goToStep(2)" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm px-6 py-2.5 rounded-lg flex items-center gap-2 transition-all shadow-sm">
                            Continue to Schedule
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </div>
                </div>

                <!-- STEP 2: DATE, TIME & TIMEZONE -->
                <div id="step-2" class="hidden bg-white rounded-2xl border border-slate-200 shadow-card-md overflow-hidden">
                    <div class="px-6 pt-6 pb-4 border-b border-slate-100">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-brand-600 mb-1">Step 2 of 3</p>
                        <h3 class="text-xl font-bold text-slate-900">Choose Date, Time & Timezone</h3>
                        <p class="text-sm text-slate-500 mt-0.5">All consultations are 30 minutes. Monday – Friday availability.</p>
                    </div>
                    <div class="p-6">
                        <!-- Timezone Bar -->
                        <div class="mb-6">
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-600 mb-2">Your Timezone</label>
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button" class="tz-chip" onclick="setTimezone('America/New_York', this)">EST (New York)</button>
                                <button type="button" class="tz-chip" onclick="setTimezone('America/Los_Angeles', this)">PST (Los Angeles)</button>
                                <button type="button" class="tz-chip" onclick="setTimezone('Europe/London', this)">GMT (London)</button>
                                <button type="button" class="tz-chip active" onclick="setTimezone('Asia/Manila', this)">PHT (Manila)</button>
                                <select id="tz-dropdown" onchange="setTimezone(this.value)" class="text-xs border border-slate-200 rounded-md py-1.5 px-2 bg-white text-slate-700 font-medium">
                                    <option value="Asia/Manila">Asia/Manila</option>
                                    <option value="America/New_York">America/New_York</option>
                                    <option value="America/Los_Angeles">America/Los_Angeles</option>
                                    <option value="Europe/London">Europe/London</option>
                                    <option value="UTC">UTC</option>
                                    <option value="Asia/Singapore">Asia/Singapore</option>
                                    <option value="Australia/Sydney">Australia/Sydney</option>
                                </select>
                            </div>
                        </div>

                        <!-- Calendar & Time Slots -->
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                            <!-- Calendar (3 cols) -->
                            <div class="md:col-span-3">
                                <div class="flex items-center justify-between mb-3">
                                    <button type="button" onclick="changeMonth(-1)" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-600">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <span id="calendar-title" class="text-sm font-bold text-slate-800">Month 2026</span>
                                    <button type="button" onclick="changeMonth(1)" class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-600">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-7 text-center mb-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                    <div>Su</div><div>Mo</div><div>Tu</div><div>We</div><div>Th</div><div>Fr</div><div>Sa</div>
                                </div>
                                <div id="calendar-grid" class="grid grid-cols-7 gap-1">
                                    <!-- Calendar days injected by JS -->
                                </div>
                            </div>

                            <!-- Slots (2 cols) -->
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-600 mb-2">
                                    Available Slots <span id="selected-date-label" class="text-brand-600 normal-case font-medium"></span>
                                </label>
                                <div id="slots-grid" class="grid grid-cols-2 gap-2 max-h-56 overflow-y-auto pr-1">
                                    <!-- Time slots injected by JS -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/70 flex items-center justify-between">
                        <button type="button" onclick="goToStep(1)" class="border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm px-5 py-2.5 rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                            Back
                        </button>
                        <button type="button" onclick="goToStep(3)" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm px-6 py-2.5 rounded-lg flex items-center gap-2 transition-all shadow-sm">
                            Continue to Details
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </div>
                </div>

                <!-- STEP 3: CLIENT DETAILS -->
                <div id="step-3" class="hidden bg-white rounded-2xl border border-slate-200 shadow-card-md overflow-hidden">
                    <div class="px-6 pt-6 pb-4 border-b border-slate-100">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-brand-600 mb-1">Step 3 of 3</p>
                        <h3 class="text-xl font-bold text-slate-900">Enterprise Credentials & Scoping</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Strictly confidential and used only for consultation preparation.</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-600 mb-1">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" id="input-name" class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="e.g. Maria Gonzalez" value="John Doe">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-600 mb-1">Company Name <span class="text-red-500">*</span></label>
                                <input type="text" id="input-company" class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="e.g. Apex Global Solutions" value="Apex Enterprises">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-600 mb-1">Corporate Email <span class="text-red-500">*</span></label>
                                <input type="email" id="input-email" class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="john@apex.com" value="john@apexenterprises.com">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-600 mb-1">Phone / WhatsApp</label>
                                <input type="tel" id="input-phone" class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="+1 (555) 123-4567" value="+1 (555) 345-6789">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-600 mb-1">Operations Country / Region</label>
                            <input type="text" id="input-country" class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="e.g. United States, Australia, Singapore" value="United States">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-600 mb-1">Key Operational Challenges (Optional)</label>
                            <textarea id="input-challenges" rows="3" class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none" placeholder="Briefly describe your support volume, escalation bottlenecks, or SLA expectations...">Looking to scale 24/7 Tier 1 support team with dedicated English-speaking representatives.</textarea>
                        </div>

                        <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <input type="checkbox" id="input-nda" checked class="mt-0.5 h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                            <label for="input-nda" class="text-xs text-slate-600 cursor-pointer">
                                <strong class="text-slate-800">Request Mutual Non-Disclosure Agreement (NDA)</strong>
                                <p class="text-slate-400 mt-0.5">We will send a standard bilateral NDA for execution prior to the discovery call.</p>
                            </label>
                        </div>

                        <!-- Summary Card -->
                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                            <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Booking Summary</div>
                            <div class="grid grid-cols-2 gap-y-1.5 text-xs text-slate-600">
                                <span>Service:</span><strong id="summary-service" class="text-slate-900">Inbound / Outbound Customer Care</strong>
                                <span>Headcount:</span><strong id="summary-headcount" class="text-slate-900">1–5 Agents</strong>
                                <span>Date & Time:</span><strong id="summary-datetime" class="text-slate-900">Tomorrow at 10:00 AM (PHT)</strong>
                                <span>Duration:</span><strong class="text-slate-900">30 minutes (Confidential)</strong>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/70 flex items-center justify-between">
                        <button type="button" onclick="goToStep(2)" class="border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm px-5 py-2.5 rounded-lg flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                            Back
                        </button>
                        <button type="button" onclick="submitBooking()" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm px-6 py-2.5 rounded-lg flex items-center gap-2 transition-all shadow-sm">
                            Confirm Booking
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </div>
                </div>

                <!-- STEP 4: CONFIRMATION -->
                <div id="step-4" class="hidden max-w-xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-card-lg overflow-hidden p-8 text-center animate-fade-in">
                    <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="inline-block bg-brand-50 text-brand-700 border border-brand-200 text-xs font-mono font-bold px-3 py-1 rounded-full mb-3" id="confirm-ref">
                        G4CE-BK-89421
                    </span>
                    <h3 class="text-2xl font-extrabold text-slate-900 mb-2">Discovery Call Confirmed!</h3>
                    <p class="text-sm text-slate-500 max-w-md mx-auto mb-3">
                        A calendar invitation and Google Meet link have been prepared for <strong id="confirm-email" class="text-slate-800">your email</strong>.
                    </p>

                    <div id="company-notify-banner" class="mb-6 mx-auto max-w-md p-3 rounded-xl bg-blue-50 border border-blue-200 text-xs text-blue-900 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Checking email dispatch status...</span>
                    </div>

                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-left text-xs space-y-2 mb-6">
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="text-slate-500">Service</span>
                            <span id="confirm-service" class="font-bold text-slate-900">Customer Care</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="text-slate-500">Date & Time</span>
                            <span id="confirm-datetime" class="font-bold text-slate-900">Oct 20, 2026 - 10:00 AM PHT</span>
                        </div>
                        <div class="flex justify-between border-b border-slate-200 pb-2">
                            <span class="text-slate-500">Consultant Assigned</span>
                            <span class="font-bold text-slate-900">Maria Santos (Senior BPO Solutions)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Meeting Link</span>
                            <span class="font-mono font-bold text-brand-600">meet.google.com/g4c-scop-bpo</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="https://meet.google.com" target="_blank" class="bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs px-5 py-2.5 rounded-lg inline-flex items-center justify-center gap-2 transition-all">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Open Google Meet
                        </a>
                        <button onclick="downloadIcs()" class="border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs px-5 py-2.5 rounded-lg inline-flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Download .ICS File
                        </button>
                        <button onclick="goToStep(1)" class="border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs px-4 py-2.5 rounded-lg">
                            New Booking
                        </button>
                    </div>
                </div>

            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════════════
             SECTION 6: FAQ ACCORDION
        ═══════════════════════════════════════════════════════════════════ -->
        <section id="faq" class="py-24 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-xs font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200 px-3 py-1 rounded-full">
                    Frequently Asked Questions
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-4 tracking-tight">
                    Everything You Need to Know Before Outsourcing
                </h2>
            </div>

            <div class="space-y-4" id="faq-container">
                <!-- FAQ Item 1 -->
                <div class="border border-slate-200 rounded-xl bg-white overflow-hidden shadow-card">
                    <button type="button" onclick="toggleFaq(1)" class="w-full px-6 py-5 text-left font-bold text-slate-900 text-base flex justify-between items-center hover:bg-slate-50">
                        <span>How quickly can a dedicated team be deployed?</span>
                        <span id="faq-icon-1" class="text-xl text-slate-400 font-normal leading-none">+</span>
                    </button>
                    <div id="faq-answer-1" class="hidden px-6 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Individual specialists (1–5 headcount) can be deployed within <strong>5 to 7 business days</strong>. Custom pods (20+ agents) requiring tailored hardware or bespoke technical training typically launch within 14 business days.
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="border border-slate-200 rounded-xl bg-white overflow-hidden shadow-card">
                    <button type="button" onclick="toggleFaq(2)" class="w-full px-6 py-5 text-left font-bold text-slate-900 text-base flex justify-between items-center hover:bg-slate-50">
                        <span>What timezones do your consultants and teams support?</span>
                        <span id="faq-icon-2" class="text-xl text-slate-400 font-normal leading-none">+</span>
                    </button>
                    <div id="faq-answer-2" class="hidden px-6 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        We offer full 24/7/365 coverage across all international shifts including US Eastern (EST), US Pacific (PST), UK/European (GMT/BST), and Australian (AEST) business hours.
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="border border-slate-200 rounded-xl bg-white overflow-hidden shadow-card">
                    <button type="button" onclick="toggleFaq(3)" class="w-full px-6 py-5 text-left font-bold text-slate-900 text-base flex justify-between items-center hover:bg-slate-50">
                        <span>How do you protect sensitive company and customer data?</span>
                        <span id="faq-icon-3" class="text-xl text-slate-400 font-normal leading-none">+</span>
                    </button>
                    <div id="faq-answer-3" class="hidden px-6 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        We sign bilateral mutual NDAs prior to onboarding. Our workstations utilize SOC 2 certified Virtual Desktop Infrastructure (VDI), hardware TPM 2.0 encryption, disable external storage peripherals, and operate under strict ISO 27001 data governance protocols.
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="border border-slate-200 rounded-xl bg-white overflow-hidden shadow-card">
                    <button type="button" onclick="toggleFaq(4)" class="w-full px-6 py-5 text-left font-bold text-slate-900 text-base flex justify-between items-center hover:bg-slate-50">
                        <span>What is your pricing model?</span>
                        <span id="faq-icon-4" class="text-xl text-slate-400 font-normal leading-none">+</span>
                    </button>
                    <div id="faq-answer-4" class="hidden px-6 pb-5 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        We offer transparent monthly flat-rate billing per dedicated seat or tiered hourly billing for seasonal contact centers. There are zero hidden equipment, recruiter, or severance fees.
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══════════════════════════════════════════════════════════════════
             SECTION 7: CONTACT US
        ═══════════════════════════════════════════════════════════════════ -->
        <section id="contact" class="py-24 bg-slate-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                    <div>
                        <span class="text-xs font-extrabold uppercase tracking-widest text-brand-400 bg-brand-900/60 border border-brand-500/30 px-3 py-1 rounded-full">
                            Get In Touch
                        </span>
                        <h2 class="text-3xl sm:text-5xl font-extrabold mt-4 tracking-tight">
                            Have Questions? Speak Directly With Our Solutions Team
                        </h2>
                        <p class="mt-4 text-slate-400 text-base leading-relaxed">
                            Whether you're exploring outsourcing for the first time or evaluating a replacement partner, our senior operations consultants are ready to help.
                        </p>

                        <div class="mt-10 space-y-6 text-sm">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-slate-800 text-blue-400 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-white">Direct Email</div>
                                    <a href="mailto:solutions@g4ce.com" class="text-brand-400 hover:underline">solutions@g4ce.com</a>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-slate-800 text-emerald-400 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-white">North America Hotline</div>
                                    <div class="text-slate-300">+1 (800) 584-G4CE (Toll-Free)</div>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-slate-800 text-purple-400 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-white">Global Delivery Headquarters</div>
                                    <div class="text-slate-300">G4ce Tower, Bonifacio Global City, Metro Manila, Philippines</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form Card -->
                    <div class="bg-slate-800/90 border border-slate-700/80 rounded-2xl p-8 backdrop-blur-md">
                        <h3 class="text-xl font-bold text-white mb-2">Send an Inquiry</h3>
                        <p class="text-xs text-slate-400 mb-6">Receive an assessment and pricing estimate within 4 business hours.</p>

                        <form id="contact-form" onsubmit="handleContactSubmit(event)" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wide mb-1">Your Name <span class="text-red-400">*</span></label>
                                    <input type="text" id="contact-name" required class="w-full rounded-lg bg-slate-900/90 border border-slate-700 px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="Alex Rivera">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wide mb-1">Company <span class="text-red-400">*</span></label>
                                    <input type="text" id="contact-company" required class="w-full rounded-lg bg-slate-900/90 border border-slate-700 px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="Acme Logistics">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wide mb-1">Business Email <span class="text-red-400">*</span></label>
                                <input type="email" id="contact-email" required class="w-full rounded-lg bg-slate-900/90 border border-slate-700 px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="alex@acmelogistics.com">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wide mb-1">How can we help? <span class="text-red-400">*</span></label>
                                <textarea id="contact-message" rows="3" required class="w-full rounded-lg bg-slate-900/90 border border-slate-700 px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none" placeholder="Tell us about the roles, shift requirements, or operational bottlenecks..."></textarea>
                            </div>

                            <button type="submit" id="contact-submit-btn" class="w-full bg-brand-600 hover:bg-brand-500 text-white font-bold text-sm py-3 rounded-lg transition-all shadow-md flex items-center justify-center gap-2">
                                <span>Submit Inquiry &rarr;</span>
                            </button>
                            <div id="contact-status-box" class="hidden rounded-lg p-3 text-xs font-medium text-center"></div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- ═══════════════════════════════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════════════════════════════ -->
    <footer class="border-t border-slate-200 bg-white py-12 text-sm text-slate-500">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2 mb-3">
                        <img src="images/logo.webp" alt="G4ce BPO Services" class="h-9 w-auto object-contain" onerror="this.onerror=null; this.src='images/4a87ef88e3c8d652c89e01aa05b9f25a_G4CE-Logo.webp'">
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Enterprise outsourcing solutions, customer support pods, technical helpdesk, and dedicated offshore talent.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider mb-3">Services</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#services" class="hover:text-brand-600">Customer Care 24/7</a></li>
                        <li><a href="#services" class="hover:text-brand-600">Technical Helpdesk L1/L2</a></li>
                        <li><a href="#services" class="hover:text-brand-600">Back-Office Processing</a></li>
                        <li><a href="#services" class="hover:text-brand-600">Executive Virtual Assistants</a></li>
                        <li><a href="#services" class="hover:text-brand-600">Staff Augmentation</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider mb-3">Company</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#about" class="hover:text-brand-600">About G4ce</a></li>
                        <li><a href="#why-us" class="hover:text-brand-600">Why Choose Us</a></li>
                        <li><a href="#faq" class="hover:text-brand-600">FAQ & Process</a></li>
                        <li><a href="#booking" class="hover:text-brand-600">Book Discovery Call</a></li>
                        <li><a href="#contact" class="hover:text-brand-600">Global Offices</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider mb-3">Security & Legal</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" class="hover:text-brand-600">Mutual NDA Policy</a></li>
                        <li><a href="#" class="hover:text-brand-600">SOC 2 Type II Compliance</a></li>
                        <li><a href="#" class="hover:text-brand-600">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-brand-600">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-100 pt-6 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-400 gap-3">
                <p>&copy; 2026 G4ce BPO Services. All rights reserved.</p>
                <p>Built with enterprise grade security, compliance & 99.4% SLA adherence.</p>
            </div>
        </div>
    </footer>

    <!-- ═══════════════════════════════════════════════════════════════════
         INTERACTIVE CLIENT CONTROLLER (JS)
    ═══════════════════════════════════════════════════════════════════ -->
    <script>
        const services = [
            { id: 1, name: 'Inbound / Outbound Customer Care', desc: '24/7 omnichannel support, customer retention, and CX excellence.', icon: 'phone' },
            { id: 2, name: 'Technical Support & Helpdesk L1/L2', desc: 'Tier 1 & 2 technical resolution, SLA compliance, and escalation management.', icon: 'cpu' },
            { id: 3, name: 'Back-Office & Data Processing', desc: 'Accurate, secure data entry, verification, and quality auditing.', icon: 'clipboard' },
            { id: 4, name: 'Virtual Assistance & Executive Ops', desc: 'Remote executive support, inbox triage, scheduling, and admin operations.', icon: 'user' },
            { id: 5, name: 'Dedicated Team / Staff Augmentation', desc: 'Scalable dedicated teams integrated directly into your workflows.', icon: 'users' },
        ];

        let state = {
            step: 1,
            service: services[0],
            headcount: '1-5',
            timezone: 'Asia/Manila',
            year: 2026,
            month: 9, // October (0-indexed)
            selectedDate: '2026-10-20',
            selectedTime: '10:00 AM',
        };

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        function scrollToBooking(step = 1) {
            goToStep(step);
            const bookingEl = document.getElementById('booking');
            if (bookingEl) {
                bookingEl.scrollIntoView({ behavior: 'smooth' });
            }
        }

        function preselectService(serviceId) {
            selectService(serviceId);
            scrollToBooking(1);
        }

        function toggleFaq(id) {
            const ans = document.getElementById(`faq-answer-${id}`);
            const icon = document.getElementById(`faq-icon-${id}`);
            const isHidden = ans.classList.contains('hidden');
            ans.classList.toggle('hidden', !isHidden);
            icon.innerText = isHidden ? '−' : '+';
        }

        function handleContactSubmit(e) {
            e.preventDefault();
            const btn = document.getElementById('contact-submit-btn');
            const msg = document.getElementById('contact-success-msg');
            btn.innerText = 'Submitting...';
            btn.disabled = true;
            setTimeout(() => {
                btn.classList.add('hidden');
                msg.classList.remove('hidden');
            }, 800);
        }

        // Render Service Options in Wizard
        function renderServices() {
            const container = document.getElementById('service-options');
            if (!container) return;
            container.innerHTML = services.map(svc => `
                <div class="service-card ${state.service.id === svc.id ? 'selected' : ''}" onclick="selectService(${svc.id})">
                    <div class="flex items-start justify-between">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg ${state.service.id === svc.id ? 'bg-brand-100 text-brand-600' : 'bg-slate-100 text-slate-500'}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        ${state.service.id === svc.id ? '<span class="w-5 h-5 rounded-full bg-brand-600 text-white flex items-center justify-center text-xs font-bold">✓</span>' : ''}
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-800 leading-tight">${svc.name}</div>
                        <div class="text-xs text-slate-500 mt-1 leading-relaxed">${svc.desc}</div>
                    </div>
                </div>
            `).join('');
        }

        function selectService(id) {
            state.service = services.find(s => s.id === id) || services[0];
            renderServices();
            updateSummary();
        }

        function selectHeadcount(val, btn) {
            state.headcount = val;
            document.querySelectorAll('.headcount-pill').forEach(b => b.classList.remove('selected'));
            if (btn) btn.classList.add('selected');
            updateSummary();
        }

        function setTimezone(tz, btn) {
            state.timezone = tz;
            const dropdown = document.getElementById('tz-dropdown');
            if (dropdown) dropdown.value = tz;
            document.querySelectorAll('.tz-chip').forEach(b => b.classList.remove('active'));
            if (btn) btn.classList.add('active');
            updateSummary();
        }

        function changeMonth(delta) {
            state.month += delta;
            if (state.month > 11) { state.month = 0; state.year++; }
            if (state.month < 0) { state.month = 11; state.year--; }
            renderCalendar();
        }

        function renderCalendar() {
            const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
            const calTitle = document.getElementById('calendar-title');
            if (!calTitle) return;
            calTitle.innerText = `${months[state.month]} ${state.year}`;

            const firstDay = new Date(state.year, state.month, 1).getDay();
            const daysInMonth = new Date(state.year, state.month + 1, 0).getDate();
            const grid = document.getElementById('calendar-grid');
            grid.innerHTML = '';

            for (let i = 0; i < firstDay; i++) {
                grid.innerHTML += '<div></div>';
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const dayOfWeek = new Date(state.year, state.month, d).getDay();
                const isWeekend = (dayOfWeek === 0 || dayOfWeek === 6);
                const dateStr = `${state.year}-${String(state.month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                const isSelected = (state.selectedDate === dateStr);

                grid.innerHTML += `
                    <div class="flex justify-center">
                        <button type="button" 
                            class="cal-day ${isWeekend ? 'disabled' : ''} ${isSelected ? 'selected' : ''}" 
                            ${isWeekend ? 'disabled' : `onclick="selectDate('${dateStr}')"`}>
                            ${d}
                        </button>
                    </div>
                `;
            }
            renderSlots();
        }

        function selectDate(dateStr) {
            state.selectedDate = dateStr;
            renderCalendar();
            updateSummary();
        }

        function renderSlots() {
            const dateObj = new Date(state.selectedDate);
            const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            const dateLabel = document.getElementById('selected-date-label');
            if (dateLabel) {
                dateLabel.innerText = `— ${months[dateObj.getMonth()]} ${dateObj.getDate()}`;
            }

            const slots = [
                '09:00 AM', '09:30 AM', '10:00 AM', '10:30 AM',
                '11:00 AM', '01:30 PM', '02:00 PM', '02:30 PM',
                '03:00 PM', '03:30 PM', '04:00 PM', '04:30 PM'
            ];

            const grid = document.getElementById('slots-grid');
            if (!grid) return;
            grid.innerHTML = slots.map(slot => `
                <button type="button" class="time-slot ${state.selectedTime === slot ? 'selected' : ''}" onclick="selectSlot('${slot}')">
                    ${slot}
                </button>
            `).join('');
        }

        function selectSlot(slot) {
            state.selectedTime = slot;
            renderSlots();
            updateSummary();
        }

        function updateSummary() {
            const dateObj = new Date(state.selectedDate);
            const formattedDate = dateObj.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
            const sService = document.getElementById('summary-service');
            const sHeadcount = document.getElementById('summary-headcount');
            const sDatetime = document.getElementById('summary-datetime');

            if (sService) sService.innerText = state.service.name;
            if (sHeadcount) sHeadcount.innerText = `${state.headcount} Agents`;
            if (sDatetime) sDatetime.innerText = `${formattedDate} at ${state.selectedTime} (${state.timezone.split('/')[1] || state.timezone})`;
        }

        function goToStep(step) {
            state.step = step;
            [1, 2, 3, 4].forEach(s => {
                const el = document.getElementById(`step-${s}`);
                if (el) el.classList.toggle('hidden', s !== step);
            });

            const progressContainer = document.getElementById('progress-container');
            if (progressContainer) {
                if (step === 4) {
                    progressContainer.classList.add('hidden');
                } else {
                    progressContainer.classList.remove('hidden');
                    [1, 2, 3].forEach(s => {
                        const dot = document.getElementById(`dot-${s}`);
                        const lbl = document.getElementById(`label-${s}`);
                        if (dot) dot.className = 'step-dot ' + (s === step ? 'active' : (s < step ? 'completed' : 'pending'));
                        if (lbl) lbl.className = 'text-[11px] font-bold uppercase tracking-wider ' + (s === step ? 'text-brand-600' : (s < step ? 'text-slate-600' : 'text-slate-400'));
                        if (dot) {
                            if (s < step) dot.innerHTML = '✓'; else dot.innerHTML = s;
                        }
                    });
                    const l1 = document.getElementById('line-1');
                    const l2 = document.getElementById('line-2');
                    if (l1) l1.className = 'flex-1 mx-3 h-0.5 transition-colors duration-300 ' + (step > 1 ? 'bg-brand-600' : 'bg-slate-200');
                    if (l2) l2.className = 'flex-1 mx-3 h-0.5 transition-colors duration-300 ' + (step > 2 ? 'bg-brand-600' : 'bg-slate-200');
                }
            }
        }

        async function submitBooking() {
            const submitBtn = document.querySelector('#step-3 button[onclick="submitBooking()"]');
            const originalContent = submitBtn ? submitBtn.innerHTML : 'Confirm Booking';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Dispatching Notification...
                `;
            }

            const name = document.getElementById('input-name')?.value?.trim() || 'Enterprise Lead';
            const company = document.getElementById('input-company')?.value?.trim() || 'Apex Enterprises';
            const emailInput = document.getElementById('input-email');
            const email = (emailInput && emailInput.value?.trim()) ? emailInput.value.trim() : 'client@enterprise.com';
            const phone = document.getElementById('input-phone')?.value?.trim() || 'N/A';
            const country = document.getElementById('input-country')?.value?.trim() || 'Global';
            const challenges = document.getElementById('input-challenges')?.value?.trim() || 'None specified';
            const nda = document.getElementById('input-nda')?.checked || false;

            const payload = {
                client_name: name,
                company_name: company,
                client_email: email,
                client_phone: phone,
                operations_country: country,
                operational_challenges: challenges,
                nda_requested: nda,
                service_id: state.service.id,
                service_name: state.service.name,
                headcount_range: state.headcount,
                scheduled_date: state.selectedDate,
                scheduled_time: state.selectedTime,
                client_timezone: state.timezone
            };

            let refCode = 'G4CE-BK-' + Math.floor(10000 + Math.random() * 90000);

            try {
                const response = await fetch('api_booking.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const resData = await response.json();
                if (resData && resData.reference_code) {
                    refCode = resData.reference_code;
                }

                // Update notification banner with actual SMTP delivery status
                const banner = document.getElementById('company-notify-banner');
                if (banner) {
                    if (resData && resData.company_notified) {
                        banner.className = 'mb-6 mx-auto max-w-md p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 flex items-center justify-center gap-2';
                        banner.innerHTML = `
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span>Email successfully sent to G4ce inbox: <strong class="text-emerald-950 font-bold">${resData.company_email || 'scholargrantgatid@gmail.com'}</strong></span>
                        `;
                    } else {
                        banner.className = 'mb-6 mx-auto max-w-md p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 flex items-start justify-center gap-2.5 text-left';
                        banner.innerHTML = `
                            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div>
                                <span class="font-bold text-amber-950">Booking saved in system!</span>
                                <span class="text-amber-800 block text-[11px] mt-0.5">Note: Email not yet sent to <strong>${resData?.company_email || 'scholargrantgatid@gmail.com'}</strong> because <strong>MAIL_PASSWORD</strong> in .env is not yet filled out.</span>
                            </div>
                        `;
                    }
                }
            } catch (err) {
                console.warn('Booking API call completed with client fallback:', err);
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                }
            }

            const cRef = document.getElementById('confirm-ref');
            const cEmail = document.getElementById('confirm-email');
            const cService = document.getElementById('confirm-service');
            const cDatetime = document.getElementById('confirm-datetime');

            if (cRef) cRef.innerText = refCode;
            if (cEmail) cEmail.innerText = email;
            if (cService) cService.innerText = state.service.name;
            if (cDatetime) cDatetime.innerText = `${state.selectedDate} at ${state.selectedTime} (${state.timezone})`;
            
            goToStep(4);
        }

        function downloadIcs() {
            const refEl = document.getElementById('confirm-ref');
            const refCode = refEl ? refEl.innerText : 'G4CE-BK-89421';
            const icsContent = 
`BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//G4ce BPO Services//Discovery Call//EN
CALSCALE:GREGORIAN
METHOD:REQUEST
BEGIN:VEVENT
UID:${refCode}@g4ce.com
SUMMARY:G4ce BPO Discovery Call - ${state.service.name}
DESCRIPTION:Discovery & Scoping Call with G4ce BPO Solutions Consultant.\\nJoin Google Meet: https://meet.google.com/g4c-scop-bpo
LOCATION:https://meet.google.com/g4c-scop-bpo
STATUS:CONFIRMED
BEGIN:VALARM
TRIGGER:-PT15M
ACTION:DISPLAY
DESCRIPTION:Reminder: G4ce Discovery Call in 15 minutes
END:VALARM
END:VEVENT
END:VCALENDAR`;

            const blob = new Blob([icsContent], { type: 'text/calendar;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.setAttribute('download', `${refCode}.ics`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Active Navbar Link Spy
        window.addEventListener('scroll', () => {
            const sections = ['home', 'services', 'about', 'why-us', 'faq', 'contact'];
            const scrollPos = window.scrollY + 100;

            sections.forEach(secId => {
                const el = document.getElementById(secId);
                const navLink = document.querySelector(`nav a[href="#${secId}"]`);
                if (el && navLink) {
                    const top = el.offsetTop;
                    const height = el.offsetHeight;
                    if (scrollPos >= top && scrollPos < top + height) {
                        document.querySelectorAll('nav a').forEach(a => a.classList.remove('active'));
                        navLink.classList.add('active');
                    }
                }
            });
        });

        // Contact Form Submission Handler
        async function handleContactSubmit(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('contact-submit-btn');
            const statusBox = document.getElementById('contact-status-box');
            const originalContent = submitBtn ? submitBtn.innerHTML : '<span>Submit Inquiry &rarr;</span>';

            const name = document.getElementById('contact-name')?.value?.trim();
            const company = document.getElementById('contact-company')?.value?.trim();
            const email = document.getElementById('contact-email')?.value?.trim();
            const message = document.getElementById('contact-message')?.value?.trim();

            if (!name || !email || !message) {
                alert('Please fill in your Name, Business Email, and Message.');
                return;
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Dispatching to G4ce Team...</span>
                `;
            }

            try {
                const response = await fetch('api_contact.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, company, email, message })
                });

                const data = await response.json();

                if (statusBox) {
                    statusBox.classList.remove('hidden');
                    if (data && data.company_notified) {
                        statusBox.className = 'rounded-lg p-3.5 text-xs font-semibold text-center bg-emerald-500/10 border border-emerald-500/30 text-emerald-300';
                        statusBox.innerHTML = `✓ Inquiry successfully sent! An email alert was dispatched to <strong>scholargrantgatid@gmail.com</strong>. A solutions consultant will respond shortly.`;
                    } else {
                        statusBox.className = 'rounded-lg p-3.5 text-xs font-semibold text-center bg-emerald-500/10 border border-emerald-500/30 text-emerald-300';
                        statusBox.innerHTML = `✓ Inquiry received! Our operations team has logged your request and will contact you at ${email}.`;
                    }
                }

                // Reset inputs
                const form = document.getElementById('contact-form');
                if (form) form.reset();

            } catch (err) {
                console.error('Contact submit error:', err);
                if (statusBox) {
                    statusBox.classList.remove('hidden');
                    statusBox.className = 'rounded-lg p-3.5 text-xs font-semibold text-center bg-emerald-500/10 border border-emerald-500/30 text-emerald-300';
                    statusBox.innerHTML = `✓ Inquiry received! Our solutions team will reach out shortly.`;
                }
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalContent;
                }
            }
        }

        // Initialize on load
        renderServices();
        renderCalendar();
        updateSummary();
    </script>

    <!-- ═════════════════════════════════════════════════════════════════════
         FLOATING AI FAQ ASSISTANT (LOWER RIGHT SIDE) - POWERED BY GEMINI
    ═════════════════════════════════════════════════════════════════════ -->
    <div id="faq-widget-root" class="fixed bottom-6 right-6 z-[9999] flex flex-col items-end">
        
        <!-- Floating Chat Window -->
        <div id="faq-chat-window" class="hidden mb-3 w-[calc(100vw-2rem)] sm:w-[410px] h-[580px] max-h-[82vh] bg-white rounded-2xl shadow-2xl border border-slate-200/90 flex flex-col overflow-hidden transition-all duration-300 transform origin-bottom-right">
            
            <!-- Window Header -->
            <div class="bg-gradient-to-r from-slate-900 via-brand-950 to-brand-900 text-white px-5 py-4 flex items-center justify-between border-b border-brand-800/40 select-none">
                <div class="flex items-center gap-3">
                    <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-tr from-brand-500 to-indigo-500 text-white shadow-md">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z"/>
                        </svg>
                        <span class="absolute -bottom-0.5 -right-0.5 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500 border border-slate-900"></span>
                        </span>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white flex items-center gap-1.5">
                            G4ce AI Assistant
                            <span class="text-[10px] font-semibold tracking-wide uppercase px-1.5 py-0.5 rounded-full bg-brand-500/30 text-brand-200 border border-brand-400/30">Gemini</span>
                        </h3>
                        <p class="text-[11px] text-slate-300 flex items-center gap-1">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Online & Ready to Help
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-1">
                    <button type="button" onclick="faqResetChat()" title="Reset conversation" class="p-1.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                        </svg>
                    </button>
                    <button type="button" onclick="toggleFaqChat()" title="Close FAQ" class="p-1.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/10 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Chat Stream Body -->
            <div id="faq-chat-messages" class="flex-1 overflow-y-auto px-4 py-4 space-y-4 bg-slate-50/70 text-sm">
                <!-- Quick FAQ Chips Header -->
                <div class="bg-white p-3 rounded-xl border border-slate-200/80 shadow-xs">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>
                        </svg>
                        Frequently Asked Questions
                    </p>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" onclick="faqSendQuickQuestion('What BPO services does G4ce provide?')" class="text-left text-xs bg-slate-100 hover:bg-brand-50 hover:text-brand-700 hover:border-brand-300 text-slate-700 px-2.5 py-1.5 rounded-lg border border-slate-200 transition-all duration-150">
                            What services do you provide?
                        </button>
                        <button type="button" onclick="faqSendQuickQuestion('How does the 30-min discovery call work?')" class="text-left text-xs bg-slate-100 hover:bg-brand-50 hover:text-brand-700 hover:border-brand-300 text-slate-700 px-2.5 py-1.5 rounded-lg border border-slate-200 transition-all duration-150">
                            How does discovery call work?
                        </button>
                        <button type="button" onclick="faqSendQuickQuestion('Can we sign an NDA before our call?')" class="text-left text-xs bg-slate-100 hover:bg-brand-50 hover:text-brand-700 hover:border-brand-300 text-slate-700 px-2.5 py-1.5 rounded-lg border border-slate-200 transition-all duration-150">
                            Can we sign an NDA?
                        </button>
                        <button type="button" onclick="faqSendQuickQuestion('What team sizes can I hire?')" class="text-left text-xs bg-slate-100 hover:bg-brand-50 hover:text-brand-700 hover:border-brand-300 text-slate-700 px-2.5 py-1.5 rounded-lg border border-slate-200 transition-all duration-150">
                            What team sizes can I hire?
                        </button>
                        <button type="button" onclick="faqSendQuickQuestion('What timezones do your teams support?')" class="text-left text-xs bg-slate-100 hover:bg-brand-50 hover:text-brand-700 hover:border-brand-300 text-slate-700 px-2.5 py-1.5 rounded-lg border border-slate-200 transition-all duration-150">
                            What timezones do you support?
                        </button>
                    </div>
                </div>

                <!-- Initial AI Welcome -->
                <div class="flex items-start gap-2.5 max-w-[90%]">
                    <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-brand-600 to-indigo-600 flex items-center justify-center text-white shrink-0 mt-0.5 shadow-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                        </svg>
                    </div>
                    <div class="bg-white border border-slate-200/90 rounded-2xl rounded-tl-sm px-4 py-3 text-slate-800 shadow-xs leading-relaxed text-xs sm:text-sm">
                        <p class="text-slate-700">Hello! 👋 I'm your <strong>G4ce AI Assistant</strong>, powered by Google Gemini. How can I help you today?</p>
                        <p class="text-slate-500 mt-2 text-xs">Feel free to tap any of the popular questions above or ask me anything directly about our BPO services, discovery call, and staffing models.</p>
                        <div class="mt-2 pt-1 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400">
                            <span>G4ce AI</span>
                            <span>Just now</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call-to-action Banner inside Chat -->
            <div class="px-4 py-2 bg-brand-50/70 border-t border-brand-100/60 flex items-center justify-between">
                <span class="text-[11px] text-brand-800 font-medium">Ready to talk to a consultant?</span>
                <a href="#booking" onclick="toggleFaqChat(); scrollToBooking(1);" class="text-xs font-semibold text-brand-600 hover:text-brand-800 hover:underline flex items-center gap-1">
                    Book 30-min Call &rarr;
                </a>
            </div>

            <!-- Chat Input Footer -->
            <div class="p-3 bg-white border-t border-slate-200">
                <form id="faq-form" onsubmit="faqHandleSubmit(event)" class="flex items-center gap-2">
                    <input
                        type="text"
                        id="faq-input-field"
                        placeholder="Ask anything about G4ce services..."
                        class="flex-1 bg-slate-100 border border-slate-200 rounded-xl px-3.5 py-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all"
                        autocomplete="off"
                    />
                    <button
                        type="submit"
                        id="faq-send-btn"
                        class="h-9 px-3.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white rounded-xl flex items-center justify-center transition-colors disabled:opacity-50"
                        title="Send message"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                        </svg>
                    </button>
                </form>
                <p class="text-[10px] text-slate-400 text-center mt-1.5">
                    Powered by Gemini AI &bull; Answers based on G4ce BPO knowledge
                </p>
            </div>
        </div>

        <!-- Floating Launcher Row -->
        <div class="flex items-center gap-3">
            <!-- Floating Text Pill -->
            <div
                id="faq-pill-hint"
                onclick="toggleFaqChat()"
                class="hidden md:flex items-center gap-2 bg-white/95 backdrop-blur-md px-3.5 py-2 rounded-full shadow-lg border border-slate-200 text-xs font-semibold text-slate-700 cursor-pointer hover:border-brand-400 hover:text-brand-600 transition-all duration-200 group"
            >
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span>Have questions? <strong class="text-brand-600 group-hover:underline">Ask AI FAQ</strong></span>
            </div>

            <!-- Floating Action Button -->
            <button
                type="button"
                id="faq-floating-btn"
                onclick="toggleFaqChat()"
                aria-label="Open AI FAQ Assistant"
                class="relative flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-tr from-brand-600 via-brand-500 to-indigo-600 text-white shadow-xl hover:shadow-brand-500/30 hover:scale-105 active:scale-95 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-brand-500/30"
            >
                <!-- Chat Icon (Closed) -->
                <span id="faq-icon-chat" class="flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>
                    </svg>
                </span>

                <!-- Close Icon (Open) -->
                <span id="faq-icon-close" class="hidden items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </span>

                <!-- Notification Badge -->
                <span id="faq-badge" class="absolute -top-1 -right-1 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-brand-500 border-2 border-white items-center justify-center text-[8px] font-bold text-white">AI</span>
                </span>
            </button>
        </div>
    </div>

    <!-- FAQ Widget Logic & API Communication -->
    <script>
        const faqState = {
            isOpen: false,
            isLoading: false,
            history: []
        };

        function toggleFaqChat() {
            faqState.isOpen = !faqState.isOpen;
            const win = document.getElementById('faq-chat-window');
            const iconChat = document.getElementById('faq-icon-chat');
            const iconClose = document.getElementById('faq-icon-close');
            const badge = document.getElementById('faq-badge');
            const pill = document.getElementById('faq-pill-hint');

            if (faqState.isOpen) {
                win.classList.remove('hidden');
                iconChat.classList.add('hidden');
                iconClose.classList.remove('hidden');
                iconClose.classList.add('flex');
                if (badge) badge.classList.add('hidden');
                if (pill) pill.classList.add('hidden');
                setTimeout(() => {
                    const input = document.getElementById('faq-input-field');
                    if (input) input.focus();
                    faqScrollToBottom();
                }, 100);
            } else {
                win.classList.add('hidden');
                iconChat.classList.remove('hidden');
                iconClose.classList.add('hidden');
                iconClose.classList.remove('flex');
                if (badge) badge.classList.remove('hidden');
                if (pill) pill.classList.remove('hidden');
            }
        }

        // Close when clicking outside or pressing Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && faqState.isOpen) {
                toggleFaqChat();
            }
        });

        function faqScrollToBottom() {
            const container = document.getElementById('faq-chat-messages');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }

        function faqFormatMarkdown(text) {
            if (!text) return '';
            // Escape HTML
            let safe = text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');

            // Bold **text**
            safe = safe.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

            // Bullet points
            safe = safe.replace(/^[•\-\*]\s+(.*)$/gm, '<li class="ml-4 list-disc">$1</li>');

            // Line breaks
            safe = safe.replace(/\n/g, '<br>');

            return safe;
        }

        function faqAddUserMessage(text) {
            const container = document.getElementById('faq-chat-messages');
            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            const div = document.createElement('div');
            div.className = 'flex items-start justify-end gap-2 max-w-[85%] ml-auto animate-fade-in';
            div.innerHTML = `
                <div class="bg-brand-600 text-white rounded-2xl rounded-tr-sm px-4 py-2.5 shadow-xs text-xs sm:text-sm leading-relaxed">
                    <p class="whitespace-pre-line">${text.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>
                    <span class="block text-right text-[10px] text-brand-200 mt-1">${time}</span>
                </div>
            `;
            container.appendChild(div);
            faqScrollToBottom();
        }

        function faqAddAssistantMessage(text) {
            const container = document.getElementById('faq-chat-messages');
            const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const formatted = faqFormatMarkdown(text);

            const div = document.createElement('div');
            div.className = 'flex items-start gap-2.5 max-w-[90%] animate-fade-in';
            div.innerHTML = `
                <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-brand-600 to-indigo-600 flex items-center justify-center text-white shrink-0 mt-0.5 shadow-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    </svg>
                </div>
                <div class="bg-white border border-slate-200/90 rounded-2xl rounded-tl-sm px-4 py-3 text-slate-800 shadow-xs leading-relaxed text-xs sm:text-sm">
                    <div class="text-slate-700 leading-relaxed text-xs sm:text-sm">${formatted}</div>
                    <div class="mt-2 pt-1 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400">
                        <span>G4ce AI</span>
                        <span>${time}</span>
                    </div>
                </div>
            `;
            container.appendChild(div);
            faqScrollToBottom();
        }

        function faqShowLoadingIndicator() {
            const container = document.getElementById('faq-chat-messages');
            const div = document.createElement('div');
            div.id = 'faq-loading-indicator';
            div.className = 'flex items-start gap-2.5 max-w-[85%] animate-fade-in';
            div.innerHTML = `
                <div class="w-7 h-7 rounded-lg bg-brand-600 flex items-center justify-center text-white shrink-0 mt-0.5">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-sm px-4 py-3 shadow-xs">
                    <div class="flex items-center gap-1.5 py-1">
                        <span class="w-2 h-2 rounded-full bg-brand-500 animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-2 h-2 rounded-full bg-brand-500 animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-2 h-2 rounded-full bg-brand-500 animate-bounce" style="animation-delay: 300ms"></span>
                        <span class="text-xs text-slate-400 ml-1.5">Thinking...</span>
                    </div>
                </div>
            `;
            container.appendChild(div);
            faqScrollToBottom();
        }

        function faqHideLoadingIndicator() {
            const el = document.getElementById('faq-loading-indicator');
            if (el) el.remove();
        }

        async function faqSubmitQuery(text) {
            if (!text || faqState.isLoading) return;

            faqAddUserMessage(text);
            faqState.history.push({ role: 'user', text: text });

            const inputField = document.getElementById('faq-input-field');
            const sendBtn = document.getElementById('faq-send-btn');
            if (inputField) inputField.value = '';
            if (sendBtn) sendBtn.disabled = true;

            faqState.isLoading = true;
            faqShowLoadingIndicator();

            try {
                // Call api_faq.php directly
                const response = await fetch('api_faq.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        message: text,
                        history: faqState.history
                    })
                });

                const data = await response.json();
                faqHideLoadingIndicator();

                if (data && data.reply) {
                    faqAddAssistantMessage(data.reply);
                    faqState.history.push({ role: 'assistant', text: data.reply });
                } else {
                    faqAddAssistantMessage("Thank you for your question! For specific team sizing or custom SLAs, please feel free to book a 30-minute discovery call using our booking portal.");
                }
            } catch (err) {
                console.error('FAQ request error:', err);
                faqHideLoadingIndicator();
                faqAddAssistantMessage("I'm here to help! G4ce BPO offers Customer Care, Technical Support, Back-Office Processing, and Dedicated Teams. You can schedule a call anytime via the booking portal on this page.");
            } finally {
                faqState.isLoading = false;
                if (sendBtn) sendBtn.disabled = false;
                if (inputField) inputField.focus();
            }
        }

        function faqSendQuickQuestion(q) {
            if (!faqState.isOpen) {
                toggleFaqChat();
            }
            faqSubmitQuery(q);
        }

        function faqHandleSubmit(e) {
            e.preventDefault();
            const inputField = document.getElementById('faq-input-field');
            if (!inputField) return;
            const val = inputField.value.trim();
            if (val) {
                faqSubmitQuery(val);
            }
        }

        function faqResetChat() {
            faqState.history = [];
            const container = document.getElementById('faq-chat-messages');
            if (!container) return;
            
            // Keep the quick questions header & welcome message
            const chips = container.firstElementChild;
            const welcome = container.children[1];
            container.innerHTML = '';
            if (chips) container.appendChild(chips);
            if (welcome) container.appendChild(welcome);

            faqAddAssistantMessage("Chat has been reset! What questions can I answer for you about G4ce BPO Services?");
            const inputField = document.getElementById('faq-input-field');
            if (inputField) inputField.value = '';
        }
    </script>
</body>
</html>
