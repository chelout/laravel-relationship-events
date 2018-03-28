<?php

namespace Chelout\RelationshipEvents\Relationships\Concerns;

use Chelout\RelationshipEvents\Relationships\MorphedByMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasMorphedByManyEvents
{
    /**
     * Instantiate a new HasManyThrough relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @param  string  $name
     * @param  string  $table
     * @param  string  $foreignPivotKey
     * @param  string  $relatedPivotKey
     * @param  string  $parentKey
     * @param  string  $relatedKey
     * @param  string  $relationName
     * @param  bool  $inverse
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    protected function newMorphToMany(Builder $query, Model $parent, $name, $table, $foreignPivotKey,
                                      $relatedPivotKey, $parentKey, $relatedKey,
                                      $relationName = null, $inverse = false)
    {
        return new MorphedByMany($query, $parent, $name, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey,
            $relationName, $inverse);
    }

    /**
     * Register a model event with the dispatcher.
     *
     * @param  string  $event
     * @param  \Closure|string  $callback
     */
    protected static function registerModelMorphedByManyEvent($event, $callback)
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
     */
    public static function morphedByManyCreating($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyCreating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function morphedByManyCreated($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyCreated', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function morphedByManySaving($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManySaving', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function morphedByManySaved($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManySaved', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function morphedByManyAttaching($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyAttaching', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function morphedByManyAttached($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyAttached', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function morphedByManyDetaching($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyDetaching', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function morphedByManyDetached($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyDetached', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function morphedByManySyncing($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManySyncing', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function morphedByManySynced($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManySynced', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function morphedByManyToggling($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyToggling', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function morphedByManyToggled($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyToggled', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function morphedByManyUpdatingExistingPivot($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyUpdatingExistingPivot', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param  \Closure|string  $callback
     */
    public static function morphedByManyUpdatedExistingPivot($callback)
    {
        static::registerModelMorphedByManyEvent('morphedByManyUpdatedExistingPivot', $callback);
    }
}
