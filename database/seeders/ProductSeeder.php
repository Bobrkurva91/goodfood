<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Пицца (category_id = 1)
            ['category_id' => 1, 'name' => 'Маргарита', 'slug' => 'margarita', 'description' => 'Классическая итальянская пицца с томатным соусом, моцареллой и базиликом', 'price' => 450, 'stock' => 10, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            ['category_id' => 1, 'name' => 'Пепперони', 'slug' => 'pepperoni', 'description' => 'Острая пицца с пепперони, томатным соусом и моцареллой', 'price' => 550, 'stock' => 8, 'is_active' => true, 'is_new' => false, 'is_on_sale' => true],
            ['category_id' => 1, 'name' => 'Гавайская', 'slug' => 'hawaiian', 'description' => 'Курица, ананас, моцарелла, томатный соус', 'price' => 520, 'stock' => 7, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            ['category_id' => 1, 'name' => 'Мясная', 'slug' => 'meat', 'description' => 'Ветчина, пепперони, бекон, курица, моцарелла', 'price' => 620, 'stock' => 5, 'is_active' => true, 'is_new' => true, 'is_on_sale' => false],
            ['category_id' => 1, 'name' => 'Четыре сыра', 'slug' => 'four-cheese', 'description' => 'Моцарелла, пармезан, горгонзола, дор блю', 'price' => 590, 'stock' => 6, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            ['category_id' => 1, 'name' => 'Карбонара', 'slug' => 'carbonara', 'description' => 'Бекон, сливочный соус, моцарелла, пармезан', 'price' => 580, 'stock' => 4, 'is_active' => true, 'is_new' => true, 'is_on_sale' => false],
            
            // Бургеры (category_id = 2)
            ['category_id' => 2, 'name' => 'Чизбургер', 'slug' => 'cheeseburger', 'description' => 'Говяжья котлета, сыр чеддер, салат, помидор, соус', 'price' => 320, 'stock' => 15, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            ['category_id' => 2, 'name' => 'Двойной бургер', 'slug' => 'double-burger', 'description' => 'Две говяжьи котлеты, двойной сыр, бекон, соус BBQ', 'price' => 450, 'stock' => 10, 'is_active' => true, 'is_new' => false, 'is_on_sale' => true],
            ['category_id' => 2, 'name' => 'Куриный бургер', 'slug' => 'chicken-burger', 'description' => 'Куриная котлета, айсберг, помидор, соус цезарь', 'price' => 290, 'stock' => 12, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            ['category_id' => 2, 'name' => 'Острый бургер', 'slug' => 'spicy-burger', 'description' => 'Говяжья котлета, халапеньо, острый соус, сыр', 'price' => 380, 'stock' => 8, 'is_active' => true, 'is_new' => true, 'is_on_sale' => false],
            ['category_id' => 2, 'name' => 'Вегетарианский', 'slug' => 'veggie-burger', 'description' => 'Котлета из нута, авокадо, салат, томаты', 'price' => 340, 'stock' => 6, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            ['category_id' => 2, 'name' => 'Бургер с беконом', 'slug' => 'bacon-burger', 'description' => 'Говяжья котлета, бекон, сыр, карамелизированный лук', 'price' => 420, 'stock' => 7, 'is_active' => true, 'is_new' => false, 'is_on_sale' => true],
            
            // Суши (category_id = 3)
            ['category_id' => 3, 'name' => 'Филадельфия', 'slug' => 'philadelphia', 'description' => 'Лосось, сливочный сыр, огурец, авокадо', 'price' => 650, 'stock' => 5, 'is_active' => true, 'is_new' => true, 'is_on_sale' => false],
            ['category_id' => 3, 'name' => 'Калифорния', 'slug' => 'california', 'description' => 'Краб, авокадо, огурец, икра тобико', 'price' => 580, 'stock' => 6, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            ['category_id' => 3, 'name' => 'Унаги', 'slug' => 'unagi', 'description' => 'Угорь, авокадо, соус унаги', 'price' => 720, 'stock' => 4, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            ['category_id' => 3, 'name' => 'Спайс туна', 'slug' => 'spice-tuna', 'description' => 'Тунец, острый соус, огурец', 'price' => 590, 'stock' => 7, 'is_active' => true, 'is_new' => false, 'is_on_sale' => true],
            ['category_id' => 3, 'name' => 'Темпура', 'slug' => 'tempura', 'description' => 'Креветка темпура, огурец, соус спайси', 'price' => 680, 'stock' => 5, 'is_active' => true, 'is_new' => true, 'is_on_sale' => false],
            ['category_id' => 3, 'name' => 'Сяке маки', 'slug' => 'syake-maki', 'description' => 'Лосось, огурец, сливочный сыр', 'price' => 540, 'stock' => 8, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            
            // Напитки (category_id = 4)
            ['category_id' => 4, 'name' => 'Лимонад', 'slug' => 'lemonade', 'description' => 'Домашний лимонад с мятой и лимоном', 'price' => 180, 'stock' => 20, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            ['category_id' => 4, 'name' => 'Морс клюквенный', 'slug' => 'cranberry-juice', 'description' => 'Натуральный клюквенный морс', 'price' => 150, 'stock' => 15, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            ['category_id' => 4, 'name' => 'Кола', 'slug' => 'cola', 'description' => 'Кока-кола 0.5л', 'price' => 120, 'stock' => 25, 'is_active' => true, 'is_new' => false, 'is_on_sale' => true],
            ['category_id' => 4, 'name' => 'Чай холодный', 'slug' => 'ice-tea', 'description' => 'Холодный чай с лимоном', 'price' => 130, 'stock' => 18, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            ['category_id' => 4, 'name' => 'Смузи ягодный', 'slug' => 'berry-smoothie', 'description' => 'Смузи из свежих ягод', 'price' => 220, 'stock' => 10, 'is_active' => true, 'is_new' => true, 'is_on_sale' => false],
            ['category_id' => 4, 'name' => 'Молочный коктейль', 'slug' => 'milkshake', 'description' => 'Классический молочный коктейль', 'price' => 190, 'stock' => 12, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            
            // Десерты (category_id = 5)
            ['category_id' => 5, 'name' => 'Чизкейк', 'slug' => 'cheesecake', 'description' => 'Нью-Йорк чизкейк с ягодным соусом', 'price' => 280, 'stock' => 8, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            ['category_id' => 5, 'name' => 'Тирамису', 'slug' => 'tiramisu', 'description' => 'Классический итальянский десерт', 'price' => 320, 'stock' => 6, 'is_active' => true, 'is_new' => true, 'is_on_sale' => false],
            ['category_id' => 5, 'name' => 'Макаруны', 'slug' => 'macarons', 'description' => 'Набор французских макарунов 6 шт', 'price' => 350, 'stock' => 5, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            ['category_id' => 5, 'name' => 'Мороженое', 'slug' => 'ice-cream', 'description' => 'Пломбир с шоколадной крошкой', 'price' => 150, 'stock' => 15, 'is_active' => true, 'is_new' => false, 'is_on_sale' => true],
            ['category_id' => 5, 'name' => 'Брауни', 'slug' => 'brownie', 'description' => 'Шоколадный брауни с орехами', 'price' => 190, 'stock' => 10, 'is_active' => true, 'is_new' => false, 'is_on_sale' => false],
            ['category_id' => 5, 'name' => 'Панна котта', 'slug' => 'panna-cotta', 'description' => 'Нежный десерт с ягодным соусом', 'price' => 240, 'stock' => 7, 'is_active' => true, 'is_new' => true, 'is_on_sale' => false],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}