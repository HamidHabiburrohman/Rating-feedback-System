<?php

declare(strict_types=1);

namespace Database\Seeders\Conversation;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Conversation\Conversation;
use App\Models\Conversation\ConversationParticipant;
use App\Models\Conversation\Message;
use App\Models\Conversation\MessageAttachment;
use App\Models\Conversation\MessageRead;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    public function run(): void
    {
        $admins = Admin::inRandomOrder()->limit(5)->get();
        $employees = Employee::inRandomOrder()->limit(10)->get();
        $users = $admins->merge($employees);

        if ($users->count() < 2) {
            $this->command->warn('ConversationSeeder skipped: Not enough Admins or Employees found.');
            return;
        }

        $conversationCount = 20;
        $minMessages = 5;
        $maxMessages = 15;
        $attachmentProbability = 20;
        $readProbability = 70;

        for ($i = 0; $i < $conversationCount; $i++) {
            $conversation = Conversation::factory()->create();
            
            $participantCount = rand(2, 4);
            $conversationUsers = $users->random(min($participantCount, $users->count()));
            
            $participantIds = [];

            foreach ($conversationUsers as $user) {
                ConversationParticipant::create([
                    'conversation_id' => $conversation->id,
                    'participant_type' => get_class($user),
                    'participant_id' => $user->id,
                    'joined_at' => $conversation->created_at,
                ]);
                
                $participantIds[] = [
                    'type' => get_class($user),
                    'id' => $user->id,
                ];
            }

            $messageCount = rand($minMessages, $maxMessages);
            $currentTime = $conversation->created_at->copy();
            $lastMessageAt = $currentTime;

            for ($m = 0; $m < $messageCount; $m++) {
                $currentTime = $currentTime->copy()->addMinutes(rand(5, 120));
                $senderData = fake()->randomElement($participantIds);
                
                $isAttachment = fake()->boolean($attachmentProbability);
                
                $message = Message::factory()
                    ->when($isAttachment, fn($factory) => $factory->withAttachment())
                    ->create([
                        'conversation_id' => $conversation->id,
                        'sender_type' => $senderData['type'],
                        'sender_id' => $senderData['id'],
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                    ]);

                if ($isAttachment) {
                    MessageAttachment::factory()->create([
                        'message_id' => $message->id,
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                    ]);
                }

                $lastMessageAt = $currentTime;

                foreach ($participantIds as $participant) {
                    $isSender = ($participant['type'] === $senderData['type'] && $participant['id'] === $senderData['id']);
                    
                    if (!$isSender && fake()->boolean($readProbability)) {
                        $readAt = $currentTime->copy()->addMinutes(rand(1, 45));
                        if ($readAt->isFuture()) {
                            $readAt = now();
                        }
                        
                        MessageRead::create([
                            'message_id' => $message->id,
                            'reader_type' => $participant['type'],
                            'reader_id' => $participant['id'],
                            'read_at' => $readAt,
                            'created_at' => $readAt,
                            'updated_at' => $readAt,
                        ]);
                    }
                }
            }

            $conversation->update(['last_message_at' => $lastMessageAt]);
        }
    }
}