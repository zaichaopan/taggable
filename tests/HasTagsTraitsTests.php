<?php

use Illuminate\Support\Collection;
use Zaichaopan\Taggable\Models\Tag;

trait HasTagsTraitsTests
{
    abstract public function getModel();

    /** @test */
    public function it_can_morphs_to_many_tags()
    {
        $model = $this->getModel();
        $this->assertInstanceOf(Collection::class, $model->tags);
    }

    /** @test */
    public function it_can_be_given_a_tag()
    {
        $model = $this->getModel();
        $tag = Tag::create(['name' => 'outdoor']);
        $model->tag($tag->name);
        $fetchedTags = $model->tags()->get();
        $this->assertCount(1, $fetchedTags);
        $this->assertEquals($tag->id, $fetchedTags->first()->id);
    }

    /** @test */
    public function it_cannot_be_given_tag_it_already_has()
    {
        $tag = Tag::create(['name' => 'outdoor']);
        $model = $this->getModel();
        $model->tag($tag->name);
        $model->tag($tag->name);
        $fetchedTags = $model->tags()->get();

        $this->assertCount(1, $fetchedTags);
        $this->assertEquals($tag->id, $fetchedTags->first()->id);
    }

    /** @test */
    public function it_can_be_given_multiple_tags()
    {
        $tagOne = Tag::create(['name' => 'outdoor']);
        $tagTwo = Tag::create(['name' => 'sports']);

        $model = $this->getModel();
        $model->tag($tagOne->name, $tagTwo->name);
        $fetchedTags = $model->tags()->get();

        $this->assertCount(2, $fetchedTags);

        $tagThree = Tag::create(['name' => 'indoor']);
        $tagFour = Tag::create(['name' => 'shopping']);
        $model->tag([$tagThree->name, $tagFour->name]);
        $fetchedTags = $model->tags()->get();

        $this->assertCount(4, $fetchedTags);
    }

    /** @test */
    public function it_can_be_removed_one_tag()
    {
        $tagOne = Tag::create(['name' => 'outdoor']);
        $tagTwo = Tag::create(['name' => 'sports']);
        $model = $this->getModel();
        $model->tag($tagOne->name, $tagTwo->name);
        $model->unTag($tagOne->name);
        $fetchedTags = $model->tags()->get();

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
        $model = $this->getModel();
        $model->tag($tagOne->name, $tagTwo->name, $tagThree->name, $tagFour->name);
        $model->unTag($tagOne->name, $tagTwo->name);
        $model->unTag([$tagThree->name, $tagFour->name]);
        $fetchedTags = $model->tags()->get();

        $this->assertCount(0, $fetchedTags);
    }

    /** @test */
    public function it_can_be_removed_all_tags()
    {
        $tag = Tag::create(['name' => 'outdoor']);
        $model = $this->getModel();
        $model->tag($tag->name);
        $model->unTagAll();
        $fetchedTags = $model->tags()->get();

        $this->assertCount(0, $fetchedTags);
    }

    /** @test */
    public function it_can_be_updated_tags()
    {
        $tagOne = Tag::create(['name' => 'outdoor']);
        $model = $this->getModel();
        $model->tag($tagOne->name);
        $fetchedTags = $model->tags()->get();

        $this->assertCount(1, $fetchedTags);
        $this->assertTrue($fetchedTags->first()->is($tagOne));

        $tagTwo = Tag::create(['name' => 'sports']);

        $model->reTag($tagTwo->name);
        $fetchedTags = $model->tags()->get();
        $this->assertCount(1, $fetchedTags);
        $this->assertTrue($fetchedTags->first()->is($tagTwo));

        $tagThree = Tag::create(['name' => 'indoor']);
        $tagFour = Tag::create(['name' => 'shopping']);

        $model->reTag([$tagThree->name, $tagFour->name]);
        $tagArray = $model->tags()->get()->pluck('id')->toArray();
        $this->assertCount(2, $tagArray);
        $this->assertContains($tagThree->id, $tagArray);
        $this->assertContains($tagFour->id, $tagArray);
    }

    /** @test */
    public function it_can_be_determined_if_it_has_a_tag()
    {
        $tagOne = Tag::create(['name' => 'outdoor']);
        $tagTwo = Tag::create(['name' => 'sports']);
        $model = $this->getModel();
        $model->tag($tagOne->name);
        $model = $model->fresh();

        $this->assertTrue($model->hasTag($tagOne->name));
        $this->assertFalse($model->hasTag($tagTwo->name));
    }
}
