<?php

namespace Zaichaopan\Taggable\Traits;

use Zaichaopan\Taggable\Models\Tag;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * @param string|array ...$tagNames
     */
    public function tag(...$tagNames): void
    {
        $this->tags()->syncWithoutDetaching($this->getTagCollection($tagNames));
    }

    /**
     *
     * @param string|array ...$tagNames
     */
    public function reTag(...$tagNames): void
    {
        $this->tags()->sync($this->getTagCollection($tagNames));
    }

    /**
     *
     * @param string|array ...$tagNames
     */
    public function unTag(...$tagNames): void
    {
        $this->tags()->detach($this->getTagCollection($tagNames));
    }

    public function unTagAll(): void
    {
        $this->tags()->detach();
    }

    public function hasTag(string $tagName): bool
    {
        return $this->tags()->whereName($tagName)->exists();
    }

    protected function getTagCollection(array $tagNames): Collection
    {
        return Tag::ofNames(array_flatten($tagNames))->get();
    }
}
