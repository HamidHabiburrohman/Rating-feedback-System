<?php

declare(strict_types=1);

namespace Database\Factories\Conversation;

use App\Models\Conversation\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition(): array
    {
        return [
            'subject' => $this->faker->optional(0.7)->randomElement([
                'Unit Maintenance Request',
                'QR Code Assignment Issue',
                'Monthly Report Discussion',
                'Schedule Update',
                'Facility Inspection',
                'Staffing Coordination',
                'Student Feedback Review',
                'Equipment Purchase Approval',
            ]),
            'status' => $this->faker->randomElement([
                Conversation::STATUS_ACTIVE,
                Conversation::STATUS_ACTIVE,
                Conversation::STATUS_ACTIVE,
                Conversation::STATUS_ARCHIVED,
                Conversation::STATUS_CLOSED,
            ]),
            'last_message_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}