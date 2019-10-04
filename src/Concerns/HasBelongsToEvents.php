<?php

namespace Chelout\RelationshipEvents\Concerns;

use Chelout\RelationshipEvents\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasBelongsToEvents.
 *
 *
 * @mixin \Chelout\RelationshipEvents\Traits\HasDispatchableEvents
 */
trait HasBelongsToEvents
{
    /**
     * Instantiate a new BelongsTo relationship.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $child
     * @param string                                $foreignKey
     * @param string                                $ownerKey
     * @param string                                $relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    protected function newBelongsTo(Builder $query, Model $child, $foreignKey, $ownerKey, $relation)
    {
        return new BelongsTo($query, $child, $foreignKey, $ownerKey, $relation);
    }

    /**
     * Register a model event with the dispatcher.
     *
     * @param string          $event
     * @param \Closure|string $callback
     */
    protected static function registerModelBelongsToEvent($event, $callback)
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
    public static function belongsToAssociating($callback)
    {
        static::registerModelBelongsToEvent('belongsToAssociating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToAssociated($callback)
    {
        static::registerModelBelongsToEvent('belongsToAssociated', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToDissociating($callback)
    {
        static::registerModelBelongsToEvent('belongsToDissociating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToDissociated($callback)
    {
        static::registerModelBelongsToEvent('belongsToDissociated', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToUpdating($callback)
    {
        static::registerModelBelongsToEvent('belongsToUpdating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function belongsToUpdated($callback)
    {
        static::registerModelBelongsToEvent('belongsToUpdated', $callback);
    }

    /**
     * Fire the given event for the model relationship.
     *
     * @param string                                         $event
     * @param string                                         $relation
     * @param \Illuminate\Database\Eloquent\Model|int|string $parent
     * @param bool                                           $halt
     *
     * @return bool
     */
    public function fireModelBelongsToEvent($event, $relation, $parent, $halt = true)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        $event = 'belongsTo' . ucfirst($event);

        // First, we will get the proper method to call on the event dispatcher, and then we
        // will attempt to fire a custom, object based event for the given event. If that
        // returns a result we can return that result, or we'll call the string events.
        $method = $halt ? 'until' : 'dispatch';

        $result = $this->filterModelEventResults(
            $this->fireCustomModelEvent($event, $method, $relation, $parent)
        );

        if (false === $result) {
            return false;
        }

        return ! empty($result) ? $result : static::$dispatcher->{$method}(
            "eloquent.{$event}: " . static::class, [
                $relation,
                $this,
                $parent,
            ]
        );
    }
}
