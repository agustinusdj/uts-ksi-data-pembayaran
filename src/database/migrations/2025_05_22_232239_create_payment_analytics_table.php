<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_gateway_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->integer('total_transactions')->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('total_fee', 15, 2)->default(0);
            $table->integer('success_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->integer('pending_count')->default(0);
            $table->decimal('success_rate', 5, 2)->default(0);
            $table->integer('avg_processing_time')->default(0); // in seconds
            $table->timestamps();

            $table->unique(['payment_gateway_id', 'date']);
            $table->index(['date', 'payment_gateway_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_analytics');
    }
};
