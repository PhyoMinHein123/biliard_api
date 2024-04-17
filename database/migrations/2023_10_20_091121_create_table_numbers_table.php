<?php

use App\Enums\TableStatusEnum;
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
        Schema::create('table_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedInteger('cashier_id');
            $table->string('status')->default(TableStatusEnum::SUCCESS->value);
            $table->auditColumns();

            $table->foreign('cashier_id')
                ->references('id')
                ->on('cashiers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_numbers');
    }
};
