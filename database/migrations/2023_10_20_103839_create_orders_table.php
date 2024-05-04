<?php

use App\Enums\OrderStatusEnum;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('table_number_id');
            $table->unsignedBigInteger('shop_id');
            $table->json('items')->nullable();
            $table->datetime('checkin');
            $table->datetime('checkout')->nullable();
            $table->unsignedInteger('table_charge')->nullable();
            $table->unsignedInteger('items_charge')->nullable();
            $table->string('total_time')->nullable();
            $table->string('status')->default(OrderStatusEnum::PENDING->value);
            $table->auditColumns();

            $table->foreign('table_number_id')
                ->references('id')
                ->on('table_numbers')
                ->onDelete('cascade');

            $table->foreign('shop_id')
                ->references('id')
                ->on('shops')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
