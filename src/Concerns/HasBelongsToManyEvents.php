<?php

namespace Chelout\RelationshipEvents\Concerns;

use Chelout\RelationshipEvents\BelongsToMany;
use Chelout\RelationshipEvents\Helpers\AttributesMethods;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasBelongsToManyEvents.
 *
 *
 * @mixin \Chelout\RelationshipEvents\Traits\HasDispatchableEvents
 */
trait HasBelongsToManyEvents
{
    /**
     * Instantiate a new BelongsToMany relationship.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $parent
     * @param string                                $table
     * @param string                                $foreignPivotKey
     * @param string                                $relatedPivotKey
     * @param string                                $parentKey
     * @param string                                $relatedKey
     * @param string                                $relationName
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    protected function newBelongsToMany(Builder $query, Model $parent, $table, $foreignPivotKey, $relatedPivotKey,
                                        $parentKey, $relatedKey, $relationName = null)
    {
        return new BelongsToMany($query, $parent, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $relationName);
    }

    /**
     * Register a model event with the dispatcher.
     *
     * @param string          $event
     * @param \Closure|string $callback
     */
    protected static function registerModelBelongsToManyEvent($event, $callback)
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
    public static function belongsToManyAttaching($callback)
    {
        static::registerModelBelongsToManyEvent('belongsToManyAttaching', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManyAttached($callback)
    {
        static::registerModelBelongsToManyEvent('belongsToManyAttached', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManyDetaching($callback)
    {
        static::registerModelBelongsToManyEvent('belongsToManyDetaching', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManyDetached($callback)
    {
        static::registerModelBelongsToManyEvent('belongsToManyDetached', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManySyncing($callback)
    {
        static::registerModelBelongsToManyEvent('belongsToManySyncing', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManySynced($callback)
    {
        static::registerModelBelongsToManyEvent('belongsToManySynced', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManyToggling($callback)
    {
        static::registerModelBelongsToManyEvent('belongsToManyToggling', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManyToggled($callback)
    {
        static::registerModelBelongsToManyEvent('belongsToManyToggled', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManyUpdatingExistingPivot($callback)
    {
        static::registerModelBelongsToManyEvent('belongsToManyUpdatingExistingPivot', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToManyUpdatedExistingPivot($callback)
    {
        static::registerModelBelongsToManyEvent('belongsToManyUpdatedExistingPivot', $callback);
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
    public function fireModelBelongsToManyEvent($event, $relation, $ids, $attributes = [], $halt = true)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        $parsedIds = AttributesMethods::parseIds($ids);
        $parsedIdsForEvent = AttributesMethods::parseIdsForEvent($parsedIds);
        $parseAttributesForEvent = AttributesMethods::parseAttributesForEvent($ids, $parsedIds, $attributes);

        $event = 'belongsToMany' . ucfirst($event);

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
