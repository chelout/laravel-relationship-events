<?php

namespace Chelout\RelationshipEvents;

use Chelout\RelationshipEvents\Contracts\EventDispatcher;
use Chelout\RelationshipEvents\Traits\HasEventDispatcher;
use Illuminate\Database\Eloquent\Relations\MorphTo as MorphToBase;

/**
 * Class MorphTo.
 *
 *
 * @property-read \Chelout\RelationshipEvents\Concerns\HasMorphToEvents $parent
 */
class MorphTo extends MorphToBase implements EventDispatcher
{
    use HasEventDispatcher;

    /**
     * Associate the model instance to the given parent.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function associate($model)
    {
        $this->parent->fireModelMorphToEvent('associating', $this->relationName, $model);

        $result = parent::associate($model);

        $this->parent->fireModelMorphToEvent('associated', $this->relationName, $model);

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

        $this->parent->fireModelMorphToEvent('dissociating', $this->relationName, $parent);

        $result = parent::dissociate();

        if (! is_null($parent)) {
            $this->parent->fireModelMorphToEvent('dissociated', $this->relationName, $parent);
        }

        return $result;
    }

    /**
     * Update the parent model on the relationship.
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function update(array $attributes)
    {
        $related = $this->getResults();

        $this->parent->fireModelMorphToEvent('updating', $this->relationName, $related);

        $result = $related->fill($attributes)->save();
        if ($related && $result) {
            $this->parent->fireModelMorphToEvent('updated', $this->relationName, $related);
        }

        return $result;
    }
}
