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

    // Example method from your class diagram
    public function checkStatus()
    {
        // Possibly compare sum of expenses for the month vs monthly_budget
        // Return e.g. 'OK' or 'Exceeded'
    }

    public function optimizeBudget()
    {
        // Some logic to redistribute amounts or give suggestions
    }
}
