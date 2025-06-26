<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove all users with role 'user' (regular users)
        DB::table('users')->where('role', 'user')->delete();
        
        // Remove user_unlocks table if exists (since we removed balance system)
        if (Schema::hasTable('user_unlocks')) {
            Schema::dropIfExists('user_unlocks');
        }
        
        // Update users table to remove balance-related fields if they exist
        Schema::table('users', function (Blueprint $table) {
            // Remove any balance-related columns that might still exist
            if (Schema::hasColumn('users', 'balance')) {
                $table->dropColumn('balance');
            }
            if (Schema::hasColumn('users', 'last_balance_update')) {
                $table->dropColumn('last_balance_update');
            }
        });
        
        // Add settings for terms and privacy policy if not exists
        $settings = [
            [
                'key' => 'terms_of_service',
                'value' => '<h2>Syarat dan Ketentuan</h2><p>Konten syarat dan ketentuan akan diatur melalui admin panel.</p>',
                'type' => 'textarea',
                'group' => 'legal',
                'label' => 'Syarat dan Ketentuan',
                'description' => 'Konten halaman syarat dan ketentuan'
            ],
            [
                'key' => 'privacy_policy',
                'value' => '<h2>Kebijakan Privasi</h2><p>Konten kebijakan privasi akan diatur melalui admin panel.</p>',
                'type' => 'textarea',
                'group' => 'legal',
                'label' => 'Kebijakan Privasi',
                'description' => 'Konten halaman kebijakan privasi'
            ],
            [
                'key' => 'show_terms_link',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'legal',
                'label' => 'Tampilkan Link Syarat & Ketentuan',
                'description' => 'Tampilkan link syarat dan ketentuan di footer'
            ],
            [
                'key' => 'show_privacy_link',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'legal',
                'label' => 'Tampilkan Link Kebijakan Privasi',
                'description' => 'Tampilkan link kebijakan privasi di footer'
            ]
        ];
        
        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the legal settings
        DB::table('settings')->whereIn('key', [
            'terms_of_service',
            'privacy_policy', 
            'show_terms_link',
            'show_privacy_link'
        ])->delete();
        
        // Recreate user_unlocks table if needed
        Schema::create('user_unlocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('blacklist_id')->constrained('rental_blacklist')->onDelete('cascade');
            $table->decimal('amount_paid', 10, 2);
            $table->timestamp('unlocked_at');
            $table->timestamps();
            
            $table->unique(['user_id', 'blacklist_id']);
        });
    }
};
