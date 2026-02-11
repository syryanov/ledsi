<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        // Валидируем данные
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Проверка логина и пароля
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Неверный логин или пароль'],
            ]);
        }

        // Генерация идентификатора сессии
        $request->session()->regenerate();

        // Возвращаем информацию о пользователе
        return response()->json(Auth::user());
    }

    public function logout(Request $request): JsonResponse
    {
        // Выход из системы
        Auth::logout();

        // Инвалидируем сессию
        $request->session()->invalidate();

        // Регенерируем CSRF-токен
        $request->session()->regenerateToken();

        // Возвращаем сообщение об успешном выходе
        return response()->json(['message' => 'Logged out']);
    }
}