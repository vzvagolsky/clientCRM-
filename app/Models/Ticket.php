<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;


    protected $fillable = [
        'customer_id',
        'subject',
        'message',
        'status',
        'answered_at',
    ];
	
	 protected $casts = [
        'answered_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
	
	public const MEDIA_ATTACHMENTS = 'attachments';
	


public function scopeLastDay(Builder $query): Builder
{
    return $query->where('created_at', '>=', now()->subDay());
}

public function scopeLastWeek(Builder $query): Builder
{
    return $query->where('created_at', '>=', now()->subWeek());
}

public function scopeLastMonth(Builder $query): Builder
{
    return $query->where('created_at', '>=', now()->subMonth());
}
	
	
	
	
	
	
}