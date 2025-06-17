<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'guest_name',
        'guest_email',
        'guest_website',
        'content',
        'status',
        'ip_address',
        'user_agent',
        'moderated_by',
        'moderated_at',
        'moderation_reason',
    ];

    protected $casts = [
        'moderated_at' => 'datetime',
    ];

    /**
     * Get the post that owns the comment
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'post_id');
    }

    /**
     * Get the user that owns the comment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    /**
     * Get the child comments
     */
    public function replies(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'parent_id');
    }

    /**
     * Get the moderator
     */
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    /**
     * Scope for approved comments
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for pending comments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for top-level comments (not replies)
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get comment author name
     */
    public function getAuthorNameAttribute(): string
    {
        return $this->user ? $this->user->name : $this->guest_name;
    }

    /**
     * Get comment author email
     */
    public function getAuthorEmailAttribute(): string
    {
        return $this->user ? $this->user->email : $this->guest_email;
    }

    /**
     * Check if comment is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if comment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if comment is from registered user
     */
    public function isFromUser(): bool
    {
        return !is_null($this->user_id);
    }

    /**
     * Check if comment is from guest
     */
    public function isFromGuest(): bool
    {
        return is_null($this->user_id);
    }

    /**
     * Approve the comment
     */
    public function approve($moderatorId = null): void
    {
        $this->update([
            'status' => 'approved',
            'moderated_by' => $moderatorId,
            'moderated_at' => now(),
        ]);

        // Update post comments count
        $this->post->increment('comments_count');
    }

    /**
     * Reject the comment
     */
    public function reject($moderatorId = null, $reason = null): void
    {
        $this->update([
            'status' => 'rejected',
            'moderated_by' => $moderatorId,
            'moderated_at' => now(),
            'moderation_reason' => $reason,
        ]);
    }

    /**
     * Mark as spam
     */
    public function markAsSpam($moderatorId = null): void
    {
        $this->update([
            'status' => 'spam',
            'moderated_by' => $moderatorId,
            'moderated_at' => now(),
        ]);
    }
}
