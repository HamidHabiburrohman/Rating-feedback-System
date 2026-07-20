<?php
declare(strict_types=1);
namespace Database\Factories\Conversation;
use App\Models\Conversation\Conversation;
use App\Models\Conversation\Message;
use Illuminate\Database\Eloquent\Factories\Factory;
class MessageFactory extends Factory
{
    protected $model = Message::class;
    public function definition(): array
    {
        return [
            'conversation_id' => Conversation::factory(),
            'reply_to_id' => null,
            'body' => $this->faker->randomElement([
                'Good morning.',
                'The report has been reviewed and approved.',
                'Please update the unit information in the system.',
                'Thank you for the quick response.',
                'I have attached the inspection document.',
                'Could you check the QR code assignment for Laboratory A?',
                'The maintenance team will arrive at 2 PM.',
                'Noted, I will handle it right away.',
                'Let me know if you need any further assistance.',
                'The schedule has been updated for next week.',
                'Please ensure all staff are informed about the changes.',
                'I have forwarded the email to the IT department.',
                'The repair is complete, please verify.',
                'We need to discuss the capacity limits for the main hall.',
                'All QR codes for the new building have been generated.',
            ]),
            'type' => Message::TYPE_TEXT,
            'edited_at' => $this->faker->optional(0.1)->dateTimeBetween('-1 week', 'now'),
        ];
    }
    public function system(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => Message::TYPE_SYSTEM,
            'body' => $this->faker->randomElement([
                'Conversation created.',
                'Participant added.',
                'Status updated.',
                'Assignment transferred.',
            ]),
        ]);
    }
    public function withAttachment(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => Message::TYPE_ATTACHMENT,
            'body' => $this->faker->randomElement([
                'Please find the attached document.',
                'Here is the photo of the facility.',
                'Attached is the monthly report.',
            ]),
        ]);
    }
}