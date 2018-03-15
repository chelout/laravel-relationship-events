<?php

namespace Chelout\RelationshipEvents\Relationships;

use Illuminate\Database\Eloquent\Relations\HasOneOrMany as HasOneOrManyBase;

class HasOneOrMany extends HasOneOrManyBase
{
    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected static $dispatcher;

    /**
     * Attach a model instance to the parent model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Database\Eloquent\Model|false
     */
    public function save(Model $model)
    {
        // dump('HasOneOrMany::saving');
        $this->fireModelRelationshipEvent('saving');

        $this->setForeignAttributesForCreate($model);

        $result = $model->save() ? $model : false;

        // dump('HasOneOrMany::saved');
        $this->fireModelRelationshipEvent('saved');

        return $result;
    }

    /**
     * Attach a collection of models to the parent instance.
     *
     * @param  \Traversable|array  $models
     * @return \Traversable|array
     */
    public function saveMany($models)
    {
        foreach ($models as $model) {
            $this->save($model);
        }

        return $models;
    }

    /**
     * Create a new instance of the related model.
     *
     * @param  array  $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = [])
    {
        return tap($this->related->newInstance($attributes), function ($instance) {
            $this->setForeignAttributesForCreate($instance);

            $instance->save();

            dump('HasOneOrMany::create');
        });
    }

    /**
     * Create a Collection of new instances of the related model.
     *
     * @param  array  $records
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createMany(array $records)
    {
        $instances = $this->related->newCollection();

        foreach ($records as $record) {
            $instances->push($this->create($record));
        }

        return $instances;
    }

    /**
     * Perform an update on all the related models.
     *
     * @param  array  $attributes
     * @return int
     */
    public function update(array $attributes)
    {
        if ($this->related->usesTimestamps()) {
            $attributes[$this->relatedUpdatedAt()] = $this->related->freshTimestampString();
        }

        return $this->query->update($attributes);
    }

    /**
     * Fire the given event for the model relationship.
     *
     * @param  string  $event
     * @param  bool  $halt
     * @return mixed
     */
    protected function fireModelRelationshipEvent($event, $halt = true)
    {
        if (! isset(static::$dispatcher)) {
            return true;
        }

        // First, we will get the proper method to call on the event dispatcher, and then we
        // will attempt to fire a custom, object based event for the given event. If that
        // returns a result we can return that result, or we'll call the string events.
        $method = $halt ? 'until' : 'fire';

        // $result = $this->filterModelEventResults(
        //     $this->fireCustomModelEvent($event, $method)
        // );

        // if ($result === false) {
        //     return false;
        // }

        // return ! empty($result) ? $result : static::$dispatcher->{$method}(
        //     "eloquent.{$event}: ".static::class, $this
        // );

        return static::$dispatcher->{$method}(
            "eloquent.hasOneOrMany".ucfirst($event).": ".get_class($this->parent), [
                $this->parent,
                $this->related,
            ]
        );
    }

    /**
     * Get the event dispatcher instance.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public static function getEventDispatcher()
    {
        return static::$dispatcher;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     * @return void
     */
    public static function setEventDispatcher(Dispatcher $dispatcher)
    {
        static::$dispatcher = $dispatcher;
    }

    /**
     * Unset the event dispatcher for models.
     *
     * @return void
     */
    public static function unsetEventDispatcher()
    {
        static::$dispatcher = null;
    }
}
