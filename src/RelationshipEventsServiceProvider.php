<?php

namespace Chelout\RelationshipEvents;

use Illuminate\Support\ServiceProvider;

class RelationshipEventsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        Relationships\BelongsTo::setEventDispatcher($this->app['events']);
        Relationships\BelongsToMany::setEventDispatcher($this->app['events']);
        Relationships\HasMany::setEventDispatcher($this->app['events']);
        Relationships\HasOne::setEventDispatcher($this->app['events']);
        Relationships\MorphedByMany::setEventDispatcher($this->app['events']);
        Relationships\MorphMany::setEventDispatcher($this->app['events']);
        Relationships\MorphOne::setEventDispatcher($this->app['events']);
        Relationships\MorphTo::setEventDispatcher($this->app['events']);
        Relationships\MorphToMany::setEventDispatcher($this->app['events']);
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
    }
}
