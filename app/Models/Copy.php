<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Copy extends Model
{
    /** @use HasFactory<\Database\Factories\CopyFactory> */
    use HasFactory;

    protected $table = 'copies';
    protected $fillable = ['book_id', 'status', 'is_damaged'];
    protected $casts = ['is_damaged' => 'boolean'];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'borrows')
            ->using(Borrow::class)
            ->withPivot('borrowed_at', 'returned_at')
            ->withTimestamps();
    }

    public function damageReports(): HasMany
    {
        return $this->hasMany(DamageReport::class);
    }
}
