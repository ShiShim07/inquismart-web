<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'staff_id', 
        'subject', 
        'description',
        'status', 
        'sentiment', 
        'staff_response', 
        'responded_at',
        'ticket_number'
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

    // ==================== TICKET NUMBER GENERATION ====================
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $year = date('Y');

            // Count existing tickets this year, then +1
            $count = self::whereYear('created_at', $year)->count() + 1;

            $ticket->ticket_number = 'TKT-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        });
    }

    // ==================== ACCESSORS ====================
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