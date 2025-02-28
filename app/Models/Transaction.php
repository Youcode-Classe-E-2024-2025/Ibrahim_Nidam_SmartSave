<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;
    
    protected $fillable = [
        'profile_id',
        'category_id',
        'date',
        'amount',
        'description',
        'type', // 'INCOME' or 'EXPENSE'
    ];

    protected $casts = [
        'date' => 'datetime', // ✅ This ensures `date` is treated as a Carbon instance
        'amount' => 'float', // Optional: Ensure amount is treated as float
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // In App\Models\Transaction.php

    public function getTypeAttribute()
    {
        return $this->attributes['type'];
    }

}
