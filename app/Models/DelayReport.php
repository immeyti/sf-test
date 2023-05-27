<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DelayReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'time'
    ];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function agents()
    {
        return $this->belongsToMany(
            User::class,
            'delay_reports_agents',
            'delay_report_id',
            'agent_id')->withPivot('status');
    }
}
