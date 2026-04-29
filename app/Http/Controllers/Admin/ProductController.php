<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Список товаров
     */
    public function index()
    {
        $products = Product::with('category')->orderBy('id', 'desc')->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Форма создания товара
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Сохранение товара
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_new' => 'boolean',
            'is_on_sale' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = Str::slug($request->name);

        // Проверяем уникальность slug
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $slug;
        $product->category_id = $request->category_id;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->is_active = $request->has('is_active');
        $product->is_new = $request->has('is_new');
        $product->is_on_sale = $request->has('is_on_sale');

        // Загрузка изображения
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $slug . '.' . $image->getClientOriginalExtension();

            // Определяем папку по категории
            $category = Category::find($request->category_id);
            $folder = $category ? $category->slug : 'products';

            $image->move(public_path('images/products/' . $folder), $imageName);
            $product->image = '/images/products/' . $folder . '/' . $imageName;
        }

        $product->save();

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар "' . $product->name . '" успешно добавлен');
    }

    /**
     * Форма редактирования товара
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Обновление товара
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_new' => 'boolean',
            'is_on_sale' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Обновляем slug только если изменилось название
        if ($product->name !== $request->name) {
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $count = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $product->slug = $slug;
        }

        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->is_active = $request->has('is_active');
        $product->is_new = $request->has('is_new');
        $product->is_on_sale = $request->has('is_on_sale');

        // Загрузка нового изображения
        if ($request->hasFile('image')) {
            // Удаляем старое изображение, если оно есть
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $product->slug . '.' . $image->getClientOriginalExtension();

            $category = Category::find($request->category_id);
            $folder = $category ? $category->slug : 'products';

            $image->move(public_path('images/products/' . $folder), $imageName);
            $product->image = '/images/products/' . $folder . '/' . $imageName;
        }

        $product->save();

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар "' . $product->name . '" успешно обновлен');
    }

    /**
     * Удаление товара
     */
    public function destroy(Product $product)
    {
        // Удаляем изображение
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $productName = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар "' . $productName . '" удален');
    }
}
