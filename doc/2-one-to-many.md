# One To Many Relations:

## hasMany

```Country``` model have many ```User``` models

```php
namespace App\Models;

use App\User;
use Chelout\RelationshipEvents\Concerns\HasManyEvents;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasManyEvents;

    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
```

Now we can use methods to assosiate ```Country``` with ```User``` and also update assosiated models.

```php
// ...

// create user
$user = factory('App\User')->create();

// Getting random country
$country = App\Models\Country::inRandomOrder()->first();

// Saving user with specified country
// This will fire two events hasManySaving, hasManySaved
$country->users()->save($user);

// ...
```

Now we should listen our events, for example we can register event listners in model's boot method:
```php
// ...
    public static function boot()
    {
        parent::boot();

        /**
         * One To Many Relationship Events
         */

        static::hasManySaving(function ($parent, $related) {
            Log::info("Saving user's country {$parent->name}.");
        });

        static::hasManySaved(function ($parent, $related) {
            Log::info("User's country is now set to {$parent->name}.");
        });
    }
// ...
```

### Available methods and events

#### HasMany::create (HasOneOrMany::create)
- fires hasManyCreating, hasManyCreated
- events have $parent and $related models

#### HasMany::save (HasOneOrMany::save)
- fires hasManyCreating, hasManyCreated
- events have $parent and $related models

#### HasMany::update (HasOneOrMany::update)
- fires hasManyUpdating, hasManyUpdated
- events have $parent model and $related Eloquent collection
> Note: has additional query to get related Eloquent collection

## belongsTo

There is also inverse relation ```User``` belongs to ```Country```

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Chelout\RelationshipEvents\Concerns\HasBelongsToEvents;

class User extends Model
{
    use HasBelongsToEvents;

    /**
     * Get the country associated with the user.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
```

Now we can use methods to assosiate, dissosiate and update ```Country``` with ```User```:

```php
// ...
 $country = App\Models\Country::inRandomOrder()->first();

$user = factory(User::class)->create([
    'name' => 'John Smith',
]);

// Assosiate user with country
// This will fire two events belongsToAssociating, belongsToAssociated
$user->country()->associate($country);
// ...
```

Now we should listen our events, for example we can register event listners in model's boot method:
```php
// ...
    protected static function boot()
    {
        parent::boot();

        static::belongsToAssociating(function ($relation, $related, $parent) {
            Log::info("Associating country {$parent->name} with user.");
        });

        static::belongsToAssociated(function ($relation, $related, $parent) {
            Log::info("User has been assosiated with country {$parent->name}.");
        });
    }
// ...
```

### Available methods and events

#### BelongsTo::associate
- fires belongsToAssociating, belongsToAssociated
- events have $relation name, $related model and $parent model or key(depends on BelongsTo::associate $model parametr). 
> Note: related model is dirty, should be saved after associating

#### BelongsTo::dissociate
- fires belongsToAssociating, belongsToAssociated
- events have $relation name, $related and $parent models. 
> Note: has additional query to get parent model
> Note: related model is dirty, should be saved after dissociating

#### BelongsTo::update
- fires belongsToUpdating, belongsToUpdated
- events have $relation name, $related and $parent models. 