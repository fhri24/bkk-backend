<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipStep extends Model
{
    protected $fillable = ['tip_id', 'step_order', 'title', 'description'];

    public function tip()
    {
        return $this->belongsTo(Tip::class);
    }
}
