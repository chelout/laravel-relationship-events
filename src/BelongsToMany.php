<?php

namespace Chelout\RelationshipEvents;

use Chelout\RelationshipEvents\Contracts\EventDispatcher;
use Chelout\RelationshipEvents\Traits\HasEventDispatcher;
use Illuminate\Database\Eloquent\Relations\BelongsToMany as BelongsToManyBase;

/**
 * Class BelongsToMany.
 *
 *
 * @property-read \Chelout\RelationshipEvents\Concerns\HasBelongsToManyEvents $parent
 */
class BelongsToMany extends BelongsToManyBase implements EventDispatcher
{
    use HasEventDispatcher;

    protected static $relationEventName = 'belongsToMany';

    /**
     * Toggles a model (or models) from the parent.
     *
     * Each existing model is detached, and non existing ones are attached.
     *
     * @param \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|int|string $ids
     * @param bool                                                                                                                   $touch
     *
     * @return array
     */
    public function toggle($ids, $touch = true)
    {
        $this->parent->fireModelBelongsToManyEvent('toggling', $this->getRelationName(), $ids);

        $result = parent::toggle($ids, $touch);

        $this->parent->fireModelBelongsToManyEvent('toggled', $this->getRelationName(), $ids, [], false);

        return $result;
    }

    /**
     * Sync the intermediate tables with a list of IDs or collection of models.
     *
     * @param \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|int|string $ids
     * @param bool                                                                                                                   $detaching
     *
     * @return array
     */
    public function sync($ids, $detaching = true)
    {
        $this->parent->fireModelBelongsToManyEvent('syncing', $this->getRelationName(), $ids);

        $result = parent::sync($ids, $detaching);

        $this->parent->fireModelBelongsToManyEvent('synced', $this->getRelationName(), $ids, [], false);

        return $result;
    }

    /**
     * Update an existing pivot record on the table.
     *
     * @param mixed $id
     * @param array $attributes
     * @param bool  $touch
     *
     * @return int
     */
    public function updateExistingPivot($id, array $attributes, $touch = true)
    {
        $this->parent->fireModelBelongsToManyEvent('updatingExistingPivot', $this->getRelationName(), $id, $attributes);

        if ($result = parent::updateExistingPivot($id, $attributes, $touch)) {
            $this->parent->fireModelBelongsToManyEvent('updatedExistingPivot', $this->getRelationName(), $id, $attributes, false);
        }

        return $result;
    }

    /**
     * Attach a model to the parent.
     *
     * @param mixed $id
     * @param array $attributes
     * @param bool  $touch
     */
    public function attach($id, array $attributes = [], $touch = true)
    {
        $this->parent->fireModelBelongsToManyEvent('attaching', $this->getRelationName(), $id, $attributes);

        parent::attach($id, $attributes, $touch);

        $this->parent->fireModelBelongsToManyEvent('attached', $this->getRelationName(), $id, $attributes, false);
    }

    /**
     * Detach models from the relationship.
     *
     * @param mixed $ids
     * @param bool  $touch
     *
     * @return int
     */
    public function detach($ids = null, $touch = true)
    {
        // Get detached ids to pass them to event
        $ids = $ids ?? $this->parent->{$this->getRelationName()}->pluck($this->relatedKey);

        $this->parent->fireModelBelongsToManyEvent('detaching', $this->getRelationName(), $ids);

        if ($result = parent::detach($ids, $touch)) {
            // If records are detached fire detached event
            // Note: detached event will be fired even if one of all records have been detached
            $this->parent->fireModelBelongsToManyEvent('detached', $this->getRelationName(), $ids, [], false);
        }

        return $result;
    }
}
