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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id('budget_id');
            $table->unsignedBigInteger('userid'); // FK
            $table->decimal('budget_amount', 10, 2);
            $table->string('month'); // e.g., 'January' or '01'
            $table->year('year'); 
            $table->foreign('userid')->references('userid')->on('user')->onDelete('cascade');


            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
