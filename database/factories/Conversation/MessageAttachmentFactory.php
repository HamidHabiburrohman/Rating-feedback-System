<?php

declare(strict_types=1);

namespace Database\Factories\Conversation;

use App\Models\Conversation\Message;
use App\Models\Conversation\MessageAttachment;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageAttachmentFactory extends Factory
{
    protected $model = MessageAttachment::class;

    public function definition(): array
    {
        $files = [
            ['name' => 'inspection-report.pdf', 'mime' => 'application/pdf', 'ext' => 'pdf'],
            ['name' => 'qr-assignment.xlsx', 'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'ext' => 'xlsx'],
            ['name' => 'laboratory-photo.jpg', 'mime' => 'image/jpeg', 'ext' => 'jpg'],
            ['name' => 'maintenance-document.pdf', 'mime' => 'application/pdf', 'ext' => 'pdf'],
            ['name' => 'schedule-update.docx', 'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'ext' => 'docx'],
            ['name' => 'student-feedback.csv', 'mime' => 'text/csv', 'ext' => 'csv'],
            ['name' => 'unit-banner.png', 'mime' => 'image/png', 'ext' => 'png'],
        ];

        $file = $this->faker->randomElement($files);
        $storedName = $this->faker->uuid() . '.' . $file['ext'];

        return [
            'message_id' => Message::factory(),
            'original_name' => $file['name'],
            'stored_name' => $storedName,
            'mime_type' => $file['mime'],
            'extension' => $file['ext'],
            'size' => $this->faker->numberBetween(50000, 5000000),
            'path' => 'attachments/' . $storedName,
        ];
    }
}