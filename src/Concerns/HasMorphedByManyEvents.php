<?php

namespace Chelout\RelationshipEvents\Concerns;

use Chelout\RelationshipEvents\Helpers\AttributesMethods;
use Chelout\RelationshipEvents\MorphedByMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasMorphedByManyEvents.
 *
 *
 * @mixin \Chelout\RelationshipEvents\Traits\HasDispatchableEvents
 */
trait HasMorphedByManyEvents
{
    /**
     * Instantiate a new HasManyThrough relationship.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $parent
     * @param string                                $name
     * @param string                                $table
     * @param string                                $foreignPivotKey
     * @param string                                $relatedPivotKey
     * @param string                                $parentKey
     * @param string                                $relatedKey
     * @param string                                $relationName
     * @param bool                                  $inverse
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    protected function newMorphToMany(Builder $query, Model $parent, $name, $table, $foreignPivotKey,
                                      $relatedPivotKey, $parentKey, $relatedKey,
                                      $relationName = null, $inverse = false)
    {
        return new MorphedByMany($query, $parent, $name, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey,
            $relationName, $inverse);
    }

    /**
     * Register a model event with the dispatcher.
     *
     * @param string          $event
     * @param \Closure|string $callback
     */
    protected static function registerModelMorphedByManyEvent($event, $callback)
    {
        if (isset(static::$dispatcher)) {
            $name = static::class;

            static::$dispatcher->listen("eloquent.{$event}: {$name}", $callback);
        }
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphedByManyCreating($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyCreating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphedByManyCreated($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyCreated', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphedByManySaving($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManySaving', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphedByManySaved($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManySaved', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphedByManyAttaching($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyAttaching', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphedByManyAttached($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyAttached', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphedByManyDetaching($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyDetaching', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphedByManyDetached($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyDetached', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphedByManySyncing($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManySyncing', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphedByManySynced($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManySynced', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphedByManyToggling($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyToggling', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphedByManyToggled($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyToggled', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphedByManyUpdatingExistingPivot($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyUpdatingExistingPivot', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphedByManyUpdatedExistingPivot($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyUpdatedExistingPivot', $callback);
    }

    /**
     * Fire the given event for the model relationship.
     *
     * @param string $event
     * @param string $relation
     * @param mixed  $ids
     * @param array  $attributes
     * @param bool   $halt
     *
     * @return mixed
     */
    public function fireModelMorphedByManyEvent($event, $relation, $ids, $attributes = [], $halt = true)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        $parsedIds = AttributesMethods::parseIds($ids);
        $parsedIdsForEvent = AttributesMethods::parseIdsForEvent($parsedIds);
        $parseAttributesForEvent = AttributesMethods::parseAttributesForEvent($ids, $parsedIds, $attributes);

        $event = 'morphedByMany' . ucfirst($event);

        // First, we will get the proper method to call on the event dispatcher, and then we
        // will attempt to fire a custom, object based event for the given event. If that
        // returns a result we can return that result, or we'll call the string events.
        $method = $halt ? 'until' : 'dispatch';

        $result = $this->filterModelEventResults(
            $this->fireCustomModelEvent($event, $method, $relation, $parsedIdsForEvent, $parseAttributesForEvent)
        );

        if (false === $result) {
            return false;
        }

        return ! empty($result) ? $result : static::$dispatcher->{$method}(
            "eloquent.{$event}: " . static::class, [
                $relation,
                $this,
                $parsedIdsForEvent,
                $parseAttributesForEvent,
            ]
        );
    }
}
