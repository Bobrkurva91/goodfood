<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // Главная страница
    public function index()
{
    $newProducts = Product::where('is_active', true)
        ->where('stock', '>', 0)
        ->where('is_new', true)
        ->limit(6)
        ->get();

    $saleProducts = Product::where('is_active', true)
        ->where('stock', '>', 0)
        ->where('is_on_sale', true)
        ->limit(6)
        ->get();

    return view('shop.index', compact('newProducts', 'saleProducts'));
}

    // Каталог товаров
    public function catalog(Request $request)
{
    $categories = Category::all();

    // Показываем только активные товары и товары в наличии
    $query = Product::where('is_active', true)
        ->where('stock', '>', 0);

    // Фильтр по категории
    if ($request->has('category') && $request->category) {
        $query->where('category_id', $request->category);
    }

    // Сортировка
    $sort = $request->get('sort', 'newest');
    switch ($sort) {
        case 'price_asc':
            $query->orderBy('price', 'asc');
            break;
        case 'price_desc':
            $query->orderBy('price', 'desc');
            break;
        case 'newest':
        default:
            $query->orderBy('created_at', 'desc');
            break;
    }

    $products = $query->paginate(12);
    $selectedCategory = $request->category;

    return view('shop.catalog', compact('products', 'categories', 'selectedCategory', 'sort'));
}

    // Страница одного товара
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Похожие товары из той же категории
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('shop.product', compact('product', 'relatedProducts'));
    }
}
