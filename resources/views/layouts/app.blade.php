<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO --}}
    <title>{{ isset($title) ? $title . ' — ' : '' }}G4ce BPO Services | Book a Discovery Call</title>
    <meta name="description" content="Schedule a confidential 30-minute discovery consultation with a G4ce BPO Solutions Consultant. Explore dedicated outsourcing teams tailored to your enterprise operations.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="Book a Consultation — G4ce BPO Services">
    <meta property="og:description" content="Partner with G4ce for world-class BPO solutions. Schedule your 30-minute discovery call now.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%230267D2'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='central' text-anchor='middle' font-family='Inter,sans-serif' font-weight='700' font-size='14' fill='white'%3EG4%3C/text%3E%3C/svg%3E">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased flex flex-col justify-between">

    {{-- Top Navigation Bar --}}
    <header class="fixed inset-x-0 top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 transition-opacity hover:opacity-90" aria-label="G4ce BPO Services Home">
                <img src="{{ asset('images/logo.webp') }}" alt="G4ce BPO Services" class="h-9 w-auto object-contain">
            </a>

            {{-- Nav links --}}
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                <a href="#services" class="hover:text-slate-900 transition-colors duration-150">Services</a>
                <a href="#about" class="hover:text-slate-900 transition-colors duration-150">Why G4ce</a>
                <a href="#contact" class="hover:text-slate-900 transition-colors duration-150">Contact</a>
                <a href="{{ route('booking.index') }}"
                   class="btn-primary text-xs px-4 py-2"
                   id="nav-book-cta">
                    Book a Call
                </a>
            </nav>
        </div>
    </header>

    {{-- Main content --}}
    <main class="flex-grow pt-16">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="mt-24 border-t border-slate-200/80 bg-white py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-6 text-center sm:text-left">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.webp') }}" alt="G4ce BPO Services" class="h-8 w-auto object-contain">
                    <span class="text-xs text-slate-400">| Enterprise BPO Solutions</span>
                </div>
                <p class="text-xs text-slate-500">
                    &copy; {{ date('Y') }} G4ce BPO Services. All rights reserved.
                    <span class="mx-2 text-slate-300">&middot;</span>
                    <a href="#" class="hover:text-slate-800 transition-colors">Privacy Policy</a>
                    <span class="mx-2 text-slate-300">&middot;</span>
                    <a href="#" class="hover:text-slate-800 transition-colors">Terms of Service</a>
                </p>
            </div>
        </div>
    </footer>

    {{-- Floating AI FAQ Assistant Widget --}}
    <livewire:faq-widget />

    @livewireScripts
</body>
</html>
