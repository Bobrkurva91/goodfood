<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Список категорий
     */
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('id', 'desc')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Форма создания категории
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Сохранение категории
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = Str::slug($request->name);

        // Проверяем уникальность slug
        $originalSlug = $slug;
        $count = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $slug;
        $category->description = $request->description;

        // Загрузка изображения
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $slug . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/categories'), $imageName);
            $category->image = '/images/categories/' . $imageName;
        }

        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория "' . $category->name . '" успешно добавлена');
    }

    /**
     * Форма редактирования категории
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Обновление категории
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Обновляем slug только если изменилось название
        if ($category->name !== $request->name) {
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $count = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $category->slug = $slug;
        }

        $category->name = $request->name;
        $category->description = $request->description;

        // Загрузка нового изображения
        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($category->image && file_exists(public_path($category->image))) {
                unlink(public_path($category->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $category->slug . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/categories'), $imageName);
            $category->image = '/images/categories/' . $imageName;
        }

        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория "' . $category->name . '" успешно обновлена');
    }

    /**
     * Удаление категории
     */
    public function destroy(Category $category)
    {
        // Проверяем, есть ли товары в этой категории
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Нельзя удалить категорию, в которой есть товары. Сначала переместите или удалите товары.');
        }

        // Удаляем изображение
        if ($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория "' . $categoryName . '" удалена');
    }
}
