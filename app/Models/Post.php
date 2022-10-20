<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    const INACTIVE = 0, ACTIVE = 1, REJECTED = 2;

    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function comments() {
        return $this->hasMany(PostComment::class, 'post_id', 'id')->with('user');
    }

    public function scopeWhereStatus($query, $status)
    {
        if ($status == null) {
            return $query->whereNot('status', Post::REJECTED);
        } else if ($status == '*') {
            return $query;
        }
        return $query->where('status', $status);
    }

    public function scopeWhereUserId($query, $userId)
    {
        if (!$userId) return $query;
        return $query->where('user_id', $userId);
    }

    public function scopeWhereCreatedBetween($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

            return $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query;
    }
}
