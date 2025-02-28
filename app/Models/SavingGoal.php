<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingGoal extends Model
{
    /** @use HasFactory<\Database\Factories\SavingGoalFactory> */
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'title',
        'target_amount',
        'current_amount',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    // Example method from your class diagram
    public function calculateProgress()
    {
        if ($this->target_amount == 0) {
            return 0;
        }
        return ($this->current_amount / $this->target_amount) * 100;
    }
}
