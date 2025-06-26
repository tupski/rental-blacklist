<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            AttributeSeeder::class,
            FooterWidgetSeeder::class,
            RentalBlacklistSeeder::class,
            UserIdentitySeeder::class,
            BlacklistReportsSeeder::class,
            GuestReportsSeeder::class,
            AiProviderSeeder::class,
            ChatbotKnowledgeBaseSeeder::class,
        ]);
    }
}
