<?php

namespace Chelout\RelationshipEvents\Traits;

use Illuminate\Contracts\Events\Dispatcher;

/**
 * Trait HasEventDispatcher.
 */
trait HasEventDispatcher
{
    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected static ?Dispatcher $dispatcher = null;

    /**
     * Get the event dispatcher instance.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public static function getEventDispatcher(): ?Dispatcher
    {
        return static::$dispatcher;
    }

    /**
     * Set the event dispatcher instance.
     */
    public static function setEventDispatcher(Dispatcher $dispatcher): void
    {
        static::$dispatcher = $dispatcher;
    }

    /**
     * Unset the event dispatcher for models.
     */
    public static function unsetEventDispatcher(): void
    {
        static::$dispatcher = null;
    }
}
