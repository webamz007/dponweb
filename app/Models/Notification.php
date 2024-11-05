<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('user_id');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId)->orWhereNull('user_id');
    }

    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class);
    }
}
