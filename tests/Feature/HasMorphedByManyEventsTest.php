<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Chelout\RelationshipEvents\Tests\Stubs\Post;
use Chelout\RelationshipEvents\Tests\Stubs\Tag;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

final class HasMorphedByManyEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        Post::setupTable();
        Tag::setupTable();
    }

    #[Test]
    public function it_fires_morphedByManyAttaching_and_morphedByManyAttached_when_created(): void
    {
        Event::fake();

        $tag = Tag::create();
        $post = $tag->posts()->create([]);

        Event::assertDispatched(
            'eloquent.morphedByManyAttaching: ' . Tag::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphedByManyAttached: ' . Tag::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
    }

    #[Test]
    public function it_fires_morphedByManyAttaching_and_morphedByManyAttached_when_saved(): void
    {
        Event::fake();

        $tag = Tag::create();
        $post = $tag->posts()->save(new Post);

        Event::assertDispatched(
            'eloquent.morphedByManyAttaching: ' . Tag::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphedByManyAttached: ' . Tag::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
    }

    #[Test]
    public function it_fires_morphedByManyAttaching_and_morphedByManyAttached_when_attached(): void
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $tag->posts()->attach($post);

        Event::assertDispatched(
            'eloquent.morphedByManyAttaching: ' . Tag::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphedByManyAttached: ' . Tag::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
    }

    #[Test]
    public function it_fires_morphedByManyDetaching_and_morphedByManyDetached_when_detached(): void
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $tag->posts()->attach($post);
        $tag->posts()->detach($post);

        Event::assertDispatched(
            'eloquent.morphedByManyDetaching: ' . Tag::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphedByManyDetached: ' . Tag::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
    }

    #[Test]
    public function it_fires_morphedByManySyncing_and_morphedByManySynced(): void
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $tag->posts()->sync($post);

        Event::assertDispatched(
            'eloquent.morphedByManySyncing: ' . Tag::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphedByManySynced: ' . Tag::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
    }

    #[Test]
    public function it_fires_morphedByManyToggling_and_morphedByManyToggled(): void
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $tag->posts()->toggle($post);

        Event::assertDispatched(
            'eloquent.morphedByManyToggling: ' . Tag::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphedByManyToggled: ' . Tag::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
    }

    #[Test]
    public function it_fires_morphedByManyUpdatingExistingPivot_and_morphedByManyUpdatedExistingPivot(): void
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $tag->posts()->attach($post);
        $tag->posts()->updateExistingPivot(1, ['created_at' => now()]);

        Event::assertDispatched(
            'eloquent.morphedByManyUpdatingExistingPivot: ' . Tag::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphedByManyUpdatedExistingPivot: ' . Tag::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
    }
}
