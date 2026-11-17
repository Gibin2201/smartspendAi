<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddIncome extends Model
{
    protected $primaryKey = 'income_id';
    protected $fillable = ['incomeamount', 'incomecategory_id', 'income_date', 'Description', 'userid'];

    public function category()
    {
        // Explicitly pass 'IncomeCategory' as the table name to prevent Laravel from guessing 'income_categories'
        return $this->belongsTo(IncomeCategory::class, 'incomecategory_id', 'incomecategory_id');
    }

    // Relationship to user (using 'userid' as the owner key)
    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'userid');
    }
}