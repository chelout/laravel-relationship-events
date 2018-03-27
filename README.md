
# Laravel Relationship Events

Missing relationship events for Laravel

## Install

1. Install package with composer

    ```
    composer require chelout/laravel-relationship-events:dev-master
    ```

2. Use necessary trait in your model.
```php
...
use Chelout\RelationshipEvents\Relationships\Concerns\HasOneEvents;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasOneEvents;
...
    public static function boot()
    {
        parent::boot();

        /**
         * One To One Relationship Events
         */
        static::hasOneSaving(function ($parent, $related) {
            dump('hasOneSaving', $parent, $related);
        });

        static::hasOneSaved(function ($parent, $related) {
            dump('hasOneSaved', $parent, $related);
        });

        static::hasOneCreating(function ($parent, $related) {
            dump('hasOneCreating', $parent, $related);
        });

        static::hasOneCreated(function ($parent, $related) {
            dump('hasOneCreated', $parent, $related);
        });

        static::hasOneUpdating(function ($parent, $related) {
            dump('hasOneUpdating', $parent, $related);
        });

        static::hasOneUpdated(function ($parent, $related) {
            dump('hasOneUpdated', $parent, $related);
        });
    }
...
}
```

#### Available traits:
- HasBelongsToEvents
- HasBelongsToManyEvents
- HasManyEvents
- HasMorphedByManyEvents
- HasMorphManyEvents
- HasMorphOneEvents
- HasMorphToEvents
- HasMorphToManyEvents
- HasOneEvents

## Relationships
### One To One:
 - hasOne:
    - methods:
         - HasOne::create (HasOneOrMany::create)
            - fires hasOneCreating, hasOneCreated
            - events have $parent and $related models
        - HasOne::save (HasOneOrMany::save)
            - fires hasOneSaving, hasOneSaved
            - events have $parent and $related models
        - HasOne::update (HasOneOrMany::update)
            - fires hasOneUpdating, hasOneUpdated
            - events have $parent and $related models
            > Note: has additional query to get related model

- belongsTo:
    - methods:
        - BelongsTo::associate
            - fires belongsToAssociating, belongsToAssociated
            - events have $relation name, $related model and $parent model or key(depends on BelongsTo::associate $model parametr). 
            > Note: related model is dirty, should be saved after associating
        - BelongsTo::dissociate
            - fires belongsToAssociating, belongsToAssociated
            - events have $relation name, $related and $parent models. 
            > Note: has additional query to get parent model
            > Note: related model is dirty, should be saved after dissociating
        - BelongsTo::update
            - fires belongsToUpdating, belongsToUpdated
            - events have $relation name, $related and $parent models. 


### One To Many:
- hasMany:
    - methods:
        - HasMany::create (HasOneOrMany::create)
            - fires hasManyCreating, hasManyCreated
            - events have $parent and $related models
        - HasMany::save (HasOneOrMany::save)
            - fires hasManyCreating, hasManyCreated
            - events have $parent and $related models
        - HasMany::update (HasOneOrMany::update)
            - fires hasManyUpdating, hasManyUpdated
            - events have $parent model and $related Eloquent collection
            > Note: has additional query to get related Eloquent collection

- belongsTo:
    - methods:
        - BelongsTo::associate
            - fires belongToAssociating, belongToAssociated
            - events have $relation name, $related model and $parent model or key(depends on BelongsTo::associate $model parametr). 
            > Note: related model is dirty, should be saved after associating
        - BelongsTo::dissociate
            - fires belongToAssociating, belongToAssociated
            - events have $relation name, $related and $parent models. 
            > Note: has additional query to get parent model
            > Note: related model is dirty, should be saved after dissociating
        - BelongsTo::update
            - fires belongToUpdating, belongToUpdated
            - events have $relation name, $related and $parent models. 


