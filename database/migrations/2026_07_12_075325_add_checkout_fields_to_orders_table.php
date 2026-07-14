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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_type', ['delivery', 'pickup'])->default('delivery')->after('id');
            $table->text('delivery_address')->nullable()->after('order_type');
            $table->date('delivery_date')->nullable()->after('delivery_address');
            $table->string('delivery_time')->nullable()->after('delivery_date');
            $table->date('pickup_date')->nullable()->after('delivery_time');
            $table->string('pickup_time')->nullable()->after('pickup_date');
            $table->text('preparation_note')->nullable()->after('payment_method');
            $table->text('delivery_note')->nullable()->after('preparation_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_type',
                'delivery_address',
                'delivery_date',
                'delivery_time',
                'pickup_date',
                'pickup_time',
                'preparation_note',
                'delivery_note'
            ]);
        });
    }
};
