<?php

namespace Chelout\RelationshipEvents\Helpers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as BaseCollection;

class AttributesMethods
{
    /**
     * Get all of the IDs from the given mixed value.
     *
     * @param mixed $value
     *
     * @return array
     */
    public static function parseIds($value)
    {
        if ($value instanceof Model) {
            return [$value->getKey()];
        }

        if ($value instanceof Collection) {
            return $value->modelKeys();
        }

        if ($value instanceof BaseCollection) {
            return $value->toArray();
        }

        return (array) $value;
    }

    /**
     * Parse ids for event.
     *
     * @param array $ids
     *
     * @return array
     */
    public static function parseIdsForEvent(array $ids): array
    {
        return array_map(function ($key, $id) {
            return is_array($id) ? $key : $id;
        }, array_keys($ids), $ids);
    }

    /**
     * Parse attributes for event.
     *
     * @param mixed $rawIds
     * @param array $parsedIds
     * @param array $attributes
     *
     * @return array
     */
    public static function parseAttributesForEvent($rawIds, array $parsedIds, array $attributes = []): array
    {
        return is_array($rawIds) ? array_filter($parsedIds, function ($id) {
            return is_array($id) && ! empty($id);
        }) : $attributes;
    }
}
