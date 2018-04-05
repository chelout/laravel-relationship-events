# One To Many Relations:
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