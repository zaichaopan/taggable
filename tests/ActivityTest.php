<?php

class ActivityTest extends TestCase
{
    use HasTagsTraitsTests;

    public function getModel()
    {
        return Activity::create();
    }
}
