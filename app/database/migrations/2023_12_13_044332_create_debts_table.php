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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();

            $table->string('code');
            $table->string('status');
            $table->string('api_client');
            $table->string('ccf_code');
            $table->string('ccf_client_id');
            $table->string('ccf_client_data');
            $table->string('client_origin_id');
            $table->string('subdebts');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};