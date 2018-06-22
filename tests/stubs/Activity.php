<?php

use Illuminate\Database\Eloquent\Model;
use Zaichaopan\Taggable\Traits\HasTags;

class Activity extends Model
{
    use HasTags;

    protected $connection = 'testbench';

    /**
    * Don't auto-apply mass assignment protection.
    *
    * @var array
    */
    protected $guarded = [];
}
