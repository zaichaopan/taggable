<?php

use Illuminate\Support\Collection;
use Zaichaopan\Taggable\Models\Tag;

class ActivityTest extends TestCase
{
    /** @test */
    public function it_can_morphs_to_many_tags()
    {
        $activity = Activity::create();

        $this->assertInstanceOf(Collection::class, $activity->tags);
    }

    /** @test */
    public function it_can_be_given_a_tag()
    {
        $tag = Tag::create(['name' => 'outdoor']);
        $activity = Activity::create();
        $activity->tag($tag->name);
        $fetchedTags = $activity->tags()->get();

        $this->assertCount(1, $fetchedTags);
        $this->assertEquals($tag->id, $fetchedTags->first()->id);
    }

    /** @test */
    public function it_cannot_be_given_tag_it_already_has()
    {
        $tag = Tag::create(['name' => 'outdoor']);
        $activity = Activity::create();
        $activity->tag($tag->name);
        $activity->tag($tag->name);
        $fetchedTags = $activity->tags()->get();

        $this->assertCount(1, $fetchedTags);
        $this->assertEquals($tag->id, $fetchedTags->first()->id);
    }

    /** @test */
    public function it_can_be_given_multiple_tags()
    {
        $tagOne = Tag::create(['name' => 'outdoor']);
        $tagTwo = Tag::create(['name' => 'sports']);
        $activity = Activity::create();
        $activity->tag($tagOne->name, $tagTwo->name);
        $fetchedTags = $activity->tags()->get();

        $this->assertCount(2, $fetchedTags);

        $tagThree = Tag::create(['name' => 'indoor']);
        $tagFour = Tag::create(['name' => 'shopping']);
        $activity->tag([$tagThree->name, $tagFour->name]);
        $fetchedTags = $activity->tags()->get();

        $this->assertCount(4, $fetchedTags);
    }

    /** @test */
    public function it_can_be_removed_one_tag()
    {
        $tagOne = Tag::create(['name' => 'outdoor']);
        $tagTwo = Tag::create(['name' => 'sports']);
        $activity = Activity::create();
        $activity->tag($tagOne->name, $tagTwo->name);
        $activity->unTag($tagOne->name);
        $fetchedTags = $activity->tags()->get();

        $this->assertCount(1, $fetchedTags);
        $this->assertTrue($fetchedTags->first()->is($tagTwo));
    }

    /** @test */
    public function it_can_be_removed_multiple_tags()
    {
        $tagOne = Tag::create(['name' => 'outdoor']);
        $tagTwo = Tag::create(['name' => 'sports']);
        $tagThree = Tag::create(['name' => 'indoor']);
        $tagFour = Tag::create(['name' => 'shopping']);
        $activity = Activity::create();
        $activity->tag($tagOne->name, $tagTwo->name, $tagThree->name, $tagFour->name);
        $activity->unTag($tagOne->name, $tagTwo->name);
        $activity->unTag([$tagThree->name, $tagFour->name]);
        $fetchedTags = $activity->tags()->get();

        $this->assertCount(0, $fetchedTags);
    }

    /** @test */
    public function it_can_be_removed_all_tags()
    {
        $tag = Tag::create(['name' => 'outdoor']);
        $activity = Activity::create();
        $activity->tag($tag->name);
        $activity->unTagAll();
        $fetchedTags = $activity->tags()->get();

        $this->assertCount(0, $fetchedTags);
    }

    /** @test */
    public function it_can_be_updated_tags()
    {
        $tagOne = Tag::create(['name' => 'outdoor']);
        $activity = Activity::create();
        $activity->tag($tagOne->name);
        $fetchedTags = $activity->tags()->get();

        $this->assertCount(1, $fetchedTags);
        $this->assertTrue($fetchedTags->first()->is($tagOne));

        $tagTwo = Tag::create(['name' => 'sports']);

        $activity->reTag($tagTwo->name);
        $fetchedTags = $activity->tags()->get();
        $this->assertCount(1, $fetchedTags);
        $this->assertTrue($fetchedTags->first()->is($tagTwo));

        $tagThree = Tag::create(['name' => 'indoor']);
        $tagFour = Tag::create(['name' => 'shopping']);

        $activity->reTag([$tagThree->name, $tagFour->name]);
        $tagArray = $activity->tags()->get()->pluck('id')->toArray();
        $this->assertCount(2, $tagArray);
        $this->assertContains($tagThree->id, $tagArray);
        $this->assertContains($tagFour->id, $tagArray);
    }

    /** @test */
    public function it_can_be_determined_if_it_has_a_tag()
    {
        $tagOne = Tag::create(['name' => 'outdoor']);
        $tagTwo = Tag::create(['name' => 'sports']);
        $activity = Activity::create();
        $activity->tag($tagOne->name);
        $activity = $activity->fresh();

        $this->assertTrue($activity->hasTag($tagOne->name));
        $this->assertFalse($activity->hasTag($tagTwo->name));
    }
}
