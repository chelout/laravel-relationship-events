<?php

namespace Chelout\RelationshipEvents\Relationships\Concerns;

use Chelout\RelationshipEvents\Relationships\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasOneEvents
{
    /**
     * Instantiate a new HasOne relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    protected function newHasOne(Builder $query, Model $parent, $foreignKey, $localKey)
    {
        return new HasOne($query, $parent, $foreignKey, $localKey);
    }

    /**
     * Register a model has one event with the dispatcher.
     *
     * @param  string  $event
     * @param  \Closure|string  $callback
     * @return void
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
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function hasOneCreating($callback)
    {
        static::registerModelHasOneEvent('hasOneCreating', $callback);
    }

    /**
     * Register a created model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function hasOneCreated($callback)
    {
        static::registerModelHasOneEvent('hasOneCreated', $callback);
    }

    /**
     * Register a saving model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function hasOneSaving($callback)
    {
        static::registerModelHasOneEvent('hasOneSaving', $callback);
    }

    /**
     * Register a saved model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function hasOneSaved($callback)
    {
        static::registerModelHasOneEvent('hasOneSaved', $callback);
    }

    /**
     * Register a updating model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function hasOneUpdating($callback)
    {
        static::registerModelHasOneEvent('hasOneUpdating', $callback);
    }

    /**
     * Register a updated model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public static function hasOneUpdated($callback)
    {
        static::registerModelHasOneEvent('hasOneUpdated', $callback);
    }
}
