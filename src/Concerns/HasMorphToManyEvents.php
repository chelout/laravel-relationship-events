<?php

namespace Chelout\RelationshipEvents\Concerns;

use Chelout\RelationshipEvents\Helpers\AttributesMethods;
use Chelout\RelationshipEvents\MorphToMany;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasMorphToManyEvents.
 *
 * @mixin \Chelout\RelationshipEvents\Traits\HasDispatchableEvents
 */
trait HasMorphToManyEvents
{
    /**
     * Instantiate a new HasManyThrough relationship.
     *
     * @param string $name
     * @param string $table
     * @param string $foreignPivotKey
     * @param string $relatedPivotKey
     * @param string $parentKey
     * @param string $relatedKey
     * @param string $relationName
     * @param bool $inverse
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    #[\Override]
    protected function newMorphToMany(
        Builder $query,
        Model $parent,
        $name,
        $table,
        $foreignPivotKey,
        $relatedPivotKey,
        $parentKey,
        $relatedKey,
        $relationName = null,
        $inverse = false,
    ): MorphToMany {
        return new MorphToMany(
            $query,
            $parent,
            $name,
            $table,
            $foreignPivotKey,
            $relatedPivotKey,
            $parentKey,
            $relatedKey,
            $relationName,
            $inverse,
        );
    }

    /**
     * Register a model event with the dispatcher.
     *
     * @param string $event
     * @param Closure|string $callback
     */
    protected static function registerModelMorphToManyEvent($event, $callback): void
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
    public static function morphToManyCreating($callback): void
    {
        static::registerModelMorphToManyEvent('morphToManyCreating', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphToManyCreated($callback): void
    {
        static::registerModelMorphToManyEvent('morphToManyCreated', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphToManySaving($callback): void
    {
        static::registerModelMorphToManyEvent('morphToManySaving', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphToManySaved($callback): void
    {
        static::registerModelMorphToManyEvent('morphToManySaved', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphToManyAttaching($callback): void
    {
        static::registerModelMorphToManyEvent('morphToManyAttaching', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphToManyAttached($callback): void
    {
        static::registerModelMorphToManyEvent('morphToManyAttached', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphToManyDetaching($callback): void
    {
        static::registerModelMorphToManyEvent('morphToManyDetaching', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphToManyDetached($callback): void
    {
        static::registerModelMorphToManyEvent('morphToManyDetached', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphToManySyncing($callback): void
    {
        static::registerModelMorphToManyEvent('morphToManySyncing', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphToManySynced($callback): void
    {
        static::registerModelMorphToManyEvent('morphToManySynced', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphToManyToggling($callback): void
    {
        static::registerModelMorphToManyEvent('morphToManyToggling', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphToManyToggled($callback): void
    {
        static::registerModelMorphToManyEvent('morphToManyToggled', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphToManyUpdatingExistingPivot($callback): void
    {
        static::registerModelMorphToManyEvent('morphToManyUpdatingExistingPivot', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param Closure|string $callback
     */
    public static function morphToManyUpdatedExistingPivot($callback): void
    {
        static::registerModelMorphToManyEvent('morphToManyUpdatedExistingPivot', $callback);
    }

    /**
     * Fire the given event for the model relationship.
     *
     * @param string $event
     * @param string $relation
     * @param mixed $ids
     * @param array $attributes
     * @param bool $halt
     *
     * @return mixed
     */
    public function fireModelMorphToManyEvent($event, $relation, $ids, $attributes = [], $halt = true): mixed
    {
        if (!isset(static::$dispatcher)) {
            return true;
        }

        $parsedIds = AttributesMethods::parseIds($ids);
        $parsedIdsForEvent = AttributesMethods::parseIdsForEvent($parsedIds);
        $parseAttributesForEvent = AttributesMethods::parseAttributesForEvent($ids, $parsedIds, $attributes);

        $event = 'morphToMany' . ucfirst($event);

        // First, we will get the proper method to call on the event dispatcher, and then we
        // will attempt to fire a custom, object based event for the given event. If that
        // returns a result we can return that result, or we'll call the string events.
        $method = $halt ? 'until' : 'dispatch';

        $result = $this->filterModelEventResults(
            $this->fireCustomModelEvent($event, $method, $relation, $parsedIdsForEvent, $parseAttributesForEvent),
        );

        if ($result === false) {
            return false;
        }

        return !empty($result) ? $result : static::$dispatcher->{$method}(
            "eloquent.{$event}: " . static::class,
            [
                $relation,
                $this,
                $parsedIdsForEvent,
                $parseAttributesForEvent,
            ]
        );
    }
}
