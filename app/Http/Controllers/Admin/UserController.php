<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Список пользователей
     */
    public function index()
    {
        $users = User::withCount('orders')->orderBy('id', 'desc')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Детали пользователя
     */
    public function show(User $user)
    {
        $user->load('orders');
        $totalSpent = $user->orders->sum('total_amount');
        $ordersCount = $user->orders->count();

        return view('admin.users.show', compact('user', 'totalSpent', 'ordersCount'));
    }

    /**
     * Форма редактирования пользователя
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Обновление пользователя
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь "' . $user->name . '" успешно обновлен');
    }

    /**
     * Удаление пользователя
     */
    public function destroy(User $user)
    {
        // Не даем удалить самого себя
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Вы не можете удалить свой собственный аккаунт');
        }

        // Проверяем, есть ли у пользователя заказы
        if ($user->orders()->count() > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Нельзя удалить пользователя, у которого есть заказы');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь "' . $userName . '" удален');
    }

    /**
     * Блокировка/разблокировка пользователя (дополнительно)
     */
    public function toggleBlock(User $user)
    {
        // Не даем заблокировать самого себя
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Вы не можете заблокировать свой собственный аккаунт');
        }

        // Меняем статус (если есть поле is_blocked, нужно добавить миграцию)
        // Пока просто заглушка
        return redirect()->route('admin.users.index')
            ->with('info', 'Функция блокировки в разработке');
    }
}
