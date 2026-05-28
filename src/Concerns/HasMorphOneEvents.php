<?php

namespace Chelout\RelationshipEvents\Concerns;

use Chelout\RelationshipEvents\MorphOne;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasMorphOneEvents.
 *
 * @mixin \Chelout\RelationshipEvents\Traits\HasDispatchableEvents
 */
trait HasMorphOneEvents
{
    /**
     * Instantiate a new MorphOne relationship.
     *
     * @param string $type
     * @param string $id
     * @param string $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    #[\Override]
    protected function newMorphOne(Builder $query, Model $parent, $type, $id, $localKey): MorphOne
    {
        return new MorphOne($query, $parent, $type, $id, $localKey);
    }

    /**
     * Register a model event with the dispatcher.
     *
     * @param string $event
     * @param Closure|string $callback
     */
    protected static function registerModelMorphOneEvent($event, $callback): void
    {
        if (isset(static::$dispatcher)) {
            $name = static::class;

            static::$dispatcher->listen("eloquent.{$event}: {$name}", $callback);
        }
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphOneCreating($callback): void
    {
        static::registerModelMorphOneEvent('morphOneCreating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphOneCreated($callback): void
    {
        static::registerModelMorphOneEvent('morphOneCreated', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphOneSaving($callback): void
    {
        static::registerModelMorphOneEvent('morphOneSaving', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphOneSaved($callback): void
    {
        static::registerModelMorphOneEvent('morphOneSaved', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphOneUpdating($callback): void
    {
        static::registerModelMorphOneEvent('morphOneUpdating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphOneUpdated($callback): void
    {
        static::registerModelMorphOneEvent('morphOneUpdated', $callback);
    }

    /**
     * Fire the given event for the model relationship.
     *
     * @param string $event
     * @param mixed $related
     * @param bool $halt
     *
     * @return mixed
     */
    public function fireModelMorphOneEvent($event, $related = null, $halt = true): mixed
    {
        if (!isset(static::$dispatcher)) {
            return true;
        }

        $event = 'morphOne' . ucfirst($event);

        // First, we will get the proper method to call on the event dispatcher, and then we
        // will attempt to fire a custom, object based event for the given event. If that
        // returns a result we can return that result, or we'll call the string events.
        $method = $halt ? 'until' : 'dispatch';

        $result = $this->filterModelEventResults(
            $this->fireCustomModelEvent($event, $method, $related),
        );

        if ($result === false) {
            return false;
        }

        return !empty($result) ? $result : static::$dispatcher->{$method}(
            "eloquent.{$event}: " . static::class,
            [
                $this,
                $related,
            ]
        );
    }
}
