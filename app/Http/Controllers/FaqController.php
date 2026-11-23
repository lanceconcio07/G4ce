<?php

namespace App\Http\Controllers;

use App\Services\GeminiFaqService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function __construct(
        private readonly GeminiFaqService $geminiFaqService
    ) {}

    /**
     * Handle AI FAQ Chat query via JSON API.
     */
    public function ask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'history' => ['nullable', 'array', 'max:10'],
            'history.*.role' => ['required_with:history', 'string', 'in:user,assistant,model'],
            'history.*.text' => ['required_with:history', 'string', 'max:2000'],
        ]);

        $reply = $this->geminiFaqService->ask(
            $validated['message'],
            $validated['history'] ?? []
        );

        return response()->json([
            'success' => true,
            'reply'   => $reply,
        ]);
    }
}
