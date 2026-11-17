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
        Schema::create('add_expenses', function (Blueprint $table) {
            $table->id('expense_id'); 
            $table->unsignedBigInteger('expensecategory_id'); // FK
            $table->decimal('expenseamount', 10, 2);
            $table->date('expense_date');
            $table->text('Description')->nullable();
            $table->unsignedBigInteger('userid'); //FK
            $table->timestamps();

        $table->foreign('userid')->references('userid')->on('user')->onDelete('cascade');
        $table->foreign('expensecategory_id')->references('expensecategory_id')->on('expense_categories')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_expenses');
    }
};
