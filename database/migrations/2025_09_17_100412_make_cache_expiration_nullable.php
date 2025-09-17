<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: alter columns to allow NULL
        DB::statement('ALTER TABLE `cache` MODIFY `expiration` INT NULL');
        DB::statement('ALTER TABLE `cache_locks` MODIFY `expiration` INT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `cache` MODIFY `expiration` INT NOT NULL');
        DB::statement('ALTER TABLE `cache_locks` MODIFY `expiration` INT NOT NULL');
    }
};
