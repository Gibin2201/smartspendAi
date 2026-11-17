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
        Schema::create('depts', function (Blueprint $table) {
            $table->id('dept_id');
            $table->string('name');
            $table->string('purpose');
            $table->decimal('debt_amount', 10, 2);
            $table->date('fromdate');
            $table->date('todate');
            
            // Type of debt and status tracking
            $table->enum('debt_type', ['borrow', 'lent']);
            $table->enum('status', ['pending', 'repaid'])->default('pending');
            
            // Foreign Key Configuration
            $table->unsignedBigInteger('userid'); 
            $table->foreign('userid')->references('userid')->on('User')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depts');
    }
};