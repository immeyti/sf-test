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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('vendor_id');
            $table->foreign('vendor_id')->references('id')
                ->on('vendors')->onDelete('cascade');

            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->unsignedInteger('delivery_time')->nullable();
            $table->timestamps();
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
