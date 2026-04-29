<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->onDelete('cascade'); // связь с категорией
        $table->string('name'); // название товара
        $table->string('slug')->unique(); // URL-другое имя
        $table->text('description'); // описание
        $table->decimal('price', 10, 2); // цена (10 цифр всего, 2 после запятой)
        $table->integer('stock')->default(0); // количество на складе
        $table->string('image')->nullable(); // фото товара
        $table->boolean('is_active')->default(true); // активен/скрыт
        $table->boolean('is_new')->default(false); // новинка
        $table->boolean('is_on_sale')->default(false); // на акции
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
