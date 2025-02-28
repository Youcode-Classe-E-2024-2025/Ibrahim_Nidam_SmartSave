<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'profile_pin', 'role','color'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function savingGoals()
    {
        return $this->hasMany(SavingGoal::class);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

}
