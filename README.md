
# Laravel Relationship Events

Missing relationship events for Laravel

[![Latest Stable Version](https://poser.pugx.org/chelout/laravel-relationship-events/version)](https://packagist.org/packages/chelout/laravel-relationship-events)
[![Total Downloads](https://poser.pugx.org/chelout/laravel-relationship-events/downloads)](https://packagist.org/packages/chelout/laravel-relationship-events)
[![License](https://poser.pugx.org/chelout/laravel-relationship-events/license)](https://packagist.org/packages/chelout/laravel-relationship-events)

## Install

1. Install package with composer

#### Stable branch:
```
composer require chelout/laravel-relationship-events
```

#### Development branch:
```
composer require chelout/laravel-relationship-events:dev-master
```

2. Use necessary trait in your model.
#### Available traits:
- HasOneEvents
- HasBelongsToEvents
- HasManyEvents
- HasBelongsToManyEvents
- HasMorphOneEvents
- HasMorphToEvents
- HasMorphManyEvents
- HasMorphToManyEvents
- HasMorphedByManyEvents

```php

use Chelout\RelationshipEvents\Concerns\HasOneEvents;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasOneEvents;

    public static function boot()
    {
        parent::boot();

        /**
         * One To One Relationship Events
         */
        static::hasOneSaved(function ($parent, $related) {
            dump('hasOneSaved', $parent, $related);
        });

        static::hasOneUpdated(function ($parent, $related) {
            dump('hasOneUpdated', $parent, $related);
        });
    }

}
```

```php

use Chelout\RelationshipEvents\Concerns\HasMorphToManyEvents;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasMorphToManyEvents;

    public static function boot()
    {
        parent::boot();

        /**
         * Many To Many Polymorphic Relations Events.
         */
        static::morphToManyAttached(function ($relation, $parent, $ids, $attributes) {
            dump('morphToManyAttached', $relation, $parent, $ids, $attributes);
        });

        static::morphToManyDetached(function ($relation, $parent, $ids) {
            dump('morphToManyDetached', $relation, $parent, $ids);
        });
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

}
```

3. Dispatchable relationship events.
It is possible to fire event classes via $dispatchesEvents properties:

```php

use Chelout\RelationshipEvents\Concerns\HasOneEvents;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasOneEvents;

    protected $dispatchesEvents = [
        'hasOneSaved' => HasOneSaved::class,
    ];

}
```

## Relationships
- [One To One Relations](doc/1-one-to-one.md)
- [One To Many Relations](doc/2-one-to-many.md)
- [Many To Many Relations](doc/3-many-to-many.md)
- [Has Many Through Relations](doc/4-has-many-through.md)
- [One To One Polymorphic Relations](doc/5-one-to-one-polymorphic.md)
- [One To Many Polymorphic Relations](doc/6-one-to-many-polymorphic.md)
- [Many To Many Polymorphic Relations](doc/7-many-to-many-polymorphic.md)


## Todo

 - Add relationship events to models $observables property in order to create model observers:
```php
HasEvents::addObservableEvents([
    'hasOneCreating',
    'hasOneCreated',
    'hasOneSaving',
    'hasOneSaved',
    'hasOneUpdating',
    'hasOneUpdated',
]);
```
 - Tests, tests, tests
 - Documentation
    - Add [One To One Polymorphic Relations](doc/5-one-to-one-polymorphic.md) Polymorphic Relations example
    - Add [Many To Many Polymorphic Relations](doc/7-many-to-many-polymorphic.md) Polymorphic Relations example
