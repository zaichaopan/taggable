<?php

namespace Zaichaopan\Taggable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Taggable extends Model
{
    /**
    * Don't auto-apply mass assignment protection.
    *
    * @var array
    */
    protected $guarded = [];

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
