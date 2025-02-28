<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavingGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'title',
        'target_amount',
        'current_amount',
        'description',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    /**
     * Get the profile that owns the saving goal.
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}