<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /** @var User[] $users */
        $users = [];
        $tokens = [];

        $users[] = User::factory()->create([
            'name' => 'admin',
            'email' => 'contact@protean.pl'
        ]);

        foreach ($users as $user) {
            $tokens[] = $user->createToken('default');
        }

        $rows = [];
        foreach ($users as $index => $user) {
            $rows[] = [
                $user->id, $user->email, $tokens[$index]->plainTextToken
            ];
        }

        $this->command->table([
            "ID", "Email", "Token"
        ], $rows);
    }
}
