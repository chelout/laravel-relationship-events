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
        HasOneOrMany::setEventDispatcher($this->app['events']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(HasOneOrMany::class, Relationships\HasOneOrMany::class);
    }
}
