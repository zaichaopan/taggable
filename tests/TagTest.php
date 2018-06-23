<?php

use Illuminate\Support\Collection;
use Zaichaopan\Taggable\Models\Tag;

class TagTest extends TestCase
{
    /** @test */
    public function it_has_many_taggable()
    {
        $tag = Tag::create(['name' => 'outdoor']);
        $this->assertInstanceOf(Collection::class, $tag->taggables);
    }

    /** @test */
    public function it_can_create_slug_when_creating()
    {
        $name = 'foo bar';
        $tag = Tag::create(['name' => $name]);
        $this->assertEquals(str_slug($name), $tag->slug);
    }

    /** @test */
    public function it_can_update_slug_when_updating()
    {
        $name = 'foo bar';
        $tag = Tag::create(['name' => $name]);
        $this->assertEquals(str_slug($name), $tag->slug);
        $newName = 'bar baz';
        $tag->update(['name' => $newName]);
        $this->assertEquals(str_slug($newName), $tag->fresh()->slug);
    }

    /** @test */
    public function it_can_get_all_tag_names()
    {
        $nameOne = 'foo bar';
        $nameTwo = 'bar baz';
        collect([$nameOne, $nameTwo])->each(function ($name) {
            Tag::create(compact('name'));
        });

        $this->assertCount(2, $names = Tag::getNames());
        $this->assertContains($nameOne, $names);
        $this->assertContains($nameTwo, $names);
    }

    /** @test */
    public function it_can_get_tags_by_their_names()
    {
        $nameOne = 'foo bar';
        $nameTwo = 'bar baz';
        $tagOne = Tag::create(['name' => $nameOne]);
        $tagTwo = Tag::create(['name' => $nameTwo]);
        $tags = Tag::ofNames([$nameTwo])->get();

        $this->assertCount(1, $tags);
        $this->assertEquals($tagTwo->id, $tags->first()->id);
    }
}
