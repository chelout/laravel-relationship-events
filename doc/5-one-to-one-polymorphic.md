### One To One Polymorphic Relation:
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
