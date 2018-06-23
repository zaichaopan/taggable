# Taggable

This Package is used to make eloquent model taggable. It can be used in laravel 5.5 or higher.

## Installation

```bash
composer require zaichaopan/taggable
```

## Usage

* Add tags and taggables table

After you install the package, run migration command to add __tags__ and __taggables__ table

```bash
php artisan migrate
```

The schemas of these two tables

```php
// tags table
Schema::create('tags', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name')->unique();
    $table->string('slug')->unique();
    $table->timestamps();
});
```

```php
// taggables table
Schema::create('taggables', function (Blueprint $table) {
    $table->increments('id');
    $table->unsignedInteger('tag_id');
    $table->foreign('tag_id')->references('id')->on('tags');
    $table->morphs('taggable');
    $table->timestamps();
});
```

This packages also provides __Tag__ and __Taggable__ models.

```php
// To add a create tag
use  Zaichaopan\Taggable\Models\Tag;

Tag::create(['name' => 'laravel']);

// To get all the tag names
$names = Tag::getNames();
```

__Note__:

Please make sure tag name is unique. After you create or update a tag,  its slug value will be automatically added or updated based on its name value.

* Add __hasTags__ trait to the model you want to tag.

Let's say you have __Activity__ model and you can give it tag.

```php
//
use Zaichaopan\Taggable\Traits\HasTags;

class Activity extends Model
{
    use HasTags;
}
```

The __hasTags__ trait provides the following methods to the model which uses it

__tags__:

It is used to get all the tags of the model:

```php
$tags = $activity->tags;
```

__tag__:

```php
/**
 *
 * @param string|array ...$tagNames
 */
public function tag(...$tagNames): void
```

```php
$activity->tag('outdoor');

// or
$activity->tag('outdoor', 'sports');

// or
$activity->tag(['outdoor', 'sports']);
```

__Note__:

The tag name provided must be valid (exists in the tags table). If a tag name is invalid, it will be ignored. If a tag has already been given to a model, it will not be given again.

__reTag__:

```php
/**
 *
 * @param string|array ...$tagNames
 */
public function reTag(...$tagNames): void
```

It is used to update a model's tag. After calling this method on a model, all its old tags will be moved and only the new tag will remain. If the tag name provided is invalid, it will be ignored. If all the provided tag names are invalid, the model will not be retagged. It will still keep its old tags.

```php
$activity->tag('outdoor');

// the tag of the activity will be sports
$activity->reTag('sports');

// or
$activity->reTag(['outdoor', 'sports']);
```

__unTag__:

```php
/**
 *
 * @param string|array ...$tagNames
 */
public function unTag(...$tagNames): void
```

It is used to remove a tag or multiple tags from a model. If a given tag name is invalid, it will be ignore. If all the given tag names are invalid, none of the model's tags will be removed.

```php
$activity->unTag('outdoor');

// or
$activity->unTag('outdoor', 'sports');

// or
$activity->unTag(['outdoor', 'sports']);
```

__unTagAll__:

```php
public function unTagAll(): void
```

It is used to removed all the tags of the model.

```php
$activity->unTagAll();
```
