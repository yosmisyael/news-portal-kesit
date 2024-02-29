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
        Schema::create('suspensions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('submission_id')->unique();
            $table->text('violation');
            $table->timestamps();

            $table->foreign('submission_id')->references('id')->on('submissions')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suspensions');
    }
};
