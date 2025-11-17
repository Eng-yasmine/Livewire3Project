<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * عرض صفحة المحادثة
     */
    public function index()
    {
        // جلب آخر 50 رسالة
        $messages = Message::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse()
            ->values();

        return view('admin.chat.index', compact('messages'));
    }

    /**
     * إرسال رسالة جديدة
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::guard('admin')->user();

        // إنشاء الرسالة
        $message = Message::create([
            'user_id' => $user->id,
            'username' => $user->name ?? $user->email ?? 'مجهول',
            'message' => $request->message,
        ]);

        // إرسال الحدث عبر Reverb
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'id' => $message->id,
                'user_id' => $message->user_id,
                'username' => $message->username,
                'message' => $message->message,
                'created_at' => $message->created_at->toDateTimeString(),
                'time' => $message->created_at->format('H:i'),
            ]
        ]);
    }

    /**
     * جلب الرسائل (API)
     */
    public function getMessages()
    {
        $messages = Message::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse()
            ->values()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'user_id' => $message->user_id,
                    'username' => $message->username ?? 'مجهول',
                    'message' => $message->message,
                    'created_at' => $message->created_at->toDateTimeString(),
                    'time' => $message->created_at->format('H:i'),
                ];
            });

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }
}
