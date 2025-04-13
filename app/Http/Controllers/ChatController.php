<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\ChatMessageEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        $messages = Message::with('sender')
            ->whereNull('receiver_id')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->reverse();

        return view('chat.index', compact('messages'));
    }

    public function contacts(): View
    {
        return view('chat.contacts');
    }

    public function searchContacts(Request $request): View
    {
        $phone = $request->get('phone');
        $contacts = collect();

        if ($phone) {
            $contacts = User::where('phone_number', 'like', "%{$phone}%")
                ->where('id', '!=', Auth::id())
                ->get();
        }

        return view('chat.contacts', compact('contacts'));
    }

    public function privateChat(User $user): View
    {
        $messages = Message::with(['sender', 'receiver'])
            ->where(function($query) use ($user) {
                $query->where(function($q) use ($user) {
                    $q->where('sender_id', Auth::id())
                      ->where('receiver_id', $user->id);
                })->orWhere(function($q) use ($user) {
                    $q->where('sender_id', $user->id)
                      ->where('receiver_id', Auth::id());
                });
            })
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->reverse();

        return view('chat.private', compact('messages', 'user'));
    }

    public function sendMessage(Request $request, User $user = null): JsonResponse
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user?->id,
            'content' => $request->get('content')
        ]);

        broadcast(new ChatMessageEvent($message->content, $message->sender))->toOthers();

        return response()->json([
            'message' => $message->load(['sender', 'receiver'])
        ]);
    }

    public function getMessages(Request $request): JsonResponse
    {
        $query = Message::with('sender');
        
        if ($request->user_id) {
            $query->where(function($q) use ($request) {
                $q->where(function($inner) use ($request) {
                    $inner->where('sender_id', Auth::id())
                          ->where('receiver_id', $request->user_id);
                })->orWhere(function($inner) use ($request) {
                    $inner->where('sender_id', $request->user_id)
                          ->where('receiver_id', Auth::id());
                });
            });
        } else {
            $query->whereNull('receiver_id');
        }

        $messages = $query->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->reverse();

        return response()->json($messages);
    }
}