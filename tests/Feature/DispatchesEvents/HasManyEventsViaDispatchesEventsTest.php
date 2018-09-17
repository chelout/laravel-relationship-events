<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use Chelout\RelationshipEvents\Tests\Stubs\Events\HasMany\HasManyCreated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasMany\HasManyCreating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasMany\HasManySaved;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasMany\HasManySaving;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasMany\HasManyUpdated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasMany\HasManyUpdating;
use Chelout\RelationshipEvents\Tests\Stubs\Post;
use Chelout\RelationshipEvents\Tests\Stubs\User;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasManyEventsViaDispatchesEventsTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        User::setupTable();
        Post::setupTable();
    }

    /** @test */
    public function it_fires_hasManyCreating_and_hasManyCreated_when_belonged_model_with_many_created_via_dispatchesEvents()
    {
        Event::fake();

        $user = User::create();
        $post = $user->posts()->create([]);

        Event::assertDispatched(
            HasManyCreating::class,
            function ($event) use ($user, $post) {
                return $event->user->is($user) && $event->post->is($post);
            }
        );
        Event::assertDispatched(
            HasManyCreated::class,
            function ($event) use ($user, $post) {
                return $event->user->is($user) && $event->post->is($post);
            }
        );
    }

    /** @test */
    public function it_fires_hasManySaving_and_hasManySaved_when_belonged_model_with_many_saved_via_dispatchesEvents()
    {
        Event::fake();

        $user = User::create();
        $post = $user->posts()->save(new Post);

        Event::assertDispatched(
            HasManySaving::class,
            function ($event) use ($user, $post) {
                return $event->user->is($user) && $event->post->is($post);
            }
        );
        Event::assertDispatched(
            HasManySaved::class,
            function ($event) use ($user, $post) {
                return $event->user->is($user) && $event->post->is($post);
            }
        );
    }

    /** @test */
    public function it_fires_hasManyUpdating_and_hasManyUpdated_when_belonged_model_with_many_updated_via_dispatchesEvents()
    {
        Event::fake();

        $user = User::create();
        $post = $user->posts()->create([]);
        $user->posts()->update([]);

        Event::assertDispatched(
            HasManyUpdating::class,
            function ($event) use ($user, $post) {
                return $event->user->is($user) && $event->post->first()->is($post);
            }
        );
        Event::assertDispatched(
            HasManyUpdated::class,
            function ($event) use ($user, $post) {
                return $event->user->is($user) && $event->post->first()->is($post);
            }
        );
    }
}
