<div class="animate-fade-in">

    {{-- ════════════════════════════════════════════════════════════════════
         STEP PROGRESS INDICATOR
    ════════════════════════════════════════════════════════════════════ --}}
    @if($step < 4)
    <div class="mb-10 max-w-xl mx-auto" aria-label="Consultation Booking Steps">
        @php
            $steps = [
                1 => 'Service & Scope',
                2 => 'Date & Time',
                3 => 'Client Details',
            ];
        @endphp

        <div class="flex items-center justify-between relative">
            {{-- Background track line --}}
            <div class="absolute left-6 right-6 top-4 h-0.5 bg-slate-200 -z-0"></div>
            {{-- Active track progress line --}}
            <div class="absolute left-6 top-4 h-0.5 bg-brand-600 transition-all duration-300 -z-0"
                 style="width: {{ $step === 1 ? '0%' : ($step === 2 ? '50%' : '100%') }};"></div>

            @foreach($steps as $num => $label)
            <div class="flex flex-col items-center z-10">
                <button
                    type="button"
                    id="step-indicator-{{ $num }}"
                    class="step-dot
                        {{ $step === $num ? 'active' : ($step > $num ? 'completed' : 'pending') }}"
                    @if($step > $num) wire:click="$set('step', {{ $num }})" @endif
                    @if($step <= $num) disabled @endif
                >
                    @if($step > $num)
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        {{ $num }}
                    @endif
                </button>
                <span class="mt-2 text-xs font-semibold tracking-tight
                    {{ $step === $num ? 'text-slate-900' : ($step > $num ? 'text-slate-700' : 'text-slate-400') }}">
                    {{ $label }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════
         STEP 1 — Service Selection & Headcount
    ════════════════════════════════════════════════════════════════════ --}}
    @if($step === 1)
    <div class="animate-slide-up">
        <div class="card-elevated overflow-hidden">

            {{-- Card header --}}
            <div class="px-6 py-6 sm:px-8 border-b border-slate-100 bg-white">
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Select Your Service & Team Scale</h2>
                <p class="text-sm text-slate-500 mt-1">Choose the primary BPO service and required agent capacity for your operational needs.</p>
            </div>

            <div class="p-6 sm:p-8 space-y-8 bg-white">

                {{-- Service cards grid --}}
                <fieldset>
                    <legend class="label">BPO Service Category <span class="text-rose-500">*</span></legend>
                    @error('serviceId')
                    <p class="error-msg mb-3">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @php
                            $defaultServices = [
                                ['id'=>1,'name'=>'Inbound / Outbound Customer Care','description'=>'24/7 omnichannel customer support, customer success, retention, and CX.','icon'=>'phone'],
                                ['id'=>2,'name'=>'Technical Support & Helpdesk L1/L2','description'=>'Multi-tier technical troubleshooting, ticket triage, and incident resolution.','icon'=>'cpu-chip'],
                                ['id'=>3,'name'=>'Back-Office & Data Processing','description'=>'High-accuracy document verification, transaction processing, and data ops.','icon'=>'clipboard-document-list'],
                                ['id'=>4,'name'=>'Virtual Assistance & Executive Ops','description'=>'Dedicated administrative workflows, scheduling, billing, and research.','icon'=>'user-circle'],
                                ['id'=>5,'name'=>'Dedicated Team / Staff Augmentation','description'=>'Custom enterprise pods managed under your KPIs and operating tooling.','icon'=>'users'],
                            ];
                            $services = $this->services->count() ? $this->services : collect($defaultServices);
                        @endphp

                        @foreach($services as $index => $svc)
                        @php
                            $svcId    = is_array($svc) ? ($svc['id'] ?? ($index + 1)) : $svc->id;
                            $svcName  = is_array($svc) ? $svc['name'] : $svc->name;
                            $svcDesc  = is_array($svc) ? $svc['description'] : $svc->description;
                            $svcIcon  = is_array($svc) ? $svc['icon'] : $svc->icon;
                        @endphp
                        <button
                            type="button"
                            id="service-card-{{ $svcId }}"
                            wire:click="$set('serviceId', {{ $svcId }})"
                            class="service-card {{ $serviceId === $svcId ? 'selected' : '' }}"
                        >
                            <div class="flex items-start gap-3.5">
                                {{-- Icon badge --}}
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl flex-shrink-0
                                    {{ $serviceId === $svcId ? 'bg-brand-600 text-white shadow-subtle' : 'bg-slate-100 text-slate-600' }}
                                    transition-colors duration-200">
                                    @if($svcIcon === 'phone')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                    @elseif($svcIcon === 'cpu-chip')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9v-9z"/></svg>
                                    @elseif($svcIcon === 'clipboard-document-list')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                                    @elseif($svcIcon === 'user-circle')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    @else
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                    @endif
                                </div>

                                <div class="flex-1 pr-6">
                                    <p class="text-sm font-semibold text-slate-900 leading-snug">{{ $svcName }}</p>
                                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $svcDesc }}</p>
                                </div>
                            </div>

                            @if($serviceId === $svcId)
                            <div class="absolute top-4 right-4">
                                <div class="flex h-5 w-5 items-center justify-center rounded-full bg-brand-600 text-white shadow-subtle">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </fieldset>

                {{-- Headcount selector --}}
                <fieldset class="pt-2">
                    <legend class="label">Headcount Requirement <span class="text-rose-500">*</span></legend>
                    @error('headcountRange')
                    <p class="error-msg mb-3">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                    @enderror

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach(['1-5' => '1–5 Agents', '6-20' => '6–20 Agents', '20+' => '20+ Enterprise Pod'] as $value => $label)
                        <button
                            type="button"
                            id="headcount-{{ str_replace(['+', '-'], ['plus', 'dash'], $value) }}"
                            wire:click="$set('headcountRange', '{{ $value }}')"
                            class="headcount-pill text-center {{ $headcountRange === $value ? 'selected' : '' }}"
                        >
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </fieldset>

            </div>

            {{-- Card footer --}}
            <div class="px-6 py-5 sm:px-8 border-t border-slate-100 bg-slate-50/70 flex justify-end">
                <button type="button" id="step1-next-btn" wire:click="nextStep" class="btn-primary">
                    <span>Continue to Schedule</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </div>

        </div>
    </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════
         STEP 2 — Calendar, Time Slots & Timezone
    ════════════════════════════════════════════════════════════════════ --}}
    @if($step === 2)
    <div class="animate-slide-up">
        <div class="card-elevated overflow-hidden">

            {{-- Card header --}}
            <div class="px-6 py-6 sm:px-8 border-b border-slate-100 bg-white">
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Choose Date & Consultation Time</h2>
                <p class="text-sm text-slate-500 mt-1">Sessions run for 30 minutes. Time slots are shown in your local timezone.</p>
            </div>

            <div class="p-6 sm:p-8 space-y-6 bg-white">

                {{-- Timezone bar --}}
                <div class="rounded-xl bg-slate-50 border border-slate-200/80 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Your Timezone</span>
                            <p class="text-xs text-slate-500">Auto-detected or adjust below</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            @foreach(['EST' => 'America/New_York', 'PST' => 'America/Los_Angeles', 'GMT' => 'Europe/London', 'PHT' => 'Asia/Manila'] as $abbr => $tz)
                            <button
                                type="button"
                                id="tz-{{ strtolower($abbr) }}"
                                wire:click="setTimezone('{{ $tz }}')"
                                class="tz-chip {{ $clientTimezone === $tz ? 'active' : '' }}"
                            >{{ $abbr }}</button>
                            @endforeach

                            <select
                                id="timezone-select"
                                wire:model.live="clientTimezone"
                                wire:change="setTimezone($event.target.value)"
                                class="input text-xs py-1.5 w-auto max-w-[200px]"
                            >
                                @foreach(timezone_identifiers_list() as $tz)
                                    <option value="{{ $tz }}" {{ $clientTimezone === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Two-Column Calendar & Slots --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pt-2">

                    {{-- Left column: Calendar --}}
                    <div class="lg:col-span-7">
                        <div class="flex items-center justify-between mb-4">
                            <button type="button" id="cal-prev-month" wire:click="prevMonth" class="btn-ghost p-2" aria-label="Previous Month">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <span class="text-sm font-bold text-slate-800 tracking-tight">{{ $this->calendarLabel }}</span>
                            <button type="button" id="cal-next-month" wire:click="nextMonth" class="btn-ghost p-2" aria-label="Next Month">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>

                        {{-- Weekday headers --}}
                        <div class="grid grid-cols-7 mb-2 text-center">
                            @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $d)
                            <div class="text-[11px] font-bold uppercase tracking-wider {{ in_array($d, ['Su','Sa']) ? 'text-slate-300' : 'text-slate-500' }}">
                                {{ $d }}
                            </div>
                            @endforeach
                        </div>

                        {{-- Day grid --}}
                        <div class="grid grid-cols-7 gap-1">
                            @foreach($this->calendarDays as $day)
                                @if($day === null)
                                    <div class="h-9"></div>
                                @else
                                    <div class="flex items-center justify-center">
                                        <button
                                            type="button"
                                            id="cal-day-{{ $day['date'] }}"
                                            @if(!$day['disabled']) wire:click="selectDate('{{ $day['date'] }}')" @endif
                                            class="cal-day
                                                {{ $selectedDate === $day['date'] ? 'selected' : '' }}
                                                {{ $day['isToday'] && $selectedDate !== $day['date'] ? 'today' : '' }}
                                                {{ $day['disabled'] ? 'disabled' : '' }}"
                                            @if($day['disabled']) disabled @endif
                                        >
                                            {{ $day['day'] }}
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        @error('selectedDate')
                        <p class="error-msg mt-3">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Right column: Time Slots --}}
                    <div class="lg:col-span-5 border-t lg:border-t-0 lg:border-l border-slate-200/80 pt-6 lg:pt-0 lg:pl-8">
                        <label class="label">Available Times</label>
                        <p class="text-xs text-slate-500 mb-3">
                            @if($selectedDate)
                                {{ \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') }}
                            @else
                                Please pick a date on the calendar
                            @endif
                        </p>

                        @error('selectedTime')
                        <p class="error-msg mb-3">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror

                        @if(!$selectedDate)
                            <div class="flex flex-col items-center justify-center p-8 text-center rounded-xl border border-dashed border-slate-200 bg-slate-50/50">
                                <svg class="w-8 h-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-xs text-slate-500">Select an available weekday to view open consultation slots.</p>
                            </div>
                        @elseif(empty($this->availableSlots))
                            <div class="flex flex-col items-center justify-center p-8 text-center rounded-xl border border-dashed border-slate-200 bg-slate-50/50">
                                <svg class="w-8 h-8 text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-xs text-slate-500">No slots available for this date. Please select another date.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-2 gap-2 max-h-[300px] overflow-y-auto pr-1">
                                @foreach($this->availableSlots as $slot)
                                <button
                                    type="button"
                                    id="slot-{{ str_replace(':', '-', $slot['value']) }}"
                                    wire:click="selectTime('{{ $slot['value'] }}')"
                                    class="time-slot {{ $selectedTime === $slot['value'] ? 'selected' : '' }}"
                                >
                                    {{ $slot['label'] }}
                                </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>

            </div>

            {{-- Card footer --}}
            <div class="px-6 py-5 sm:px-8 border-t border-slate-100 bg-slate-50/70 flex items-center justify-between">
                <button type="button" id="step2-back-btn" wire:click="previousStep" class="btn-secondary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                    <span>Back</span>
                </button>
                <button type="button" id="step2-next-btn" wire:click="nextStep" class="btn-primary">
                    <span>Continue to Details</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </div>

        </div>
    </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════
         STEP 3 — Client Enterprise Credentials & Scoping
    ════════════════════════════════════════════════════════════════════ --}}
    @if($step === 3)
    <div class="animate-slide-up">
        <div class="card-elevated overflow-hidden">

            {{-- Card header --}}
            <div class="px-6 py-6 sm:px-8 border-b border-slate-100 bg-white">
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Enterprise Credentials & Scoping</h2>
                <p class="text-sm text-slate-500 mt-1">All information is held under strict confidentiality and used solely for call preparation.</p>
            </div>

            <div class="p-6 sm:p-8 space-y-6 bg-white">

                {{-- Name & Company --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="label" for="client-name">Full Name <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            id="client-name"
                            wire:model.defer="clientName"
                            class="input {{ $errors->has('clientName') ? 'input-error' : '' }}"
                            placeholder="Sarah Jenkins"
                            autocomplete="name"
                        >
                        @error('clientName')
                        <p class="error-msg">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label class="label" for="company-name">Company Name <span class="text-rose-500">*</span></label>
                        <input
                            type="text"
                            id="company-name"
                            wire:model.defer="companyName"
                            class="input {{ $errors->has('companyName') ? 'input-error' : '' }}"
                            placeholder="Enterprise Logistics Ltd."
                            autocomplete="organization"
                        >
                        @error('companyName')
                        <p class="error-msg">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                {{-- Work Email & Phone --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="label" for="client-email">Work Email <span class="text-rose-500">*</span></label>
                        <input
                            type="email"
                            id="client-email"
                            wire:model.defer="clientEmail"
                            class="input {{ $errors->has('clientEmail') ? 'input-error' : '' }}"
                            placeholder="s.jenkins@enterpriselogistics.com"
                            autocomplete="email"
                        >
                        @error('clientEmail')
                        <p class="error-msg">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label class="label" for="client-phone">Direct Phone / WhatsApp</label>
                        <input
                            type="tel"
                            id="client-phone"
                            wire:model.defer="clientPhone"
                            class="input {{ $errors->has('clientPhone') ? 'input-error' : '' }}"
                            placeholder="+1 (555) 234-5678"
                            autocomplete="tel"
                        >
                    </div>
                </div>

                {{-- Country & Operations --}}
                <div>
                    <label class="label" for="operations-country">Operating Country / Headquarters</label>
                    <input
                        type="text"
                        id="operations-country"
                        wire:model.defer="operationsCountry"
                        class="input"
                        placeholder="e.g. United States, United Kingdom, Australia, Singapore"
                    >
                </div>

                {{-- Operational Challenges --}}
                <div>
                    <label class="label" for="operational-challenges">
                        Key Operational Challenges & Expectations
                        <span class="normal-case font-normal text-slate-400 ml-1">(Optional)</span>
                    </label>
                    <textarea
                        id="operational-challenges"
                        wire:model.defer="operationalChallenges"
                        class="input resize-none"
                        rows="3"
                        placeholder="Detail your operational pain points, desired team shift hours, languages, or tools currently in use..."
                    ></textarea>
                </div>

                {{-- Bilateral NDA Request Toggle --}}
                <div class="flex items-start gap-4 p-5 rounded-xl border border-slate-200/90 bg-slate-50/70">
                    <div class="flex h-5 items-center pt-0.5">
                        <input
                            type="checkbox"
                            id="nda-requested"
                            wire:model.defer="ndaRequested"
                            class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-600"
                        >
                    </div>
                    <div class="flex-1">
                        <label for="nda-requested" class="text-sm font-semibold text-slate-900 cursor-pointer">
                            Request Mutual Non-Disclosure Agreement (NDA)
                        </label>
                        <p class="text-xs text-slate-500 mt-1">
                            We will email our standard bilateral enterprise NDA for signature prior to the consultation.
                        </p>
                    </div>
                    <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-brand-50 text-brand-700 border border-brand-200">
                        Enterprise Standard
                    </span>
                </div>

                {{-- Live Summary Card --}}
                <div class="rounded-xl border border-slate-200/80 bg-slate-50 p-5 space-y-2">
                    <span class="text-xs font-semibold text-slate-700 uppercase tracking-wider block mb-3">Booking Summary</span>
                    @php
                        $svcName = 'Selected Service';
                        if($serviceId && $this->services->count()) {
                            $found = $this->services->firstWhere('id', $serviceId);
                            if($found) $svcName = $found->name;
                        }
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                        <div>
                            <span class="text-slate-400 block font-medium">Service</span>
                            <span class="text-slate-800 font-semibold truncate block mt-0.5">{{ $svcName }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Scale</span>
                            <span class="text-slate-800 font-semibold block mt-0.5">{{ $headcountRange ?: '1-5' }} agents</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Date</span>
                            <span class="text-slate-800 font-semibold block mt-0.5">
                                {{ $selectedDate ? \Carbon\Carbon::parse($selectedDate)->format('M j, Y') : '—' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-medium">Time</span>
                            <span class="text-slate-800 font-semibold block mt-0.5">
                                {{ $selectedTime ?: '—' }} <span class="text-slate-400 font-normal">({{ $clientTimezone }})</span>
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Card footer --}}
            <div class="px-6 py-5 sm:px-8 border-t border-slate-100 bg-slate-50/70 flex items-center justify-between">
                <button type="button" id="step3-back-btn" wire:click="previousStep" class="btn-secondary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                    <span>Back</span>
                </button>
                <button
                    type="button"
                    id="step3-submit-btn"
                    wire:click="nextStep"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-70 cursor-wait"
                    class="btn-primary"
                >
                    <span wire:loading.remove wire:target="nextStep">Confirm & Dispatch Invite</span>
                    <span wire:loading wire:target="nextStep" class="flex items-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Securing Schedule…
                    </span>
                    <svg wire:loading.remove wire:target="nextStep" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>

        </div>
    </div>
    @endif

    {{-- ════════════════════════════════════════════════════════════════════
         STEP 4 — Confirmation Card
    ════════════════════════════════════════════════════════════════════ --}}
    @if($step === 4)
    <div class="animate-slide-up max-w-xl mx-auto">

        <div class="card-elevated overflow-hidden">

            {{-- Top confirmed banner --}}
            <div class="bg-brand-600 px-6 py-6 sm:px-8 text-white flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-brand-200">Consultation Scheduled</span>
                    <h2 class="text-xl font-bold mt-0.5 tracking-tight">Booking Confirmed</h2>
                </div>
                <div class="text-right">
                    <span class="text-[10px] uppercase font-semibold text-brand-200 block">Reference ID</span>
                    <span class="font-mono text-sm font-bold text-white tracking-wider">{{ $confirmationReference }}</span>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6 bg-white">

                <div class="flex items-start gap-3.5 p-4 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-900 text-xs leading-relaxed">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-emerald-950">Calendar invite & meeting link dispatched</p>
                        <p class="mt-0.5 text-emerald-800">We have sent an RFC 5545 calendar invite (.ics) to <strong class="text-emerald-950">{{ $clientEmail }}</strong>. You can accept it directly from your calendar app.</p>
                    </div>
                </div>

                {{-- Consultation Specs --}}
                @if($this->confirmedBooking)
                <div class="grid grid-cols-2 gap-4 text-xs border-b border-slate-100 pb-6">
                    <div>
                        <span class="text-slate-400 font-medium block">Service Domain</span>
                        <span class="font-semibold text-slate-800 block mt-1">{{ $this->confirmedBooking->service->name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block">Team Headcount</span>
                        <span class="font-semibold text-slate-800 block mt-1">{{ $this->confirmedBooking->headcount_range }} agents</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block">Date & Local Time</span>
                        <span class="font-semibold text-slate-800 block mt-1">
                            {{ $this->confirmedBooking->scheduled_at->setTimezone($clientTimezone)->format('l, M j, Y') }}
                        </span>
                        <span class="text-slate-500 block">
                            {{ $this->confirmedBooking->scheduled_at->setTimezone($clientTimezone)->format('g:i A') }} ({{ $clientTimezone }})
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block">Duration</span>
                        <span class="font-semibold text-slate-800 block mt-1">30 Minutes</span>
                    </div>
                </div>

                @if($this->confirmedBooking->consultant)
                <div class="flex items-center gap-3.5 p-4 rounded-xl border border-slate-200/80 bg-slate-50">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-600 text-white font-bold text-sm flex-shrink-0">
                        {{ substr($this->confirmedBooking->consultant->name, 0, 2) }}
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">Your Assigned Consultant</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $this->confirmedBooking->consultant->name }}</p>
                        <p class="text-xs text-slate-500">{{ $this->confirmedBooking->consultant->title }} &bull; {{ $this->confirmedBooking->consultant->email }}</p>
                    </div>
                </div>
                @endif
                @endif

                {{-- Action options --}}
                <div class="pt-2 flex flex-col sm:flex-row gap-3">
                    <button type="button" wire:click="$set('step', 1)" class="btn-secondary w-full justify-center">
                        Schedule Another Call
                    </button>
                    <a href="{{ url('/') }}" class="btn-primary w-full justify-center">
                        Return to Homepage
                    </a>
                </div>

            </div>

        </div>

    </div>
    @endif

</div>
