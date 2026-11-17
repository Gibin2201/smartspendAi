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
        Schema::create('savings', function (Blueprint $table) {
            $table->id('savings_id');
            $table->unsignedBigInteger('userid'); 
            $table->decimal('target_amount', 10, 2);
            $table->string('saving_purpose');
            $table->decimal('saved_amount', 10, 2)->default(0.00); 
            $table->date('end_date');
            $table->timestamps();
            $table->foreign('userid')->references('userid')->on('User')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings');
    }
};
