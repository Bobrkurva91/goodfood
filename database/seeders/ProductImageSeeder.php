<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            // Пицца (папка pizza)
            'margarita' => '/images/products/pizza/margarita.jpg',
            'pepperoni' => '/images/products/pizza/pepperoni.jpg',
            'hawaiian' => '/images/products/pizza/hawaiian.jpg',
            'meat' => '/images/products/pizza/meat.jpg',
            'four-cheese' => '/images/products/pizza/four-cheese.jpg',
            'carbonara' => '/images/products/pizza/carbonara.jpg',
            
            // Бургеры (папка burger)
            'cheeseburger' => '/images/products/burger/cheeseburger.jpg',
            'double-burger' => '/images/products/burger/double-burger.jpg',
            'chicken-burger' => '/images/products/burger/chicken-burger.jpg',
            'spicy-burger' => '/images/products/burger/spicy-burger.jpg',
            'veggie-burger' => '/images/products/burger/veggie-burger.jpg',
            'bacon-burger' => '/images/products/burger/bacon-burger.jpg',
            
            // Суши (папка sushi)
            'philadelphia' => '/images/products/sushi/philadelphia.jpg',
            'california' => '/images/products/sushi/california.jpg',
            'unagi' => '/images/products/sushi/unagi.jpg',
            'spice-tuna' => '/images/products/sushi/spice-tuna.jpg',
            'tempura' => '/images/products/sushi/tempura.jpg',
            'syake-maki' => '/images/products/sushi/syake-maki.jpg',
            
            // Напитки (папка drinks)
            'lemonade' => '/images/products/drinks/lemonade.jpg',
            'cranberry-juice' => '/images/products/drinks/cranberry-juice.jpg',
            'cola' => '/images/products/drinks/cola.jpg',
            'ice-tea' => '/images/products/drinks/ice-tea.jpg',
            'berry-smoothie' => '/images/products/drinks/berry-smoothie.jpg',
            'milkshake' => '/images/products/drinks/milkshake.jpg',
            
            // Десерты (папка dessert)
            'cheesecake' => '/images/products/dessert/cheesecake.jpg',
            'tiramisu' => '/images/products/dessert/tiramisu.jpg',
            'macarons' => '/images/products/dessert/macarons.jpg',
            'ice-cream' => '/images/products/dessert/ice-cream.jpg',
            'brownie' => '/images/products/dessert/brownie.jpg',
            'panna-cotta' => '/images/products/dessert/panna-cotta.jpg',
        ];
        
        foreach ($images as $slug => $imagePath) {
            $product = Product::where('slug', $slug)->first();
            if ($product) {
                $product->image = $imagePath;
                $product->save();
                $this->command->info("✅ Обновлено: {$product->name} -> {$imagePath}");
            } else {
                $this->command->error("❌ Товар не найден: {$slug}");
            }
        }
        
        $this->command->info('Готово! Обновлено ' . count($images) . ' товаров');
    }
}