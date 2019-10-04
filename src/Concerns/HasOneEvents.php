<?php

namespace Chelout\RelationshipEvents\Concerns;

use Chelout\RelationshipEvents\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasOneEvents.
 *
 *
 * @mixin \Chelout\RelationshipEvents\Traits\HasDispatchableEvents
 */
trait HasOneEvents
{
    /**
     * Instantiate a new HasOne relationship.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $parent
     * @param string                                $foreignKey
     * @param string                                $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    protected function newHasOne(Builder $query, Model $parent, $foreignKey, $localKey)
    {
        return new HasOne($query, $parent, $foreignKey, $localKey);
    }

    /**
     * Register a model has one event with the dispatcher.
     *
     * @param string          $event
     * @param \Closure|string $callback
     */
    protected static function registerModelHasOneEvent($event, $callback)
    {
        if (isset(static::$dispatcher)) {
            $name = static::class;

            static::$dispatcher->listen("eloquent.{$event}: {$name}", $callback);
        }
    }

    /**
     * Register a creating model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function hasOneCreating($callback)
    {
        static::registerModelHasOneEvent('hasOneCreating', $callback);
    }

    /**
     * Register a created model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function hasOneCreated($callback)
    {
        static::registerModelHasOneEvent('hasOneCreated', $callback);
    }

    /**
     * Register a saving model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function hasOneSaving($callback)
    {
        static::registerModelHasOneEvent('hasOneSaving', $callback);
    }

    /**
     * Register a saved model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function hasOneSaved($callback)
    {
        static::registerModelHasOneEvent('hasOneSaved', $callback);
    }

    /**
     * Register a updating model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function hasOneUpdating($callback)
    {
        static::registerModelHasOneEvent('hasOneUpdating', $callback);
    }

    /**
     * Register a updated model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function hasOneUpdated($callback)
    {
        static::registerModelHasOneEvent('hasOneUpdated', $callback);
    }

    /**
     * Fire the given event for the model relationship.
     *
     * @param string $event
     * @param mixed  $related
     * @param bool   $halt
     *
     * @return mixed
     */
    public function fireModelHasOneEvent($event, $related = null, $halt = true)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        $event = 'hasOne' . ucfirst($event);

        // First, we will get the proper method to call on the event dispatcher, and then we
        // will attempt to fire a custom, object based event for the given event. If that
        // returns a result we can return that result, or we'll call the string events.
        $method = $halt ? 'until' : 'dispatch';

        $result = $this->filterModelEventResults(
            $this->fireCustomModelEvent($event, $method, $related)
        );

        if (false === $result) {
            return false;
        }

        return ! empty($result) ? $result : static::$dispatcher->{$method}(
            "eloquent.{$event}: " . static::class, [
                $this,
                $related,
            ]
        );
    }
}
