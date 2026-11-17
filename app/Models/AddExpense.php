<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddExpense extends Model
{
    protected $primaryKey = 'expense_id';
    protected $fillable = ['expenseamount', 'expensecategory_id', 'expense_date', 'Description', 'userid'];

    // Relationship to category
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expensecategory_id', 'expensecategory_id');
    }

    // Relationship to user (using 'userid' as the owner key)
    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'userid');
    }
}