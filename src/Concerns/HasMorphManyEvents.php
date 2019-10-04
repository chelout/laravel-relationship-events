<?php

namespace Chelout\RelationshipEvents\Concerns;

use Chelout\RelationshipEvents\MorphMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasMorphManyEvents.
 *
 *
 * @mixin \Chelout\RelationshipEvents\Traits\HasDispatchableEvents
 */
trait HasMorphManyEvents
{
    /**
     * Instantiate a new MorphMany relationship.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model   $parent
     * @param string                                $type
     * @param string                                $id
     * @param string                                $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    protected function newMorphMany(Builder $query, Model $parent, $type, $id, $localKey)
    {
        return new MorphMany($query, $parent, $type, $id, $localKey);
    }

    /**
     * Register a model event with the dispatcher.
     *
     * @param string          $event
     * @param \Closure|string $callback
     */
    protected static function registerModelMorphManyEvent($event, $callback)
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
    public static function morphManyCreating($callback)
    {
        static::registerModelMorphManyEvent('morphManyCreating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphManyCreated($callback)
    {
        static::registerModelMorphManyEvent('morphManyCreated', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphManySaving($callback)
    {
        static::registerModelMorphManyEvent('morphManySaving', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphManySaved($callback)
    {
        static::registerModelMorphManyEvent('morphManySaved', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphManyUpdating($callback)
    {
        static::registerModelMorphManyEvent('morphManyUpdating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     */
    public static function morphManyUpdated($callback)
    {
        static::registerModelMorphManyEvent('morphManyUpdated', $callback);
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
    public function fireModelMorphManyEvent($event, $related = null, $halt = true)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        $event = 'morphMany' . ucfirst($event);

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
