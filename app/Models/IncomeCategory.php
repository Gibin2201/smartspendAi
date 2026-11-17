<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class IncomeCategory extends Model
{
    use HasFactory;
    protected $primaryKey = 'incomecategory_id';
    protected $fillable = ['incomecategory','is_borrow',];
}
