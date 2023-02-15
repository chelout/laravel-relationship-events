<?php

namespace Chelout\RelationshipEvents;

use Chelout\RelationshipEvents\Contracts\EventDispatcher;
use Chelout\RelationshipEvents\Traits\HasEventDispatcher;
use Chelout\RelationshipEvents\Traits\HasOneOrManyMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany as MorphManyBase;

/**
 * Class MorphMany.
 */
class MorphMany extends MorphManyBase implements EventDispatcher
{
    use HasEventDispatcher;
    use HasOneOrManyMethods;

    /**
     * Attach a model instance to the parent model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(Model $model)
    {
        $this->fireModelRelationshipEvent('saving', $model);

        $result = parent::save($model);

        if ($result !== false) {
            $this->fireModelRelationshipEvent('saved', $result, false);
        }

        return $result;
    }
}
