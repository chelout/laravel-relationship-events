<?php

namespace Chelout\RelationshipEvents\Traits;

use ReflectionClass;
use ReflectionMethod;

trait HasRelationshipObservables
{
    /**
     * @var array
     */
    protected static $relationshipObservables = [];

    /**
     * Initialize relationship observables.
     *
     * @return void
     */
    public static function bootHasRelationshipObservables()
    {
        $class = static::class;
        $traits = [];
        
        do {
            $traits = array_merge(class_uses($class), $traits);
        } while ($class = get_parent_class($class));

        foreach ($traits as $trait => $same) {
            $traits = array_merge(class_uses($trait), $traits);
        }

        $trait_uses = array_unique($traits);

        $methods = collect($trait_uses)
            ->filter(function ($trait) {
                return starts_with($trait, 'Chelout\RelationshipEvents\Concerns');
            })
            ->flatMap(function ($trait) {
                $trait = new ReflectionClass($trait);
                $methods = $trait->getMethods(ReflectionMethod::IS_PUBLIC);

                return collect($methods)->filter(function ($method) {
                    return $method->isStatic();
                })
                    ->map(function ($method) {
                        return $method->name;
                    });
            })
            ->toArray();

        static::mergeRelationshipObservables($methods);
    }

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
