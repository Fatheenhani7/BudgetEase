<?php

namespace Database\Factories;

use App\Models\UserInfo;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'conversation_id' => Conversation::factory(),
            'sender_id' => UserInfo::factory(),
            'message' => $this->faker->sentence,
            'is_read' => false
        ];
    }
}
