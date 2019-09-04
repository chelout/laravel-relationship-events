<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use Chelout\RelationshipEvents\Tests\Stubs\Post;
use Chelout\RelationshipEvents\Tests\Stubs\Tag;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasMorphToManyEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        Post::setupTable();
        Tag::setupTable();
    }

    /** @test */
    public function it_fires_morphToManyAttaching_and_morphToManyAttached()
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $post->tags()->attach($tag);

        Event::assertDispatched(
            'eloquent.morphToManyAttaching: ' . Post::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'tags' && $callback[1]->is($post) && $callback[2][0] == $tag->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphToManyAttached: ' . Post::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'tags' && $callback[1]->is($post) && $callback[2][0] == $tag->id;
            }
        );
    }

    /** @test */
    public function it_fires_morphToManyDetaching_and_morphToManyDetached()
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $post->tags()->attach($tag);
        $post->tags()->detach($tag);

        Event::assertDispatched(
            'eloquent.morphToManyDetaching: ' . Post::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'tags' && $callback[1]->is($post) && $callback[2][0] == $tag->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphToManyDetached: ' . Post::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'tags' && $callback[1]->is($post) && $callback[2][0] == $tag->id;
            }
        );
    }

    /** @test */
    public function it_fires_morphToManySyncing_and_morphToManySynced()
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $post->tags()->sync($tag);

        Event::assertDispatched(
            'eloquent.morphToManySyncing: ' . Post::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'tags' && $callback[1]->is($post) && $callback[2][0] == $tag->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphToManySynced: ' . Post::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'tags' && $callback[1]->is($post) && $callback[2][0] == $tag->id;
            }
        );
    }

    /** @test */
    public function it_fires_morphToManyUpdatingExistingPivot_and_morphToManyUpdatedExistingPivot()
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $post->tags()->sync($tag);
        $post->tags()->updateExistingPivot(1, ['created_at' => now()]);

        Event::assertDispatched(
            'eloquent.morphToManyUpdatingExistingPivot: ' . Post::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'tags' && $callback[1]->is($post) && $callback[2][0] == $tag->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphToManyUpdatedExistingPivot: ' . Post::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'tags' && $callback[1]->is($post) && $callback[2][0] == $tag->id;
            }
        );
    }
}
