<?php

namespace Chelout\RelationshipEvents;

use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Support\ServiceProvider;

class RelationshipEventsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        Relationships\BelongsTo::setEventDispatcher($this->app['events']);
        Relationships\BelongsToMany::setEventDispatcher($this->app['events']);
        Relationships\HasMany::setEventDispatcher($this->app['events']);
        Relationships\HasOne::setEventDispatcher($this->app['events']);
        Relationships\MorphMany::setEventDispatcher($this->app['events']);
        Relationships\MorphOne::setEventDispatcher($this->app['events']);
        Relationships\MorphTo::setEventDispatcher($this->app['events']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // 
    }
}
