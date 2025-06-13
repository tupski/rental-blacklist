<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class ApiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create API keys for existing users
        $users = User::whereDoesntHave('apiKeys')->get();

        foreach ($users as $user) {
            $user->createApiKey('Default API Key');
        }

        $this->command->info('API keys created for ' . $users->count() . ' users.');
    }
}
