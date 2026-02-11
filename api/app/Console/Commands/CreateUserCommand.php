<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUserCommand extends Command
{
    protected $signature = 'user:create';

    protected $description = 'Создать нового пользователя';

    public function handle()
    {
        $name = $this->ask('Введите имя пользователя');
        $email = $this->ask('Введите email');
        $password = $this->secret('Введите пароль');
        $role = $this->ask('Введите роль (customer|admin)');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:customer,admin',
        ]);

        if ($validator->fails()) {
            $this->error('Ошибки валидации: ' . implode(', ', $validator->errors()->all()));

            return;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $role,
        ]);

        $this->info("Пользователь '{$user->name}' (ID: {$user->id}) успешно создан!");
    }
}