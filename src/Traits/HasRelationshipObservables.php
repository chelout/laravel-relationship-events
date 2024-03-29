<?php

namespace Chelout\RelationshipEvents\Traits;

use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

/**
 * Trait HasRelationshipObservables.
 *
 * @mixin \Illuminate\Database\Eloquent\Concerns\HasEvents
 */
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
        $methods = collect(
            class_uses(static::class),
        )->filter(fn ($trait) => Str::startsWith($trait, 'Chelout\RelationshipEvents\Concerns'))->flatMap(function ($trait) {
            $trait = new ReflectionClass($trait);
            $methods = $trait->getMethods(ReflectionMethod::IS_PUBLIC);

            return collect($methods)->filter(fn (ReflectionMethod $method) => $method->isStatic())->map(fn ($method) => $method->name);
        })->toArray();

        static::mergeRelationshipObservables($methods);
    }

    /**
     * Merge relationship observables.
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
                'saving', 'saved', 'restoring', 'restored', 'replicating',
                'deleting', 'deleted', 'forceDeleted',
            ],
            static::getRelationshipObservables(),
            $this->observables,
        );
    }

    /**
     * Get relationship observables.
     */
    public static function getRelationshipObservables(): array
    {
        return static::$relationshipObservables;
    }
}
