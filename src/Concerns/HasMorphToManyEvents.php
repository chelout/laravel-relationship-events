<?php

namespace Chelout\RelationshipEvents\Concerns;

use Chelout\RelationshipEvents\Helpers\AttributesMethods;
use Chelout\RelationshipEvents\MorphToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasMorphToManyEvents.
 *
 *
 * @mixin \Chelout\RelationshipEvents\Traits\HasDispatchableEvents
 */
trait HasMorphToManyEvents
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
        return new MorphToMany($query, $parent, $name, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey,
            $relationName, $inverse);
    }

    /**
     * Register a model event with the dispatcher.
     *
     * @param string          $event
     * @param \Closure|string $callback
     */
    protected static function registerModelMorphToManyEvent($event, $callback)
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
    public static function morphToManyCreating($callback)
    {
        static::registerModelMorphToManyEvent('morphToManyCreating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToManyCreated($callback)
    {
        static::registerModelMorphToManyEvent('morphToManyCreated', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToManySaving($callback)
    {
        static::registerModelMorphToManyEvent('morphToManySaving', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToManySaved($callback)
    {
        static::registerModelMorphToManyEvent('morphToManySaved', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToManyAttaching($callback)
    {
        static::registerModelMorphToManyEvent('morphToManyAttaching', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToManyAttached($callback)
    {
        static::registerModelMorphToManyEvent('morphToManyAttached', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToManyDetaching($callback)
    {
        static::registerModelMorphToManyEvent('morphToManyDetaching', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToManyDetached($callback)
    {
        static::registerModelMorphToManyEvent('morphToManyDetached', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToManySyncing($callback)
    {
        static::registerModelMorphToManyEvent('morphToManySyncing', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToManySynced($callback)
    {
        static::registerModelMorphToManyEvent('morphToManySynced', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToManyToggling($callback)
    {
        static::registerModelMorphToManyEvent('morphToManyToggling', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToManyToggled($callback)
    {
        static::registerModelMorphToManyEvent('morphToManyToggled', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToManyUpdatingExistingPivot($callback)
    {
        static::registerModelMorphToManyEvent('morphToManyUpdatingExistingPivot', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToManyUpdatedExistingPivot($callback)
    {
        static::registerModelMorphToManyEvent('morphToManyUpdatedExistingPivot', $callback);
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
    public function fireModelMorphToManyEvent($event, $relation, $ids, $attributes = [], $halt = true)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        $parsedIds = AttributesMethods::parseIds($ids);
        $parsedIdsForEvent = AttributesMethods::parseIdsForEvent($parsedIds);
        $parseAttributesForEvent = AttributesMethods::parseAttributesForEvent($ids, $parsedIds, $attributes);

        $event = 'morphToMany' . ucfirst($event);

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
