<x-layouts.app title="Book a Discovery Call">

    {{-- Hero Section --}}
    <section class="relative bg-white border-b border-slate-200/80">
        <div class="mx-auto max-w-4xl px-4 pt-16 pb-14 sm:px-6 sm:pt-20 sm:pb-16 text-center">

            <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
                Schedule Your Discovery Call
            </h1>

            <p class="mt-4 text-base leading-relaxed text-slate-600 max-w-2xl mx-auto">
                Connect directly with a G4ce Solutions Consultant for a confidential 30-minute operational scoping session. We'll map your staffing requirements and design a dedicated team model.
            </p>

            {{-- Enterprise Trust Badges --}}
            <div class="mt-10 flex flex-wrap justify-center items-center gap-y-3 gap-x-8 text-xs font-medium text-slate-600">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>NDA Available Upon Request</span>
                </div>

                <div class="hidden sm:block text-slate-300">&bull;</div>

                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Instant Calendar & Meeting Invite</span>
                </div>

                <div class="hidden sm:block text-slate-300">&bull;</div>

                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>30-Minute Confidential Consultation</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Interactive Booking Wizard Surface --}}
    <section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        @livewire('booking-wizard')
    </section>

</x-layouts.app>
