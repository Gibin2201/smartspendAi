<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $primaryKey = 'budget_id';
    protected $fillable = ['budget_amount', 'month', 'year', 'userid'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'userid');
    }
}