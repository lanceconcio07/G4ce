<div
    x-data="{
        isOpen: @entangle('isOpen'),
        scrollToBottom() {
            $nextTick(() => {
                const el = this.$refs.chatBody;
                if (el) {
                    el.scrollTop = el.scrollHeight;
                }
            });
        }
    }"
    x-init="
        $watch('isOpen', value => { if (value) scrollToBottom(); });
        Livewire.on('faq-message-added', () => scrollToBottom());
        Livewire.on('faq-opened', () => scrollToBottom());
    "
    class="relative z-50"
>
    {{-- ════════════════════════════════════════════════════════════════════
         FLOATING CHAT WINDOW
    ════════════════════════════════════════════════════════════════════ --}}
    <div
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
        @keydown.escape.window="isOpen = false"
        class="fixed bottom-24 right-4 sm:right-6 w-[calc(100vw-2rem)] sm:w-[420px] h-[580px] max-h-[82vh] bg-white rounded-2xl shadow-elevation-xl border border-slate-200 flex flex-col overflow-hidden z-50"
        style="display: none;"
    >
        {{-- Header --}}
        <div class="bg-slate-900 text-white px-5 py-4 flex items-center justify-between border-b border-slate-800 select-none">
            <div class="flex items-center gap-3">
                <div class="relative flex items-center justify-center w-9 h-9 rounded-xl bg-brand-600 text-white shadow-subtle">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/>
                    </svg>
                    <span class="absolute -bottom-0.5 -right-0.5 flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500 border border-slate-900"></span>
                    </span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        G4ce Concierge
                        <span class="text-[10px] font-semibold uppercase px-1.5 py-0.5 rounded-md bg-slate-800 text-slate-300 border border-slate-700">AI Assistant</span>
                    </h3>
                    <p class="text-[11px] text-slate-400 flex items-center gap-1.5 mt-0.5">
                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        Ready to assist
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-1">
                {{-- Reset conversation --}}
                <button
                    type="button"
                    wire:click="clearChat"
                    title="Reset conversation"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                </button>
                {{-- Close Window --}}
                <button
                    type="button"
                    @click="isOpen = false"
                    title="Close"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Chat Body --}}
        <div
            x-ref="chatBody"
            class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50 text-sm"
        >
            {{-- Quick Questions Box --}}
            <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-subtle">
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>
                    </svg>
                    Frequently Asked Questions
                </p>
                <div class="flex flex-wrap gap-1.5">
                    @foreach($quickQuestions as $q)
                        <button
                            type="button"
                            wire:click="askQuickQuestion('{{ addslashes($q) }}')"
                            wire:loading.attr="disabled"
                            class="text-left text-xs bg-slate-50 hover:bg-brand-50 hover:text-brand-700 hover:border-brand-300 text-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 transition-all duration-150"
                        >
                            {{ $q }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Messages Stream --}}
            @foreach($messages as $msg)
                @if($msg['role'] === 'assistant')
                    {{-- Assistant Bubble --}}
                    <div class="flex items-start gap-2.5 max-w-[90%]">
                        <div class="w-7 h-7 rounded-lg bg-slate-900 flex items-center justify-center text-white shrink-0 mt-0.5 shadow-subtle">
                            <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                            </svg>
                        </div>
                        <div class="bg-white border border-slate-200/90 rounded-2xl rounded-tl-sm px-4 py-3 text-slate-800 shadow-subtle leading-relaxed text-xs sm:text-sm">
                            <div class="prose prose-xs max-w-none text-slate-700 whitespace-pre-line leading-relaxed">
                                {!! nl2br(e($msg['text'])) !!}
                            </div>
                            <div class="mt-2 pt-1 border-t border-slate-100 flex items-center justify-between text-[10px] text-slate-400">
                                <span>G4ce AI</span>
                                <span>{{ $msg['time'] }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- User Bubble --}}
                    <div class="flex items-start justify-end gap-2 max-w-[85%] ml-auto">
                        <div class="bg-brand-600 text-white rounded-2xl rounded-tr-sm px-4 py-2.5 shadow-subtle text-xs sm:text-sm leading-relaxed">
                            <p class="whitespace-pre-line">{{ $msg['text'] }}</p>
                            <span class="block text-right text-[10px] text-brand-200 mt-1">{{ $msg['time'] }}</span>
                        </div>
                    </div>
                @endif
            @endforeach

            {{-- Loading State --}}
            @if($isLoading)
                <div class="flex items-start gap-2.5 max-w-[85%]">
                    <div class="w-7 h-7 rounded-lg bg-slate-900 flex items-center justify-center text-white shrink-0 mt-0.5">
                        <svg class="w-4 h-4 animate-spin text-brand-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-sm px-4 py-3 shadow-subtle">
                        <div class="flex items-center gap-1.5 py-1">
                            <span class="w-2 h-2 rounded-full bg-brand-600 animate-pulse"></span>
                            <span class="w-2 h-2 rounded-full bg-brand-600 animate-pulse" style="animation-delay: 200ms"></span>
                            <span class="w-2 h-2 rounded-full bg-brand-600 animate-pulse" style="animation-delay: 400ms"></span>
                            <span class="text-xs text-slate-400 ml-1.5 font-medium">Thinking…</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Direct CTA Banner --}}
        <div class="px-4 py-2.5 bg-slate-100 border-t border-slate-200 flex items-center justify-between">
            <span class="text-xs text-slate-600 font-medium">Ready to discuss your scope?</span>
            <a
                href="{{ route('booking.index') }}"
                @click="isOpen = false"
                class="text-xs font-semibold text-brand-600 hover:text-brand-800 transition-colors flex items-center gap-1"
            >
                Book Discovery Call &rarr;
            </a>
        </div>

        {{-- Input Footer --}}
        <div class="p-3 bg-white border-t border-slate-200">
            <form wire:submit.prevent="sendMessage" class="flex items-center gap-2">
                <input
                    type="text"
                    wire:model="inputMessage"
                    placeholder="Ask about team scale, SLAs, or capabilities..."
                    class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-600/20 focus:border-brand-600 focus:bg-white transition-all"
                    wire:loading.attr="disabled"
                    autofocus
                />
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="h-9 px-3.5 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white rounded-xl flex items-center justify-center transition-colors disabled:opacity-50 shadow-subtle"
                    title="Send message"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                    </svg>
                </button>
            </form>
            <p class="text-[10px] text-slate-400 text-center mt-1.5">
                AI Assistant trained on G4ce BPO operational standards
            </p>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════
         FLOATING TRIGGER BUTTON (LOWER RIGHT)
    ════════════════════════════════════════════════════════════════════ --}}
    <div class="fixed bottom-6 right-6 z-50 flex items-center gap-3">
        {{-- Floating Prompt Pill (Visible when closed) --}}
        <div
            x-show="!isOpen"
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="opacity-0 translate-x-3"
            x-transition:enter-end="opacity-100 translate-x-0"
            @click="isOpen = true"
            class="hidden md:flex items-center gap-2.5 bg-white/95 backdrop-blur-md px-4 py-2.5 rounded-full shadow-elevation-md border border-slate-200/90 text-xs font-semibold text-slate-700 cursor-pointer hover:border-slate-300 hover:text-slate-900 transition-all duration-150 group"
        >
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span>Have questions? <strong class="text-brand-600 font-semibold group-hover:underline">Ask AI FAQ</strong></span>
        </div>

        {{-- Floating Action Button --}}
        <button
            type="button"
            @click="isOpen = !isOpen"
            id="g4ce-faq-floating-btn"
            aria-label="Open AI FAQ Assistant"
            class="relative flex items-center justify-center w-13 h-13 p-3.5 rounded-full bg-slate-900 hover:bg-slate-800 text-white shadow-elevation-lg hover:shadow-elevation-xl hover:-translate-y-0.5 active:translate-y-0 active:scale-95 transition-all duration-150 focus:outline-none focus:ring-4 focus:ring-brand-500/20"
        >
            {{-- Icon when closed --}}
            <span x-show="!isOpen" class="flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/>
                </svg>
            </span>

            {{-- Icon when open --}}
            <span x-show="isOpen" style="display: none;" class="flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </span>

            {{-- Indicator dot --}}
            <span x-show="!isOpen" class="absolute top-0 right-0 flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-brand-500 border-2 border-white"></span>
            </span>
        </button>
    </div>
</div>
