<?php

namespace Chelout\RelationshipEvents\Relationships;

use Chelout\RelationshipEvents\Relationships\Traits\HasEventDispatcher;
use Chelout\RelationshipEvents\Relationships\Contracts\EventDispatcher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany as MorphToManyBase;

class MorphedByMany extends MorphToManyBase implements EventDispatcher
{
    use HasEventDispatcher;

    protected static $relationEventName = 'morphedByMany';

    /**
     * Toggles a model (or models) from the parent.
     *
     * Each existing model is detached, and non existing ones are attached.
     *
     * @param  mixed  $ids
     * @param  bool   $touch
     *
     * @return array
     */
    public function toggle($ids, $touch = true)
    {
        $this->fireModelRelationshipEvent('toggling', $ids);

        $result = parent::toggle($ids, $touch);

        $this->fireModelRelationshipEvent('toggled', $ids, [], false);

        return $result;
    }

    /**
     * Sync the intermediate tables with a list of IDs or collection of models.
     *
     * @param  \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|array  $ids
     * @param  bool   $detaching
     *
     * @return array
     */
    public function sync($ids, $detaching = true)
    {
        $this->fireModelRelationshipEvent('syncing', $ids);

        $result = parent::sync($ids, $detaching);

        $this->fireModelRelationshipEvent('synced', $ids, [], false);

        return $result;
    }

    /**
     * Update an existing pivot record on the table.
     *
     * @param  mixed  $id
     * @param  array  $attributes
     * @param  bool   $touch
     *
     * @return int
     */
    public function updateExistingPivot($id, array $attributes, $touch = true)
    {
        $this->fireModelRelationshipEvent('updatingExistingPivot', $id, $attributes);

        if ($result = parent::updateExistingPivot($id, $attributes, $touch)) {
            $this->fireModelRelationshipEvent('updatedExistingPivot', $id, $attributes, false);
        }

        return $result;
    }

    /**
     * Attach a model to the parent.
     *
     * @param  mixed  $id
     * @param  array  $attributes
     * @param  bool   $touch
     */
    public function attach($id, array $attributes = [], $touch = true)
    {
        $this->fireModelRelationshipEvent('attaching', $id, $attributes);

        parent::attach($id, $attributes, $touch);

        $this->fireModelRelationshipEvent('attached', $id, $attributes, false);
    }

    /**
     * Detach models from the relationship.
     *
     * @param  mixed  $ids
     * @param  bool  $touch
     *
     * @return int
     */
    public function detach($ids = null, $touch = true)
    {
        // Get detached ids to pass them to event
        $ids = $ids ?? $this->parent->{$this->getRelationName()}->pluck('id');

        $this->fireModelRelationshipEvent('detaching', $ids);

        if ($result = parent::detach($ids, $touch)) {
            // If records are detached fire detached event
            // Note: detached event will be fired even if one of all records have been detached
            $this->fireModelRelationshipEvent('detached', $ids, [], false);
        }

        return $result;
    }

    /**
     * Fire the given event for the model relationship.
     *
     * @param  string  $event
     * @param  mixed $ids
     * @param  array $attributes
     * @param  bool  $halt
     *
     * @return mixed
     */
    protected function fireModelRelationshipEvent($event, $ids, $attributes = [], $halt = true)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        // First, we will get the proper method to call on the event dispatcher, and then we
        // will attempt to fire a custom, object based event for the given event. If that
        // returns a result we can return that result, or we'll call the string events.
        $method = $halt ? 'until' : 'fire';

        // $result = $this->filterModelEventResults(
        //     $this->fireCustomModelEvent($event, $method)
        // );

        // if ($result === false) {
        //     return false;
        // }

        // return ! empty($result) ? $result : static::$dispatcher->{$method}(
        //     "eloquent.{$event}: ".static::class, $this
        // );

        $parsedIds = $this->parseIds($ids);

        return static::$dispatcher->{$method}(
            'eloquent.' . static::$relationEventName . ucfirst($event) . ': ' . get_class($this->parent), [
                $this->getRelationName(),
                $this->parent,
                $this->parseIdsForEvent($parsedIds),
                $this->parseAttributesForEvent($ids, $parsedIds, $attributes),
            ]
        );
    }

    /**
     * Parse ids for event.
     *
     * @param  array $ids
     *
     * @return array
     */
    protected function parseIdsForEvent(array $ids): array
    {
        return array_map(function ($key, $id) {
            return is_array($id) ? $key : $id;
        }, array_keys($ids), $ids);
    }

    /**
     * Parse attributes for event.
     *
     * @param  array $attributes
     *
     * @return array
     */
    protected function parseAttributesForEvent($rawIds, array $parsedIds, array $attributes = []): array
    {
        return is_array($rawIds) ? array_filter($parsedIds, function ($id) {
            return is_array($id) && ! empty($id);
        }) : $attributes;
    }
}
