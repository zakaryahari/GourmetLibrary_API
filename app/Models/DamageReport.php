<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DamageReport extends Model
{
    /** @use HasFactory<\Database\Factories\DamageReportFactory> */
    use HasFactory;

    protected $table = 'damage_reports';
    protected $fillable = ['copy_id', 'description'];
    public $timestamps = false;

    public function copy()
    {
        return $this->belongsTo(Copy::class);
    }
}
