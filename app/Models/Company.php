<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Import yang wajib ada:
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $table = 'companies';
    protected $primaryKey = 'company_id';
    protected $guarded = [];

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'company_id', 'company_id');
    }
}