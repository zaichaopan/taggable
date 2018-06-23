<?php

use Zaichaopan\Taggable\Models\Tag;
use Zaichaopan\Taggable\Models\Taggable;

class TaggableTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_tag()
    {
        $tag = Tag::create(['name' => 'outdoor']);
        $activity = Activity::create();
        $activity->tags()->attach($tag);
        $taggable = Taggable::where('tag_id', $tag->id)->first();
        
        $this->assertInstanceOf(Tag::class, $taggable->tag);
    }
}
