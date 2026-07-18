<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete()->after('user_id');
            $table->unsignedTinyInteger('rider_rating')->nullable()->after('comment');
            $table->text('rider_comment')->nullable()->after('rider_rating');
            $table->json('media')->nullable()->after('rider_comment');
        });

        // Make product_id nullable
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn(['order_id', 'rider_rating', 'rider_comment', 'media']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable(false)->change();
        });
    }
};
