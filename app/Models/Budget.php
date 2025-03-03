<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    /** @use HasFactory<\Database\Factories\BudgetFactory> */
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'monthly_budget',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

}
