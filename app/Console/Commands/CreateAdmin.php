<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create
        {email? : Yönetici e-postası}
        {password? : Parola}
        {--name=Yönetici : Ad}';

    protected $description = 'Admin paneli için yönetici kullanıcı oluşturur/günceller';

    public function handle(): int
    {
        $email = $this->argument('email') ?: $this->ask('E-posta');
        $password = $this->argument('password') ?: $this->secret('Parola');
        $name = $this->option('name');

        if (! $email || ! $password) {
            $this->error('E-posta ve parola gerekli.');
            return self::FAILURE;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make($password), 'is_admin' => true]
        );

        $this->info("Yönetici hazır: {$user->email}");

        return self::SUCCESS;
    }
}
