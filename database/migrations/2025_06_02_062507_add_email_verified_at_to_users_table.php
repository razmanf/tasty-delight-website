<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add nullable timestamp for email verification
            $table->timestamp('email_verified_at')->nullable()->after('password');
            
            // Also add remember_token if missing (often needed for auth)
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken()->after('email_verified_at');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email_verified_at');
            // Only drop remember_token if we added it in this migration
            if (Schema::hasColumn('users', 'remember_token')) {
                $table->dropRememberToken();
            }
        });
    }
};