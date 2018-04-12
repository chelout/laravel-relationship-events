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
    public static function getEventDispatcher();

    /**
     * Set the event dispatcher instance.
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $dispatcher
     */
    public static function setEventDispatcher(Dispatcher $dispatcher);

    /**
     * Unset the event dispatcher for models.
     */
    public static function unsetEventDispatcher();
}
