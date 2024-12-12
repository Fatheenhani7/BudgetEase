<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = UserInfo::where('email', Auth::user()->email)->first();
        $conversations = Conversation::where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->with(['product', 'buyer', 'seller', 'lastMessage'])
            ->latest()
            ->get();

        return view('pages.chat.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $user = UserInfo::where('email', Auth::user()->email)->first();
        
        // Authorize that user is part of the conversation
        if ($conversation->buyer_id !== $user->id && $conversation->seller_id !== $user->id) {
            abort(403);
        }

        $conversation->load(['product', 'buyer', 'seller', 'messages.sender']);
        
        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('pages.chat.show', compact('conversation'));
    }

    public function store(Request $request, Product $product)
    {
        $user = UserInfo::where('email', Auth::user()->email)->first();
        
        // Check if conversation already exists
        $conversation = Conversation::where('product_id', $product->id)
            ->where('buyer_id', $user->id)
            ->where('seller_id', $product->seller_id)
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'product_id' => $product->id,
                'buyer_id' => $user->id,
                'seller_id' => $product->seller_id,
            ]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $user = UserInfo::where('email', Auth::user()->email)->first();
        
        // Validate user is part of conversation
        if ($conversation->buyer_id !== $user->id && $conversation->seller_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

        return back();
    }
}
