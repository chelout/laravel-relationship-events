<?php

namespace Chelout\RelationshipEvents;

use Chelout\RelationshipEvents\Contracts\EventDispatcher;
use Chelout\RelationshipEvents\Traits\HasEventDispatcher;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsToBase;

/**
 * Class BelongsTo.
 *
 *
 * @property-read \Chelout\RelationshipEvents\Concerns\HasBelongsToEvents $parent
 */
class BelongsTo extends BelongsToBase implements EventDispatcher
{
    use HasEventDispatcher;

    protected static $relationEventName = 'belongsTo';

    /**
     * Associate the model instance to the given parent.
     *
     * @param \Illuminate\Database\Eloquent\Model|int|string $model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function associate($model)
    {
        $this->parent->fireModelBelongsToEvent('associating', $this->relationName, $model);

        $result = parent::associate($model);

        $this->parent->fireModelBelongsToEvent('associated', $this->relationName, $model);

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

        $this->parent->fireModelBelongsToEvent('dissociating', $this->relationName, $parent);

        $result = parent::dissociate();

        if (! is_null($parent)) {
            $this->parent->fireModelBelongsToEvent('dissociated', $this->relationName, $parent);
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

        $this->parent->fireModelBelongsToEvent('updating', $this->relationName, $related);

        if ($result = $related->fill($attributes)->save()) {
            $this->parent->fireModelBelongsToEvent('updated', $this->relationName, $related);
        }

        return $result;
    }
}
