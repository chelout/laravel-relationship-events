<?php

namespace Chelout\RelationshipEvents\Relationships;

use Chelout\RelationshipEvents\Relationships\Traits\HasEventDispatcher;
use Chelout\RelationshipEvents\Relationships\Contracts\EventDispatcher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo as MorphToBase;

class MorphTo extends MorphToBase implements EventDispatcher
{
    use HasEventDispatcher;

    protected static $relationEventName = 'morphTo';

    /**
     * Associate the model instance to the given parent.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function associate($model)
    {
        $this->fireModelRelationshipEvent('associating', $model);

        $result = parent::associate($model);

        $this->fireModelRelationshipEvent('associated', $model);

        return $result;
    }

    /**
     * Dissociate previously associated model from the given parent.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function dissociate()
    {
        $parent = $this->getResults();

        $this->fireModelRelationshipEvent('dissociating', $parent);
        
        $result = parent::dissociate();

        if (! is_null($parent)) {
            $this->fireModelRelationshipEvent('dissociated', $parent);
        }

        return $result;
    }

    /**
     * Update the parent model on the relationship.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function update(array $attributes)
    {
        $related = $this->getResults();

        $this->fireModelRelationshipEvent('updating', $related);

        if ($result = parent::update($attributes)) {
            $related->fill($attributes)->syncChanges()->syncOriginal();

            $this->fireModelRelationshipEvent('updated', $related);
        }

        return $result;
    }

    /**
     * Fire the given event for the model relationship.
     *
     * @param  string  $event
     * @param  bool  $halt
     * @return mixed
     */
    protected function fireModelRelationshipEvent($event, $parent, $halt = true)
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
            "eloquent.".static::$relationEventName.ucfirst($event).": ".get_class($this->child), [
                $this->relation,
                $this->child,
                $parent,
            ]
        );
    }
}
