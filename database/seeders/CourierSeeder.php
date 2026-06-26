<?php

namespace Database\Seeders;

use App\Models\Courier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CourierSeeder extends Seeder
{
    public function run(): void
    {
        $couriers = [
            [
                'name' => 'Пётр',
                'phone' => '+7 (999) 111-22-33',
                'email' => 'petr@goodfood.ru',
                'password' => Hash::make('password123'),
                'vehicle' => 'Toyota Corolla (белый)',
                'is_active' => true,
            ],
            [
                'name' => 'Иван',
                'phone' => '+7 (999) 444-55-66',
                'email' => 'ivan@goodfood.ru',
                'password' => Hash::make('password123'),
                'vehicle' => 'Hyundai Solaris (серый)',
                'is_active' => true,
            ],
        ];

        foreach ($couriers as $courier) {
            Courier::create($courier);
        }

        $this->command->info('✅ Курьеры добавлены: Пётр и Иван');
    }
}
