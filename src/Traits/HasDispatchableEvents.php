<?php

namespace Chelout\RelationshipEvents\Traits;

trait HasDispatchableEvents
{
    /**
     * Fire a custom model event for the given event.
     *
     * @param string $event
     * @param string $method
     * @param array  $params
     *
     * @return mixed|null
     */
    protected function fireCustomModelEvent($event, $method, $relation = null, ...$params)
    {
        if (! isset($this->dispatchesEvents[$event])) {
            return;
        }

        $result = static::$dispatcher->$method(new $this->dispatchesEvents[$event]($this, $relation, $params));

        if (! is_null($result)) {
            return $result;
        }
    }
}
