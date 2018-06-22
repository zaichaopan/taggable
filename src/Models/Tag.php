<?php

 namespace  Zaichaopan\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    /**
    * Don't auto-apply mass assignment protection.
    *
    * @var array
    */
    protected $guarded = [];

    public function taggables(): HasMany
    {
        return $this->hasMany(Taggable::class);
    }

    public function scopeOfNames(Builder $builder, array $names): Builder
    {
        return $builder->whereIn('name', $names);
    }

    public static function getNames(): array
    {
        static::pluck('name')->toArray();
    }
}
