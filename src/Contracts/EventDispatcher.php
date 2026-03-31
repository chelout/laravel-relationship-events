<?php

namespace Chelout\RelationshipEvents\Contracts;

use Illuminate\Contracts\Events\Dispatcher;

interface EventDispatcher
{
    /**
     * Get the event dispatcher instance.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public static function getEventDispatcher(): ?Dispatcher;

    /**
     * Set the event dispatcher instance.
     */
    public static function setEventDispatcher(Dispatcher $dispatcher): void;

    /**
     * Unset the event dispatcher for models.
     */
    public static function unsetEventDispatcher(): void;
}
