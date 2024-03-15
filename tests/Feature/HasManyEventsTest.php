<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Chelout\RelationshipEvents\Tests\Stubs\Post;
use Chelout\RelationshipEvents\Tests\Stubs\User;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

final class HasManyEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        User::setupTable();
        Post::setupTable();
    }

    #[Test]
    public function it_fires_hasManyCreating_and_hasManyCreated_when_belonged_model_with_many_created(): void
    {
        Event::fake();

        $user = User::create();
        $post = $user->posts()->create([]);

        Event::assertDispatched(
            'eloquent.hasManyCreating: ' . User::class,
            function ($event, $callback) use ($user, $post) {
                return $callback[0]->is($user) && $callback[1]->is($post);
            }
        );
        Event::assertDispatched(
            'eloquent.hasManyCreated: ' . User::class,
            function ($event, $callback) use ($user, $post) {
                return $callback[0]->is($user) && $callback[1]->is($post);
            }
        );
    }

    #[Test]
    public function it_fires_hasManySaving_and_hasManySaved_when_belonged_model_with_many_saved(): void
    {
        Event::fake();

        $user = User::create();
        $post = $user->posts()->save(new Post);

        Event::assertDispatched(
            'eloquent.hasManySaving: ' . User::class,
            function ($event, $callback) use ($user, $post) {
                return $callback[0]->is($user) && $callback[1]->is($post);
            }
        );
        Event::assertDispatched(
            'eloquent.hasManySaved: ' . User::class,
            function ($event, $callback) use ($user, $post) {
                return $callback[0]->is($user) && $callback[1]->is($post);
            }
        );
    }

    #[Test]
    public function it_fires_hasManyUpdating_and_hasManyUpdated_when_belonged_model_with_many_updated(): void
    {
        Event::fake();

        $user = User::create();
        $post = $user->posts()->create([]);
        $user->posts()->update([]);

        Event::assertDispatched(
            'eloquent.hasManyUpdating: ' . User::class,
            function ($event, $callback) use ($user, $post) {
                return $callback[0]->is($user) && $callback[1][0]->is($post);
            }
        );
        Event::assertDispatched(
            'eloquent.hasManyUpdated: ' . User::class,
            function ($event, $callback) use ($user, $post) {
                return $callback[0]->is($user) && $callback[1][0]->is($post);
            }
        );
    }
}
