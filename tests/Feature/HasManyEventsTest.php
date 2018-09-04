<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Chelout\RelationshipEvents\Tests\TestCase;
use Chelout\RelationshipEvents\Tests\Stubs\User;
use Chelout\RelationshipEvents\Tests\Stubs\Post;
use Chelout\RelationshipEvents\Tests\Stubs\Profile;

class HasManyEventsTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        User::setupTable();
        Post::setupTable();
    }

    /** @test */
    public function it_fires_hasManyCreating_and_hasManyCreated_when_belonged_model_with_many_created()
    {
        Event::fake();

        User::create()->posts()->create([]);

        Event::assertDispatched('eloquent.hasManyCreating: ' . User::class);
        Event::assertDispatched('eloquent.hasManyCreated: ' . User::class);
    }

    /** @test */
    public function it_fires_hasManySaving_and_hasManySaved_when_belonged_model_with_many_saved()
    {
        Event::fake();

        User::create()->posts()->save(new Post);

        Event::assertDispatched('eloquent.hasManySaving: ' . User::class);
        Event::assertDispatched('eloquent.hasManySaved: ' . User::class);
    }

    /** @test */
    public function it_fires_hasManyUpdating_and_hasManyUpdated_when_belonged_model_with_many_updated()
    {
        Event::fake();

        $user = User::create();
        $user->posts()->create([]);
        $user->posts()->update([]);

        Event::assertDispatched('eloquent.hasManyUpdating: ' . User::class);
        Event::assertDispatched('eloquent.hasManyUpdated: ' . User::class);
    }
}
