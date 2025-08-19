<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeterReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'billing_month',
        'reading_date',
        'reading',
        'consumption',
        'amount'
    ];

    protected $casts = [
        'reading_date' => 'datetime:Y-m-d',
        'reading' => 'float',
        'consumption' => 'float',
        'amount' => 'float'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
