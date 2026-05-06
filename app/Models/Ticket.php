<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'staff_id', 'subject', 'description',
        'status', 'sentiment', 'staff_response', 'responded_at'
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function sentimentLog()
    {
        return $this->hasOne(SentimentLog::class);
    }

    public function getSentimentColorAttribute()
    {
        return match($this->sentiment) {
            'Urgent' => 'danger',
            'Frustrated' => 'warning',
            'Neutral' => 'info',
            default => 'secondary'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Resolved' => 'success',
            'Processing' => 'warning',
            'Pending' => 'primary',
            default => 'secondary'
        };
    }
}