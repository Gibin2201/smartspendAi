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
        Schema::create('add_incomes', function (Blueprint $table) {
            $table->id('income_id');
            $table->unsignedBigInteger('incomecategory_id'); // FK
            $table->decimal('incomeamount', 10, 2);
            $table->date('income_date');
            $table->text('Description')->nullable();
            $table->unsignedBigInteger('userid'); //FK
            $table->timestamps();

        $table->foreign('userid')->references('userid')->on('user')->onDelete('cascade');
        $table->foreign('incomecategory_id')->references('incomecategory_id')->on('income_categories')->onDelete('cascade');

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_incomes');
    }
};
