<?php

namespace Chelout\RelationshipEvents\Relationships\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait HasOneOrManyMethods
{
    /**
     * Create a new instance of the related model.
     *
     * @param  array  $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = [])
    {
        return tap($this->related->newInstance($attributes), function ($instance) {
            $this->fireModelRelationshipEvent('creating', $instance);

            $this->setForeignAttributesForCreate($instance);

            if (false !== $instance->save()) {
                $this->fireModelRelationshipEvent('created', $instance, false);
            }
        });
    }

    /**
     * Attach a model instance to the parent model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * 
     * @return \Illuminate\Database\Eloquent\Model|false
     */
    public function save(Model $model)
    {
        $this->fireModelRelationshipEvent('saving', $model);

        $result = parent::save($model);

        if (false !== $result) {
            $this->fireModelRelationshipEvent('saved', $result, false);
        }

        return $result;
    }

    /**
     * Perform an update on all the related models.
     *
     * @param  array  $attributes
     *
     * @return int
     */
    public function update(array $attributes)
    {
        $related = $this->getResults();

        $this->fireModelRelationshipEvent('updating', $related);

        if ($result = parent::update($attributes)) {
            if ($related instanceof Model) {
                $this->updateRelated($related, $attributes);
            }
            if ($related instanceof Collection) {
                $related->each(function ($model) use ($attributes) {
                    $this->updateRelated($model, $attributes);
                });
            }

            $this->fireModelRelationshipEvent('updated', $related, false);
        }

        return $result;
    }

    /**
     * Fire the given event for the model relationship.
     *
     * @param  string  $event
     * @param  mixed $related
     * @param  bool  $halt
     *
     * @return mixed
     */
    protected function fireModelRelationshipEvent($event, $related = null, $halt = true)
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
            'eloquent.' . static::$relationEventName . ucfirst($event) . ': ' . get_class($this->parent), [
                $this->parent,
                $related,
            ]
        );
    }

    /**
     * Updated related model's attributes.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $related
     * @param  array  $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function updateRelated(Model $related, array $attributes): Model
    {
        return $related->fill($attributes)->syncChanges()->syncOriginal();
    }
}
