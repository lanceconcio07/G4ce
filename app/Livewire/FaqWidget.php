<?php

namespace App\Livewire;

use App\Services\GeminiFaqService;
use Livewire\Component;

class FaqWidget extends Component
{
    public bool $isOpen = false;
    public string $inputMessage = '';
    public bool $isLoading = false;

    /**
     * @var array<int, array{id: string, role: string, text: string, time: string}>
     */
    public array $messages = [];

    public array $quickQuestions = [
        'What BPO services does G4ce provide?',
        'How does the 30-min discovery call work?',
        'Can we sign an NDA before our call?',
        'What team sizes can I hire?',
        'What timezones do your teams support?',
    ];

    public function mount(): void
    {
        $this->messages = [
            [
                'id'   => 'welcome-1',
                'role' => 'assistant',
                'text' => "Hello! 👋 I'm your **G4ce AI Assistant**, powered by Gemini. How can I help you today?\n\nYou can click any question below or type your inquiry directly!",
                'time' => now()->format('g:i A'),
            ]
        ];
    }

    public function toggle(): void
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen) {
            $this->dispatch('faq-opened');
        }
    }

    public function askQuickQuestion(string $question): void
    {
        $this->inputMessage = $question;
        $this->sendMessage();
    }

    public function sendMessage(): void
    {
        $text = trim($this->inputMessage);
        if ($text === '') {
            return;
        }

        // Add user message
        $this->messages[] = [
            'id'   => uniqid('u_'),
            'role' => 'user',
            'text' => $text,
            'time' => now()->format('g:i A'),
        ];

        $this->inputMessage = '';
        $this->isLoading = true;

        // Build history
        $history = array_map(function ($m) {
            return [
                'role' => ($m['role'] === 'user') ? 'user' : 'model',
                'text' => $m['text'],
            ];
        }, $this->messages);

        // Call Gemini Service
        try {
            $faqService = app(GeminiFaqService::class);
            $reply = $faqService->ask($text, $history);
        } catch (\Throwable $e) {
            $reply = "I apologize, but I encountered an unexpected error. Please try asking again or feel free to book a call directly with our team!";
        }

        $this->messages[] = [
            'id'   => uniqid('a_'),
            'role' => 'assistant',
            'text' => $reply,
            'time' => now()->format('g:i A'),
        ];

        $this->isLoading = false;
        $this->dispatch('faq-message-added');
    }

    public function clearChat(): void
    {
        $this->messages = [
            [
                'id'   => 'welcome-reset',
                'role' => 'assistant',
                'text' => "Chat reset! How else can I assist you with G4ce's BPO services or scheduling your discovery call?",
                'time' => now()->format('g:i A'),
            ]
        ];
        $this->inputMessage = '';
    }

    public function render()
    {
        return view('livewire.faq-widget');
    }
}
