<?php

namespace Chelout\RelationshipEvents\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait HasOneOrManyMethods
{
    /**
     * Create a new instance of the related model.
     *
     * @param array $attributes
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
     * @param \Illuminate\Database\Eloquent\Model $model
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
     * @param array $attributes
     *
     * @return int
     */
    public function update(array $attributes)
    {
        $related = $this->getResults();

        if ($related instanceof Model) {
            $related->fill($attributes);
        }
        if ($related instanceof Collection) {
            $related->each(function ($model) use ($attributes) {
                /** @var Model $model */
                $model->fill($attributes);
            });
        }

        $this->fireModelRelationshipEvent('saving', $related);
        $this->fireModelRelationshipEvent('updating', $related);

        if ($result = parent::update($attributes)) {
            if ($related instanceof Model) {
                $related->syncChanges();
            }
            if ($related instanceof Collection) {
                $related->each(function ($model){
                    /** @var Model $model */
                    $model->syncChanges();
                });
            }

            $this->fireModelRelationshipEvent('updated', $related, false);
            $this->fireModelRelationshipEvent('saved', $related, false);

            if ($related instanceof Model) {
                $related->syncOriginal();
            }
            if ($related instanceof Collection) {
                $related->each(function ($model) use ($attributes) {
                    /** @var Model $model */
                    $model->syncOriginal();
                });
            }

        }

        return $result;
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
    public function fireModelRelationshipEvent($event, $related = null, $halt = true)
    {
        return $this->parent->{'fireModel' . class_basename(static::class) . 'Event'}($event, $related, $halt);
    }

}