### Many To Many:
- belongsToMany:
    - methods:
        - BelongsToMany::attach
            - fires belongToManyAttching, belongToManyAttached
            - events have $parent model, $attributes attaching model ids
        - BelongsToMany::detach
            - fires belongToManyDetching, belongToManyDetached
            - events have $parent model, $ids detaching model ids, $attributes additional data
            > Note: has additional query to get related ids
        - BelongsToMany::sync
            - fires belongToManySyncing, belongToManySynced, BelongsToMany::attach, BelongsToMany::detach
            - events have $parent model, $ids detaching model ids, $attributes additional data
        - BelongsToMany::toggle
            - fires belongToManyToggling, belongToManyToggled, BelongsToMany::attach, BelongsToMany::detach
            - events have $parent model, $ids detaching model ids, $attributes additional data
        - BelongsToMany::updateExistingPivot
            - fires updatingExistingPivotToggling, updatedExistingPivotToggled
            - events have $parent model, $id updating model id, $attributes additional data


### Has Many Through:
- hasManyThrough


### One To One Polymorphic Relations:
- morphOne:
    - methods:
        - MorphOne::create (HasOneOrMany::create)
            - fires morphOneCreating, morphOneCreated
            - events have $parent and $related models
        - MorphOne::save (HasOneOrMany::save)
            - fires morphOneSaving, morphOneSaved
            - events have $parent and $related models
        - MorphOne::update (HasOneOrMany::update)
            - fires morphOneUpdating, morphOneUpdated
            - events have $parent and $related models

- morphTo:
    - methods:
        - MorphTo::associate
            - fires belongToAssociating, belongToAssociated
            - events have $relation name, $related and $parent models. 
            > Note: related model is dirty, should be saved after associating
        - MorphTo::dissociate
            - fires belongToAssociating, belongToAssociated
            - events have $relation name, $related and $parent models. 
            > Note: has additional query to get parent model
            > Note: related model is dirty, should be saved after dissociating
        - MorphTo::update
            - fires belongToUpdating, belongToUpdated
            - events have $relation name, $related and $parent models. 
            > Note: has additional query to get related model


### One To Many Polymorphic Relations:
- morphMany:
    - methods:
        - MorphMany::create (HasOneOrMany::create)
            - fires hasManyCreating, hasManyCreated
            - events have $parent and $related models
        - MorphMany::save (HasOneOrMany::save)
            - fires hasManyCreating, hasManyCreated
            - events have $parent and $related models
        - MorphMany::update (HasOneOrMany::update)
            - fires hasManyUpdating, hasManyUpdated
            - events have $parent model and $related Eloquent collection
            > Note: has additional query to get related Eloquent collection

- morphTo:
    - methods:
        - MorphTo::associate
            - fires belongToAssociating, belongToAssociated
            - events have $relation name, $related and $parent models. 
            > Note: related model is dirty, should be saved after associating
        - MorphTo::dissociate
            - fires belongToAssociating, belongToAssociated
            - events have $relation name, $related and $parent models. 
            > Note: has additional query to get parent model
            > Note: related model is dirty, should be saved after dissociating
        - MorphTo::update
            - fires belongToUpdating, belongToUpdated
            - events have $relation name, $related and $parent models. 
            > Note: has additional query to get related model


### Many To Many Polymorphic Relations:
- morphToMany:
    - methods:
        - ? BelongsToMany::create
        - ? BelongsToMany::save
        - BelongsToMany::attach
        - BelongsToMany::detach
        - BelongsToMany::sync
        - BelongsToMany::updateExistingPivot
        - BelongsToMany::toggle
- morphedByMany:
    - methods:
        - ? BelongsToMany::create
        - ? BelongsToMany::save
        - BelongsToMany::attach
        - BelongsToMany::detach
        - BelongsToMany::sync
        - BelongsToMany::updateExistingPivot
        - BelongsToMany::toggle


## Todo

 - Implement Many To Many polymorphic relations events.
 - Dive into Has Many Through and understand if there could be some events.
 - Move fireModelRelationshipEvent() method to relation concerns in order to create dispatchable relationship events:
```php
protected $dispatchesEvents = [
    'hasOneSaving' => \App\Events\UserSaving::class,
];
```
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
 - Refactor model relationship events registration to __callStatic() magic method.
 - Tests, tests, tests
 - Documentation
