# Many To Many Polymorphic Relations:

## morphToMany

```Post``` model has many ```Tag``` models

```php
namespace App\Models;

use App\Tag;
use Chelout\RelationshipEvents\Concerns\HasMorphToManyEvents;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasMorphToManyEvents;

    protected $fillable = [
        'title',
        'body',
    ];

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
```

Now we can use methods to attach ```Tag``` to ```Post```. Also we can detach and sync models.

```php
// ...

// create post
$post = factory('App\Post')->create();

// create couple tags
$tags = factory('App\Tag', 2)->create();

// Attaching tags to post with random priority
$post->tags()->attach(
    $tags->mapWithKeys(function ($tag) {
        return [
            $tag->id => [
                'priority' => rand(1, 10),
            ]
        ];
    })
    ->toArray()
);

// ...
```

Now we should listen our events, for example we can register event listners in model's boot method:
```php
// ...
    public static function boot()
    {
        parent::boot();

        /**
         * Many To Many Polymorphic Relationship Events
         */

        static::morphToManyAttaching(function ($relation, $parent, $attributes) {
            Log::info("Attaching tags to post {$parent->title}.");
        });

        static::morphToManyAttached(function ($relation, $parent, $attributes) {
            Log::info("Tags have been attached to post {$parent->title}.");
        });
    }
// ...
```

### Available methods and events

#### MorphToMany::attach
- fires morphToManyAttaching, morphToManyAttached
- events have $relation name, $parent model, $attributes attaching model ids

#### MorphToMany::detach
- fires morphToManyDetaching, morphToManyDetached
- events have $relation name, $parent model, $ids detaching model ids, $attributes additional data
> Note: has additional query to get related ids

#### MorphToMany::sync
- fires morphToManySyncing, morphToManySynced, MorphToMany::attach, MorphToMany::detach
- events have $relation name, $parent model, $ids detaching model ids, $attributes additional data

#### MorphToMany::toggle
- fires morphToManyToggling, morphToManyToggled, MorphToMany::attach, MorphToMany::detach
- events have $relation name, $parent model, $ids detaching model ids, $attributes additional data

#### MorphToMany::updateExistingPivot
- fires morphToManyUpdatingExistingPivot, morphToManyUpdatedExistingPivot
- events have $relation name, $parent model, $id updating model id, $attributes additional data


## morphedByMany

There is also inverse relation ```Tag``` morphed by ```Post```

```php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Chelout\RelationshipEvents\Concerns\HasBelongsToEvents;

class Tag extends Model
{
    use HasMorphedByManyEvents;

    /**
     * Get morphed posts.
     */
    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
}
```

Now we can use methods to attach, detach, sync, toggle and update existing pivot:

```php
// create post
$post = factory('App\Post')->create();

// create couple tags
$tag = factory('App\Tag')->create();

// Attaching tags to post with random priority
$tag->posts()->attach($tag, [
    'priority' => rand(1, 10),
]);

// ...
```

Now we should listen our events, for example we can register event listners in model's boot method:
```php
// ...
    protected static function boot()
    {
        parent::boot();

        /**
         * Many To Many Polymorphic Relationship Events
         */

        static::morphedByManyAttaching(function ($relation, $parent, $attributes) {
            Log::info("Attaching post to tag {$parent->name}.");
        });

        static::morphedByManyAttached(function ($relation, $parent, $attributes) {
            Log::info("Post has been attached to tag {$parent->name}.");
        });
    }
// ...
```

### Available methods and events

#### MorphedByMany::attach
- fires morphedByManyAttaching, morphedByManyAttached
- events have $relation name, $parent model, $attributes attaching model ids
#### MorphedByMany::detach
- fires morphedByManyDetaching, morphedByManyDetached
- events have $relation name, $parent model, $ids detaching model ids, $attributes additional data
> Note: has additional query to get related ids
#### MorphedByMany::sync
- fires morphedByManySyncing, morphedByManySynced, MorphedByMany::attach, MorphedByMany::detach
- events have $relation name, $parent model, $ids detaching model ids, $attributes additional data
#### MorphedByMany::toggle
- fires morphedByManyToggling, morphedByManyToggled, MorphedByMany::attach, MorphedByMany::detach
- events have $relation name, $parent model, $ids detaching model ids, $attributes additional data
#### MorphedByMany::updateExistingPivot
- fires morphedByManyUpdatingExistingPivot, morphedByManyUpdatedExistingPivot
- events have $relation name, $parent model, $id updating model id, $attributes additional data
