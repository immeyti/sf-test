<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function trip(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Trip::class);
    }

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function delayReports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DelayReport::class);
    }

    protected function getExpectedDeliveryTimeAttribute()
    {
        return $this->created_at->addMinutes($this->delivery_time);
    }
}
