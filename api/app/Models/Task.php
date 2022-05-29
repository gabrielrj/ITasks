<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, HasUuidGenerator, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'users_id',
        'status',
        'completed_datetime',
    ];

    protected $hidden = [
        'id',
        'users_id',
        'completed_datetime',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    protected $dates = [
        'completed_datetime',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    protected $appends = [
        'registration_date',
        'completed_task',
        'status_task'
    ];

    public function getRegistrationDateAttribute(){
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getCompletedTaskAttribute(){
        return $this->completed_datetime?->format('d/m/Y H:i');
    }

    public function getStatusTaskAttribute(): string
    {
        return strtoupper($this->status);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
