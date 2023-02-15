<?php

namespace Chelout\RelationshipEvents\Traits;

/**
 * Trait HasDispatchableEvents.
 *
 * @mixin \Illuminate\Database\Eloquent\Concerns\HasEvents
 */
trait HasDispatchableEvents
{
    /**
     * Fire a custom model event for the given event.
     *
     * @param string $event
     * @param string $method
     * @param array $params
     *
     * @return null|mixed
     */
    protected function fireCustomModelEvent($event, $method, ...$params)
    {
        if (!isset($this->dispatchesEvents[$event])) {
            return;
        }

        $result = static::$dispatcher->$method(new $this->dispatchesEvents[$event]($this, $params));

        if ($result !== null) {
            return $result;
        }
    }
}
