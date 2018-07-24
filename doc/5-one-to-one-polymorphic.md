# One To One Polymorphic Relation:

## morphOne

```User``` model might be associated with one ```Address```

```php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Chelout\RelationshipEvents\Concerns\HasMorphOneEvents;

class User extends Model
{
    use HasMorphOneEvents;

    /**
     * Get the address associated with the user.
     */
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
```

Now we can use methods to assosiate ```User``` with ```Address``` and also update assosiated model.

```php
// ...
$user = factory(User::class)->create([
    'name' => 'John Smith',
]);

// Create address and assosiate it with user
// This will fire two events morphOneCreating, morphOneCreated
$user->address()->create([
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

        static::morphOneCreating(function ($parent, $related) {
            Log::info("Creating address for user {$parent->name}.");
        });

        static::morphOneCreated(function ($parent, $related) {
            Log::info("Address for user {$parent->name} has been created.");
        });
    }
// ...
```

### Available methods and events

#### MorphOne::create (HasOneOrMany::create)
- fires morphOneCreating, morphOneCreated
- events have $parent and $related models

#### MorphOne::save (HasOneOrMany::save)
- fires morphOneSaving, morphOneSaved
- events have $parent and $related models

#### MorphOne::update (HasOneOrMany::update)
- fires morphOneUpdating, morphOneUpdated
- events have $parent and $related models

## belongsTo

There is also inverse relation ```Address``` belongs to ```User```

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Chelout\RelationshipEvents\Concerns\HasBelongsToEvents;

class Address extends Model
{
    use HasBelongsToEvents;
    
    /**
     * Get the user that owns address.
     */
    public function user()
    {
        return $this->morphTo(User::class);
    }
}
```

Now we can use methods to assosiate, dissosiate and update ```User``` with ```Address```:

```php
// ...
$user = factory(User::class)->create([
    'name' => 'John Smith',
]);

$address = factory(Address::class)->create([
    'phone' => '8-800-123-45-67',
    'email' => 'user@example.com',
    'address' => 'One Infinite Loop Cupertino, CA 95014',
]);

// Assosiate address with user
// This will fire two events morphToAssociating, morphToAssociated
$address->user()->assosiate($user);
// ...
```

Now we should listen our events, for example we can register event listners in model's boot method:
```php
// ...
    protected static function boot()
    {
        parent::boot();

        static::morphToAssociating(function ($relation, $related, $parent) {
            Log::info("Associating user {$parent->name} with address.");
        });

        static::morphToAssociated(function ($relation, $related, $parent) {
            Log::info("Address has been assosiated with user {$parent->name}.");
        });
    }
// ...
```

### Available methods and events

#### MorphTo::associate
- fires belongToAssociating, belongToAssociated
- events have $relation name, $related and $parent models. 
> Note: related model is dirty, should be saved after associating

#### MorphTo::dissociate
- fires belongToAssociating, belongToAssociated
- events have $relation name, $related and $parent models. 
> Note: has additional query to get parent model
> Note: related model is dirty, should be saved after dissociating

#### MorphTo::update
- fires belongToUpdating, belongToUpdated
- events have $relation name, $related and $parent models. 
> Note: has additional query to get related model