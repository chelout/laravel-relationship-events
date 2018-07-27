# One To Many Polymorphic Relations:

## morphMany

```Post``` model have many ```Comment``` models

```php
namespace App\Models;

use Chelout\RelationshipEvents\Concerns\HasMorphManyEvents;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasMorphManyEvents;

    protected $fillable = [
        'title',
        'body',
    ];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
```

Now we can use methods to assosiate ```Post``` with ```Comment``` and also update assosiated models.

```php
// ...
// create post
$post = factory('App\Models\Post')->create();

// create comment
$comment = factory('App\Models\Comment')->create();

// Saving comment with specified post
// This will fire two events morphManySaving, morphManySaved
$post->comments()->save($comment);

// ...
```

Now we should listen our events, for example we can register event listners in model's boot method:
```php
// ...
    public static function boot()
    {
        parent::boot();

        /**
         * One To Many Polymorphic Relationship Events
         */
        static::morphManySaving(function ($parent, $related) {
            Log::info("Saving comment's post.");
        });

        static::morphManySaved(function ($parent, $related) {
            Log::info("Comment's post is now set.");
        });
    }
// ...
```

### Available methods and events

#### MorphMany::create (HasOneOrMany::create)
- fires hasManyCreating, hasManyCreated
- events have $parent and $related models
#### MorphMany::save (HasOneOrMany::save)
- fires hasManyCreating, hasManyCreated
- events have $parent and $related models
#### MorphMany::update (HasOneOrMany::update)
- fires hasManyUpdating, hasManyUpdated
- events have $parent model and $related Eloquent collection
> Note: has additional query to get related Eloquent collection

## morphTo

There is also inverse relation ```Comment``` belongs to ```Post```

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Chelout\RelationshipEvents\Concerns\HasMorphToEvents;

class Comment extends Model
{
    use HasMorphToEvents;

    /**
     * Get the post associated with the comment.
     */
    public function post()
    {
        return $this->morphTo(Post::class);
    }
}
```

Now we can use methods to assosiate, dissosiate and update ```Post``` with ```Comment```:

```php
// ...
// create post
$post = factory('App\Models\Post')->create();

// create comment
$comment = factory('App\Models\Comment')->create();

// Saving comment with specified post
// This will fire two events morphToAssociating, morphToAssociated
$comment->post()->associate($post);

// ...
```

Now we should listen our events, for example we can register event listners in model's boot method:
```php
// ...
    protected static function boot()
    {
        parent::boot();

        static::morphToAssociating(function ($relation, $related, $parent) {
            Log::info("Associating post {$parent->name} with comment.");
        });

        static::morphToAssociated(function ($relation, $related, $parent) {
            Log::info("Comment has been assosiated with post {$parent->name}.");
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
