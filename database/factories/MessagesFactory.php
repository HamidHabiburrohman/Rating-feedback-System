<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $unit = Unit::inRandomOrder()->first() ?? Unit::factory()->create();
        $admin = User::role('admin')->first() ?? User::factory()->create();
        $unitUser = User::role('unit')->first() ?? User::factory()->create();

        $isFromAdmin = $this->faker->boolean(70);

        return [
            'pengirim_tipe' => $isFromAdmin ? 'admin' : 'unit',
            'pengirim_id' => $isFromAdmin ? $admin->id : $unitUser->id,
            'penerima_tipe' => $isFromAdmin ? 'unit' : 'admin',
            'penerima_id' => $isFromAdmin ? $unitUser->id : $admin->id,
            'unit_id' => $unit->id,
            'judul' => $this->faker->sentence(),
            'pesan' => $this->faker->paragraphs(3, true),
            'kategori' => $this->faker->randomElement([
                'technical', 'status_request', 'rating_feedback', 
                'maintenance', 'announcement', 'instruction', 
                'question', 'emergency'
            ]),
            'prioritas' => $this->faker->randomElement(['biasa', 'penting', 'sangat_penting']),
            'status' => $this->faker->randomElement(['terkirim', 'diterima', 'dibaca', 'ditanggapi', 'selesai']),
            'perlu_tindakan' => $this->faker->boolean(30),
            'tipe_tindakan' => $this->faker->optional()->word(),
            'data_tindakan' => $this->faker->optional()->array(),
            'dibaca_pada' => $this->faker->optional()->dateTime(),
            'tindakan_diambil_pada' => $this->faker->optional()->dateTime(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}