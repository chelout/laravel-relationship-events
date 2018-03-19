
# Laravel Relationship Events

Missing relationship events for Laravel

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
            - fires hasOneAssociating, hasOneAssociated
            - events have $parent and $related models. Note: related model is dirty, should be saved after associating
        - BelongsTo::dissociate
            - fires hasOneAssociating, hasOneAssociated
            - events have $parent and $related models. Note: related model is dirty, should be saved after dissociating
        - BelongsTo::update
            - fires hasOneUpdating, hasOneUpdated
            - events have $parent and $related models
            > Note: has additional query to get related model


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
            - events have $parent and $related models. Note: related model is dirty, should be saved after associating
        - BelongsTo::dissociate
            - fires belongToAssociating, belongToAssociated
            - events have $parent and $related models. Note: related model is dirty, should be saved after associating
        - BelongsTo::update
            - fires belongToUpdating, belongToUpdated
            - events have $parent and $related models
            > Note: has additional query to get related model


### Many To Many:
- belongsToMany:
    - methods:
        - BelongsToMany::attach
            - fires belongToManyAttching, belongToManyAttached
            - events have $parent model, $attributes attaching model ids
        - BelongsToMany::detach
            - fires belongToManyDetching, belongToManyDetached
            - events have $parent model, $ids detaching model ids, $attributes additional data
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
            > Note: has additional query to get related model

- morphTo:
    - methods:
        - MorphTo::associate
            - fires belongToAssociating, belongToAssociated
            - events have $parent and $related models. Note: related model is dirty, should be saved after associating
        - MorphTo::dissociate
            - fires belongToAssociating, belongToAssociated
            - events have $parent and $related models. Note: related model is dirty, should be saved after associating
        - MorphTo::update
            - fires belongToUpdating, belongToUpdated
            - events have $parent and $related models
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
        - BelongsTo::associate
            - fires belongToAssociating, belongToAssociated
            - events have $parent and $related models. Note: related model is dirty, should be saved after associating
        - BelongsTo::dissociate
            - fires belongToAssociating, belongToAssociated
            - events have $parent and $related models. Note: related model is dirty, should be saved after associating
        - BelongsTo::update
            - fires belongToUpdating, belongToUpdated
            - events have $parent and $related models
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
