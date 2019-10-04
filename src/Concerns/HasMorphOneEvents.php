<?php

namespace Chelout\RelationshipEvents\Concerns;

use Chelout\RelationshipEvents\MorphOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasMorphOneEvents.
 *
 *
 * @mixin \Chelout\RelationshipEvents\Traits\HasDispatchableEvents
 */
trait HasMorphOneEvents
{
    /**
     * Instantiate a new MorphOne relationship.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $parent
     * @param string                                $type
     * @param string                                $id
     * @param string                                $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    protected function newMorphOne(Builder $query, Model $parent, $type, $id, $localKey)
    {
        return new MorphOne($query, $parent, $type, $id, $localKey);
    }

    /**
     * Register a model event with the dispatcher.
     *
     * @param string          $event
     * @param \Closure|string $callback
     */
    protected static function registerModelMorphOneEvent($event, $callback)
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
    public static function morphOneCreating($callback)
    {
        static::registerModelMorphOneEvent('morphOneCreating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphOneCreated($callback)
    {
        static::registerModelMorphOneEvent('morphOneCreated', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphOneSaving($callback)
    {
        static::registerModelMorphOneEvent('morphOneSaving', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphOneSaved($callback)
    {
        static::registerModelMorphOneEvent('morphOneSaved', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphOneUpdating($callback)
    {
        static::registerModelMorphOneEvent('morphOneUpdating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphOneUpdated($callback)
    {
        static::registerModelMorphOneEvent('morphOneUpdated', $callback);
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
    public function fireModelMorphOneEvent($event, $related = null, $halt = true)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        $event = 'morphOne' . ucfirst($event);

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
