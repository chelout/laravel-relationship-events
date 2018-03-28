<?php

namespace Chelout\RelationshipEvents\Relationships\Concerns;

use Chelout\RelationshipEvents\Relationships\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasManyEvents
{
    /**
     * Instantiate a new HasMany relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected function newHasMany(Builder $query, Model $parent, $foreignKey, $localKey)
    {
        return new HasMany($query, $parent, $foreignKey, $localKey);
    }

    /**
     * Register a model event with the dispatcher.
     *
     * @param  string  $event
     * @param  \Closure|string  $callback
     * @return void
     */
    protected static function registerModelHasManyEvent($event, $callback)
    {
        if (isset(static::$dispatcher)) {
            $name = static::class;

            static::$dispatcher->listen("eloquent.{$event}: {$name}", $callback);
        }
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function hasManyCreating($callback)
    {
        static::registerModelHasManyEvent('hasManyCreating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function hasManyCreated($callback)
    {
        static::registerModelHasManyEvent('hasManyCreated', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function hasManySaving($callback)
    {
        static::registerModelHasManyEvent('hasManySaving', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function hasManySaved($callback)
    {
        static::registerModelHasManyEvent('hasManySaved', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function hasManyUpdating($callback)
    {
        static::registerModelHasManyEvent('hasManyUpdating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function hasManyUpdated($callback)
    {
        static::registerModelHasManyEvent('hasManyUpdated', $callback);
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
    public function fireModelHasManyEvent($event, $related = null, $halt = true)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        $event = 'hasMany' . ucfirst($event);

        // First, we will get the proper method to call on the event dispatcher, and then we
        // will attempt to fire a custom, object based event for the given event. If that
        // returns a result we can return that result, or we'll call the string events.
        $method = $halt ? 'until' : 'fire';

        $result = $this->filterModelEventResults(
            $this->fireCustomModelEvent($event, $method)
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
