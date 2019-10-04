<?php

namespace Chelout\RelationshipEvents\Concerns;

use Chelout\RelationshipEvents\MorphTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasMorphToEvents.
 *
 *
 * @mixin \Chelout\RelationshipEvents\Traits\HasDispatchableEvents
 */
trait HasMorphToEvents
{
    /**
     * Instantiate a new MorphTo relationship.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $parent
     * @param string                                $foreignKey
     * @param string                                $ownerKey
     * @param string                                $type
     * @param string                                $relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    protected function newMorphTo(Builder $query, Model $parent, $foreignKey, $ownerKey, $type, $relation)
    {
        return new MorphTo($query, $parent, $foreignKey, $ownerKey, $type, $relation);
    }

    /**
     * Register a model event with the dispatcher.
     *
     * @param string          $event
     * @param \Closure|string $callback
     */
    protected static function registerModelMorphToEvent($event, $callback)
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
    public static function morphToAssociating($callback)
    {
        static::registerModelMorphToEvent('morphToAssociating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToAssociated($callback)
    {
        static::registerModelMorphToEvent('morphToAssociated', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToDissociating($callback)
    {
        static::registerModelMorphToEvent('morphToDissociating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToDissociated($callback)
    {
        static::registerModelMorphToEvent('morphToDissociated', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToUpdating($callback)
    {
        static::registerModelMorphToEvent('morphToUpdating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphToUpdated($callback)
    {
        static::registerModelMorphToEvent('morphToUpdated', $callback);
    }

    /**
     * Fire the given event for the model relationship.
     *
     * @param string                                         $event
     * @param string                                         $relation
     * @param \Illuminate\Database\Eloquent\Model|int|string $parent
     * @param bool                                           $halt
     *
     * @return mixed
     */
    public function fireModelMorphToEvent($event, $relation, $parent, $halt = true)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        $event = 'morphTo' . ucfirst($event);

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
