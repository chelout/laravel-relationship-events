# One To Many Polymorphic Relations:
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
