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
        Schema::create('user_register_referrel_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_ref_by'); // ID of the user who referred
            $table->unsignedBigInteger('user_id'); // ID of the referred user
            $table->boolean('payed_status')->default(false); // Payed status, default to false
            $table->decimal('amount', 10, 2)->default(0); // Amount, default to 0
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_ref_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_register_referrel_history');
    }
};
