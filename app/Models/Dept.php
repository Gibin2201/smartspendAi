<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dept extends Model
{
    protected $primaryKey = 'dept_id';
    protected $fillable = ['name', 'purpose', 'debt_amount', 'fromdate', 'todate', 'debt_type', 'status', 'userid'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'userid');
    }
}
