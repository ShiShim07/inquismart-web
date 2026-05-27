<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Ticket Model — Updated for revised capstone
 *
 * Changes from original:
 * - sentiment: Urgent/Frustrated/Neutral → Positive/Negative/Neutral
 * - Added: processing_at, resolved_at, sentiment_confidence to fillable
 * - Updated getSentimentColorAttribute() for new labels
 * - Updated getSentimentLabelAttribute() for admin views
 */
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
        'sentiment_confidence',  // NEW: high / medium / low
        'staff_response',
        'responded_at',
        'processing_at',         // NEW: when status changed to Processing
        'resolved_at',           // NEW: when status changed to Resolved
        'ticket_number',
    ];

    protected $casts = [
        'responded_at'  => 'datetime',
        'processing_at' => 'datetime',
        'resolved_at'   => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

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
            $year  = date('Y');
            $count = self::whereYear('created_at', $year)->count() + 1;
            $ticket->ticket_number = 'TKT-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        });

        // Auto-set processing_at and resolved_at when status changes
        static::updating(function ($ticket) {
            if ($ticket->isDirty('status')) {
                if ($ticket->status === 'Processing' && !$ticket->processing_at) {
                    $ticket->processing_at = now();
                }
                if ($ticket->status === 'Resolved' && !$ticket->resolved_at) {
                    $ticket->resolved_at = now();
                }
            }
        });
    }

    // ==================== ACCESSORS ====================

    /**
     * Returns Bootstrap color class for sentiment badge.
     * Used in admin Blade views.
     */
    public function getSentimentColorAttribute(): string
    {
        return match ($this->sentiment) {
            'Positive' => 'success',
            'Negative' => 'danger',
            'Neutral'  => 'info',
            // Legacy support — in case old data still has these
            'Urgent'      => 'danger',
            'Frustrated'  => 'warning',
            default       => 'secondary',
        };
    }

    /**
     * Returns Bootstrap color class for status badge.
     * Used in admin Blade views.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'Resolved'   => 'success',
            'Processing' => 'warning',
            'Pending'    => 'primary',
            default      => 'secondary',
        };
    }

    /**
     * Returns a short label with emoji for display in admin views.
     */
    public function getSentimentLabelAttribute(): string
    {
        return match ($this->sentiment) {
            'Positive' => '😊 Positive',
            'Negative' => '😠 Negative',
            'Neutral'  => '😐 Neutral',
            default    => $this->sentiment,
        };
    }

    // ==================== SCOPES ====================

    public function scopePositive($query)
    {
        return $query->where('sentiment', 'Positive');
    }

    public function scopeNegative($query)
    {
        return $query->where('sentiment', 'Negative');
    }

    public function scopeNeutral($query)
    {
        return $query->where('sentiment', 'Neutral');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'Processing');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'Resolved');
    }
}