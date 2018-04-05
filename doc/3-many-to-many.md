# Many To Many Relations:
- belongsToMany:
    - methods:
        - BelongsToMany::attach
            - fires belongToManyAttching, belongToManyAttached
            - events have $relation name, $parent model, $attributes attaching model ids
        - BelongsToMany::detach
            - fires belongToManyDetching, belongToManyDetached
            - events have $relation name, $parent model, $ids detaching model ids, $attributes additional data
            > Note: has additional query to get related ids
        - BelongsToMany::sync
            - fires belongToManySyncing, belongToManySynced, BelongsToMany::attach, BelongsToMany::detach
            - events have $relation name, $parent model, $ids detaching model ids, $attributes additional data
        - BelongsToMany::toggle
            - fires belongToManyToggling, belongToManyToggled, BelongsToMany::attach, BelongsToMany::detach
            - events have $relation name, $parent model, $ids detaching model ids, $attributes additional data
        - BelongsToMany::updateExistingPivot
            - fires belongsToManyUpdatingExistingPivot, belongsToManyUpdatedExistingPivot
            - events have $relation name, $parent model, $id updating model id, $attributes additional data