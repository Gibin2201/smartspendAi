<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingsTransaction extends Model
{
    //protected $table = 'savings_transactions';
    protected $primaryKey = 'transaction_id';
    
    protected $fillable = [
        'savings_id',
        'userid',
        'deposit_amount',
    ];


    public function savingsGoal()
    {
        return $this->belongsTo(Savings::class, 'savings_id', 'savings_id');
    }
}
