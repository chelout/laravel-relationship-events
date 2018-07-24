<?php

namespace Chelout\RelationshipEvents\Traits;

trait HasRelationshipObservables
{
    /**
     * @var array
     */
    protected static $relationshipObservables = [];

    /**
     * Get relationship observables.
     *
     * @return array
     */
    public static function getRelationshipObservables(): array
    {
        return static::$relationshipObservables;
    }

    /**
     * Merge relationship observables.
     *
     * @param array $relationshipObservables
     *
     * @return void
     */
    public static function mergeRelationshipObservables(array $relationshipObservables)
    {
        static::$relationshipObservables = array_merge(static::$relationshipObservables, $relationshipObservables);
    }

    /**
     * Get the observable event names.
     *
     * @return array
     */
    public function getObservableEvents()
    {
        return array_merge(
            [
                'retrieved', 'creating', 'created', 'updating', 'updated',
                'saving', 'saved', 'restoring', 'restored',
                'deleting', 'deleted', 'forceDeleted',
            ],
            static::getRelationshipObservables(),
            $this->observables
        );
    }
}
