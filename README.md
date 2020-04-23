
# Laravel Relationship Events

Missing relationship events for Laravel

<p align="center">
 <a href="https://github.com/chelout/laravel-relationship-events/actions"><img src="https://github.com/chelout/laravel-relationship-events/workflows/tests/badge.svg" alt="Build Status"></a>
 <a href="https://packagist.org/packages/chelout/laravel-relationship-events"><img src="https://poser.pugx.org/chelout/laravel-relationship-events/d/total.svg" alt="Total Downloads"></a>
 <a href="https://packagist.org/packages/chelout/laravel-relationship-events"><img src="https://poser.pugx.org/chelout/laravel-relationship-events/v/stable.svg" alt="Latest Stable Version"></a>
 <a href="https://packagist.org/packages/chelout/laravel-relationship-events"><img src="https://poser.pugx.org/chelout/laravel-relationship-events/license.svg" alt="License"></a>
 </p>

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
It is possible to fire event classes via $dispatchesEvents properties and adding ```HasDispatchableEvents``` trait:

```php

use Chelout\RelationshipEvents\Concerns\HasOneEvents;
use Chelout\RelationshipEvents\Traits\HasDispatchableEvents;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasDispatchableEvents;
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


## Observers
Starting from v0.4 it is possible to use relationship events in [Laravel observers classes](https://laravel.com/docs/5.6/eloquent#observers) Usage is very simple. Let's take ```User``` and ```Profile``` classes from [One To One Relations](doc/1-one-to-one.md), add ```HasRelationshipObservables``` trait to ```User``` class. Define observer class:

```php
namespace App\Observer;

class UserObserver
{
    /**
     * Handle the User "hasOneCreating" event.
     *
     * @param \App\Models\User $user
     * @param \Illuminate\Database\Eloquent\Model $related
     *
     * @return void
     */
    public function hasOneCreating(User $user, Model $related)
    {
        Log::info("Creating profile for user {$related->name}.");
    }

    /**
     * Handle the User "hasOneCreated" event.
     *
     * @param \App\Models\User $user
     * @param \Illuminate\Database\Eloquent\Model $related
     *
     * @return void
     */
    public function hasOneCreated(User $user, Model $related)
    {
        Log::info("Profile for user {$related->name} has been created.");
    }
}
```

Don't forget to register an observer in the ```boot``` method of your ```AppServiceProvider```:
```php
namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
// ...
    public function boot()
    {
        User::observe(UserObserver::class);
    }
// ...
}
```

And now just create profile for user:
```php
// ...
$user = factory(User::class)->create([
    'name' => 'John Smith',
]);

// Create profile and assosiate it with user
// This will fire two events hasOneCreating, hasOneCreated
$user->profile()->create([
    'phone' => '8-800-123-45-67',
    'email' => 'user@example.com',
    'address' => 'One Infinite Loop Cupertino, CA 95014',
]);
// ...
```


## Todo
 - Tests, tests, tests
