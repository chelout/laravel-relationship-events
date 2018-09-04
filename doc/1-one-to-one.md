# One To One Relations:

## hasOne

```User``` model might be associated with one ```Profile```

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Chelout\RelationshipEvents\Concerns\HasOneEvents;

class User extends Model
{
    use HasOneEvents;

    /**
     * Get the profile associated with the user.
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}
```

Now we can use methods to assosiate ```User``` with ```Profile``` and also update assosiated model.

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

Now we should listen our events, for example we can register event listners in model's boot method:
```php
// ...
    protected static function boot()
    {
        parent::boot();

        static::hasOneCreating(function ($parent, $related) {
            Log::info("Creating profile for user {$parent->name}.");
        });

        static::hasOneCreated(function ($parent, $related) {
            Log::info("Profile for user {$parent->name} has been created.");
        });
    }
// ...
```

### Available methods and events

#### HasOne::create (HasOneOrMany::save)
- fires hasOneCreating, hasOneCreated
- events have $parent and $related models

#### HasOne::save (HasOneOrMany::save)
- fires hasOneSaving, hasOneSaved
- events have $parent and $related models

#### HasOne::update (HasOneOrMany::update)
- fires hasOneUpdating, hasOneUpdated
- events have $parent and $related models
> Note: has additional query to get related model

## belongsTo

There is also inverse relation ```Profile``` belongs to ```User```

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Chelout\RelationshipEvents\Concerns\HasBelongsToEvents;

class Profile extends Model
{
    use HasBelongsToEvents;
    
    /**
     * Get the user that owns profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

Now we can use methods to assosiate, dissosiate and update ```User``` with ```Profile```:

```php
// ...
$user = factory(User::class)->create([
    'name' => 'John Smith',
]);

$profile = factory(Profile::class)->create([
    'phone' => '8-800-123-45-67',
    'email' => 'user@example.com',
    'address' => 'One Infinite Loop Cupertino, CA 95014',
]);

// Assosiate profile with user
// This will fire two events belongsToAssociating, belongsToAssociated
$profile->user()->assosiate($user);
// ...
```

Now we should listen our events, for example we can register event listners in model's boot method:
```php
// ...
    protected static function boot()
    {
        parent::boot();

        static::belongsToAssociating(function ($relation, $related, $parent) {
            Log::info("Associating user {$parent->name} with profile.");
        });

        static::belongsToAssociated(function ($relation, $related, $parent) {
            Log::info("Profile has been assosiated with user {$parent->name}.");
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
- fires belongsToDissociating, belongsToDissociated
- events have $relation name, $related and $parent models. 
> Note: has additional query to get parent model
> Note: related model is dirty, should be saved after dissociating

#### BelongsTo::update
- fires belongsToUpdating, belongsToUpdated
- events have $relation name, $related and $parent models. 