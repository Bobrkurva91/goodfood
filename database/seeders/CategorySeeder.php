<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $categories = [
        ['name' => 'Пицца', 'slug' => 'pizza', 'description' => 'Итальянская пицца на тонком тесте'],
        ['name' => 'Бургеры', 'slug' => 'burgers', 'description' => 'Сочные бургеры из натуральной говядины'],
        ['name' => 'Суши', 'slug' => 'sushi', 'description' => 'Свежие роллы и суши'],
        ['name' => 'Напитки', 'slug' => 'drinks', 'description' => 'Прохладительные и горячие напитки'],
        ['name' => 'Десерты', 'slug' => 'desserts', 'description' => 'Сладкие угощения'],
    ];

    foreach ($categories as $category) {
        \App\Models\Category::create($category);
    }
}
}
