<?php

namespace Database\Seeders\Conversation;

use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Conversation\Conversation;
use App\Models\Conversation\ConversationParticipant;
use App\Models\Conversation\Message;
use App\Models\Conversation\MessageAttachment;
use App\Models\Conversation\MessageRead;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $admins = Admin::inRandomOrder()->limit(3)->get();
        $employees = Employee::inRandomOrder()->limit(5)->get();

        if ($admins->isEmpty() || $employees->isEmpty()) {
            $this->command->warn('Skipping MessageSeeder: Admins or Employees not found.');
            return;
        }

        $distributions = [
            ['count' => 10, 'min_msg' => 5, 'max_msg' => 10],
            ['count' => 20, 'min_msg' => 15, 'max_msg' => 30],
            ['count' => 5, 'min_msg' => 50, 'max_msg' => 100],
        ];

        $messageTemplates = [
            'Please verify the QR assignment for Laboratory A.',
            'The report has been reviewed and approved.',
            'Could you update the operating hours for the main library?',
            'Students reported that the scanner in Room 101 is unavailable.',
            'Thank you, the issue has been resolved.',
            'I have attached the inspection report for your review.',
            'The maintenance team will arrive at 2 PM.',
            'Please ensure all staff are informed about the schedule change.',
            'We need to order more toner for the printer in the admin office.',
            'The air conditioning in Hall B is not working properly.',
            'Can we schedule a meeting to discuss the new equipment purchase?',
            'The system will be down for maintenance tonight from 11 PM to 2 AM.',
            'I have updated the database with the latest student feedback.',
            'Please check the attached document for the monthly report.',
            'The projector in Room 204 is flickering, can someone take a look?',
            'All QR codes for the new building have been generated.',
            'Let me know if you need any further assistance with the report.',
            'The cleaning staff will handle the spill in the cafeteria.',
            'Please remind the team about the safety drill tomorrow.',
            'I have forwarded the email to the IT department.',
            'Noted, I will handle it right away.',
            'Could you send me the latest version of the document?',
            'The repair is complete, please verify.',
            'I will be on leave tomorrow, please contact Sarah for urgent matters.',
            'The new software update has been deployed successfully.',
        ];

        $attachmentFiles = [
            ['name' => 'inspection-report.pdf', 'mime' => 'application/pdf', 'ext' => 'pdf'],
            ['name' => 'qr-assignment.xlsx', 'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'ext' => 'xlsx'],
            ['name' => 'laboratory-photo.jpg', 'mime' => 'image/jpeg', 'ext' => 'jpg'],
            ['name' => 'maintenance-document.pdf', 'mime' => 'application/pdf', 'ext' => 'pdf'],
        ];

        foreach ($distributions as $dist) {
            for ($i = 0; $i < $dist['count']; $i++) {
                $admin = $admins->random();
                $employee = $employees->random();
                
                $startDate = now()->subWeeks(rand(1, 4))->subDays(rand(0, 6));
                
                $conversation = Conversation::factory()->create([
                    'last_message_at' => $startDate,
                ]);

                ConversationParticipant::create([
                    'conversation_id' => $conversation->id,
                    'participant_type' => Admin::class,
                    'participant_id' => $admin->id,
                    'joined_at' => $startDate,
                ]);
                
                ConversationParticipant::create([
                    'conversation_id' => $conversation->id,
                    'participant_type' => Employee::class,
                    'participant_id' => $employee->id,
                    'joined_at' => $startDate,
                ]);

                $messageCount = rand($dist['min_msg'], $dist['max_msg']);
                $currentTime = $startDate->copy();
                
                // 0 = fully unread, 1 = partially read, 2 = fully read
                $readState = rand(0, 2); 
                $lastReadMessageIndex = $readState === 0 ? -1 : ($readState === 1 ? rand(0, $messageCount - 2) : $messageCount - 1);

                $messagesData = [];

                for ($m = 0; $m < $messageCount; $m++) {
                    $currentTime = $currentTime->copy()->addMinutes(rand(5, 120));
                    $isAdminSender = rand(0, 1) === 1;
                    
                    $senderType = $isAdminSender ? Admin::class : Employee::class;
                    $senderId = $isAdminSender ? $admin->id : $employee->id;
                    
                    $message = Message::factory()->create([
                        'conversation_id' => $conversation->id,
                        'sender_type' => $senderType,
                        'sender_id' => $senderId,
                        'body' => fake()->randomElement($messageTemplates),
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                    ]);
                    
                    $messagesData[] = [
                        'id' => $message->id,
                        'sender_type' => $senderType,
                        'sender_id' => $senderId,
                        'created_at' => $currentTime,
                    ];

                    // 10% chance of attachment
                    if (rand(1, 100) <= 10) {
                        $file = fake()->randomElement($attachmentFiles);
                        $storedName = fake()->uuid() . '.' . $file['ext'];
                        
                        MessageAttachment::factory()->create([
                            'message_id' => $message->id,
                            'original_name' => $file['name'],
                            'stored_name' => $storedName,
                            'mime_type' => $file['mime'],
                            'path' => 'attachments/' . $storedName,
                            'created_at' => $currentTime,
                            'updated_at' => $currentTime,
                        ]);
                        
                        $message->update(['type' => 'attachment']);
                    }
                }

                $conversation->update(['last_message_at' => $currentTime]);

                // Generate read history naturally
                foreach ($messagesData as $index => $msgData) {
                    $readerType = ($msgData['sender_type'] === Admin::class) ? Employee::class : Admin::class;
                    $readerId = ($msgData['sender_type'] === Admin::class) ? $employee->id : $admin->id;
                    
                    if ($index <= $lastReadMessageIndex) {
                        $readAt = $msgData['created_at']->copy()->addMinutes(rand(1, 45));
                        if ($readAt->isFuture()) {
                            $readAt = now();
                        }
                        
                        MessageRead::create([
                            'message_id' => $msgData['id'],
                            'participant_type' => $readerType,
                            'participant_id' => $readerId,
                            'read_at' => $readAt,
                            'created_at' => $readAt,
                            'updated_at' => $readAt,
                        ]);
                    }
                }
            }
        }
    }
}