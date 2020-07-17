# Many To Many Relations:

## belongsToMany

```User``` model belongs to many ```Role``` models

```php
namespace App\Models;

use App\User;
use Chelout\RelationshipEvents\Concerns\HasBelongsToManyEvents;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasBelongsToManyEvents;

    protected $fillable = [
        'name', 'email', 'password', 'country_id',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class)
            ->withPivot('is_active');
    }
}
```

Now we can use attach, detach, sync, toggle methods to update existing pivot:

```php
// ...
$user = factory('App\User')->create();

// Attcha one role to user
// This will fire two events belongsToManyAttaching, belongsToManyAttached
$role = factory('App\Models\Role')->create();
$user->roles()->attach($role, ['is_active' => true]);

// Attach many roles to user
// This will fire two events belongsToManyAttaching, belongsToManyAttached
$roles = factory('App\Models\Role', 2)->create();
$user->roles()->attach(
    $roles->mapWithKeys(function ($role) {
        return [
            $role->id => [
                'is_active' => true,
            ],
        ];
    })
    ->toArray()
);

// ...
```

Now we should listen our events, for example we can register event listners in model's boot method:
```php
// ...
    protected static function boot()
    {
        parent::boot();

        static::belongsToManyAttaching(function ($relation, $parent, $ids) {
            Log::info("Attaching roles to user {$parent->name}.");
        });

        static::belongsToManyAttached(function ($relation, $parent, $ids) {
            Log::info("Roles has been attached to user {$parent->name}.");
        });
    }
// ...
```

### Available methods and events

#### BelongsToMany::attach
- fires belongToManyAttaching, belongToManyAttached
- events have $relation name, $parent model, $attributes attaching model ids
#### BelongsToMany::detach
- fires belongToManyDetaching, belongToManyDetached
- events have $relation name, $parent model, $ids detaching model ids, $attributes additional data
> Note: has additional query to get related ids
#### BelongsToMany::sync
- fires belongToManySyncing, belongToManySynced, BelongsToMany::attach, BelongsToMany::detach
- events have $relation name, $parent model, $ids detaching model ids, $attributes additional data
#### BelongsToMany::toggle
- fires belongToManyToggling, belongToManyToggled, BelongsToMany::attach, BelongsToMany::detach
- events have $relation name, $parent model, $ids detaching model ids, $attributes additional data
#### BelongsToMany::updateExistingPivot
- fires belongsToManyUpdatingExistingPivot, belongsToManyUpdatedExistingPivot
- events have $relation name, $parent model, $id updating model id, $attributes additional data


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
