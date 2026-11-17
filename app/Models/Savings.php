<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Savings extends Model
{
    protected $primaryKey = 'savings_id';
    protected $fillable = [ 'userid', 'target_amount', 'saving_purpose', 'saved_amount', 'end_date'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'userid');
    }
}
