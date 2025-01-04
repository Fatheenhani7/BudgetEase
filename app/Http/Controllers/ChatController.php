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

<<<<<<< HEAD
    public function store(Product $product)
    {
        $user = Auth::user();
        $isAdminChat = request()->has('admin_chat');
        
        if ($isAdminChat) {
            // For admin chat, create conversation with admin
            $adminUser = UserInfo::where('email', 'adminb@gmail.com')->first();
            
            // Check if admin conversation already exists
            $conversation = Conversation::where(function($query) use ($product, $user, $adminUser) {
                $query->where('buyer_id', $user->id)
                    ->where('seller_id', $adminUser->id)
                    ->where('product_id', $product->id);
            })->orWhere(function($query) use ($product, $user, $adminUser) {
                $query->where('seller_id', $user->id)
                    ->where('buyer_id', $adminUser->id)
                    ->where('product_id', $product->id);
            })->first();

            if (!$conversation) {
                $conversation = Conversation::create([
                    'product_id' => $product->id,
                    'buyer_id' => $user->id,
                    'seller_id' => $adminUser->id,
                ]);

                // Create initial message for admin chat
                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $user->id,
                    'message' => "Hi admin, I would like to discuss about my reported product: " . $product->title,
                ]);
            }
        } else {
            // Regular product inquiry chat
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

                // Create initial message for regular chat
                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $user->id,
                    'message' => "Hi, I'm interested in your product: " . $product->title,
                ]);
            }
        }

        return redirect()->route('chat.show', $conversation);
    }

    public function startConversation(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'message' => 'required|string|max:1000',
        ]);

        $product = Product::findOrFail($request->product_id);
=======
    public function store(Request $request, Product $product)
    {
        $user = UserInfo::where('email', Auth::user()->email)->first();
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
        
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

<<<<<<< HEAD
        // Create the initial message
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

=======
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
        return redirect()->route('chat.show', $conversation);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
<<<<<<< HEAD
        $user = Auth::user();
        
        // Authorize that user is part of the conversation
=======
        $user = UserInfo::where('email', Auth::user()->email)->first();
        
        // Validate user is part of conversation
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
        if ($conversation->buyer_id !== $user->id && $conversation->seller_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

<<<<<<< HEAD
        Message::create([
=======
        $message = Message::create([
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

<<<<<<< HEAD
        return redirect()->back();
    }

    public function contactAdmin()
    {
        $user = Auth::user();
        $adminUser = UserInfo::where('email', 'adminb@gmail.com')->first();
        
        // Check if conversation with admin already exists
        $conversation = Conversation::where(function($query) use ($user, $adminUser) {
            $query->where('buyer_id', $user->id)
                ->where('seller_id', $adminUser->id)
                ->whereNull('product_id');
        })->orWhere(function($query) use ($user, $adminUser) {
            $query->where('seller_id', $user->id)
                ->where('buyer_id', $adminUser->id)
                ->whereNull('product_id');
        })->first();

        if (!$conversation) {
            // Create new conversation with admin
            $conversation = Conversation::create([
                'buyer_id' => $user->id,
                'seller_id' => $adminUser->id,
                'product_id' => null // This indicates it's a general support conversation
            ]);

            // Create initial message
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'message' => "Hi admin, I need assistance with BudgetEase.",
            ]);
        }

        return redirect()->route('chat.show', $conversation);
=======
        return back();
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }
}
