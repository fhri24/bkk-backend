<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class KepalaBkk extends Model
{
    use HasFactory;

    protected $table = 'kepala_bkks';
    protected $primaryKey = 'kepala_bkk_id';
    protected $guarded = [];

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }
}