<?php

namespace Tests\Feature\Chat;

use App\Models\UserInfo;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = UserInfo::factory()->create();
        $this->admin = UserInfo::factory()->create([
            'email' => 'adminb@gmail.com'
        ]);
    }

    public function test_user_can_start_admin_chat()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('chat.contact-admin'));

        $response->assertRedirect();
        $this->assertDatabaseHas('conversations', [
            'buyer_id' => $this->user->id,
            'seller_id' => $this->admin->id,
            'product_id' => null
        ]);
    }

    public function test_user_can_send_message()
    {
        $this->actingAs($this->user);

        $conversation = Conversation::factory()->create([
            'buyer_id' => $this->user->id,
            'seller_id' => $this->admin->id
        ]);

        $response = $this->post(route('chat.messages.store', $conversation), [
            'message' => 'Hello Admin'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $this->user->id,
            'message' => 'Hello Admin'
        ]);
    }

    public function test_user_can_view_conversations()
    {
        $this->actingAs($this->user);

        $conversation = Conversation::factory()->create([
            'buyer_id' => $this->user->id,
            'seller_id' => $this->admin->id
        ]);

        Message::factory()->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $this->user->id,
            'message' => 'Test Message'
        ]);

        $response = $this->get(route('chat.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Message');
    }

    public function test_user_can_start_product_chat()
    {
        $this->actingAs($this->user);

        $seller = UserInfo::factory()->create();
        $product = Product::factory()->create([
            'seller_id' => $seller->id
        ]);

        $response = $this->post(route('chat.store', $product));

        $response->assertRedirect();
        $this->assertDatabaseHas('conversations', [
            'buyer_id' => $this->user->id,
            'seller_id' => $seller->id,
            'product_id' => $product->id
        ]);
    }
}
