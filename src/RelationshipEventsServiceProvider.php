<?php

namespace Chelout\RelationshipEvents;

use Illuminate\Support\ServiceProvider;

/**
 * Class RelationshipEventsServiceProvider.
 */
class RelationshipEventsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        BelongsTo::setEventDispatcher($this->app['events']);
        BelongsToMany::setEventDispatcher($this->app['events']);
        HasMany::setEventDispatcher($this->app['events']);
        HasOne::setEventDispatcher($this->app['events']);
        MorphedByMany::setEventDispatcher($this->app['events']);
        MorphMany::setEventDispatcher($this->app['events']);
        MorphOne::setEventDispatcher($this->app['events']);
        MorphTo::setEventDispatcher($this->app['events']);
        MorphToMany::setEventDispatcher($this->app['events']);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
    }
}
