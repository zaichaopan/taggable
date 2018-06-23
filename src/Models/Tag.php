<?php

namespace Zaichaopan\Taggable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    protected $guarded = [];

    public static function boot()
    {
        collect(['creating', 'updating'])->each(function ($event) {
            static::$event(function ($tag) {
                $tag->slug = $tag->getSlugValue();
            });
        });
    }

    public function taggables(): HasMany
    {
        return $this->hasMany(Taggable::class);
    }

    public function scopeOfNames(Builder $builder, array $names): Builder
    {
        return $builder->whereIn('name', $names);
    }

    public function getSlugValue(): string
    {
        return str_slug($this->name);
    }

    public static function getNames()
    {
        return static::pluck('name')->all();
    }
}
