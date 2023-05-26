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
        Schema::create('delay_reports_agents', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('delay_report_id');
            $table->foreign('delay_report_id')->references('id')
                ->on('delay_reports')->onDelete('cascade');

            $table->unsignedBigInteger('agent_id');
            $table->foreign('agent_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->string('status')->default(\App\enums\DelayReportStatusEnum::PROCESSING->value);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delay_reports_agents');
    }
};
