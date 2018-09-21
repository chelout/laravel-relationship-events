<?php

namespace Chelout\RelationshipEvents\Tests\Feature\DispatchesEvents;

use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyAttached;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyAttaching;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyDetached;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyDetaching;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManySynced;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManySyncing;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyToggled;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyToggling;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyUpdatedExistingPivot;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyUpdatingExistingPivot;
use Chelout\RelationshipEvents\Tests\Stubs\Post;
use Chelout\RelationshipEvents\Tests\Stubs\Tag;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasMorphedByManyEventsViaDispatchesEventsTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        Post::setupTable();
        Tag::setupTable();
    }

    /** @test */
    public function it_fires_morphedByManyAttaching_and_morphedByManyAttached_when_created()
    {
        Event::fake();

        $tag = Tag::create();
        $post = $tag->posts()->create([]);

        Event::assertDispatched(
            MorphedByManyAttaching::class,
            function ($event) use ($post, $tag) {
                return $event->relation == 'posts' && $event->tag->is($tag) && $event->postId == $post->id;
            }
        );
        Event::assertDispatched(
            MorphedByManyAttached::class,
            function ($event) use ($post, $tag) {
                return $event->relation == 'posts' && $event->tag->is($tag) && $event->postId == $post->id;
            }
        );
    }

    /** @test */
    public function it_fires_morphedByManyAttaching_and_morphedByManyAttached_when_saved()
    {
        Event::fake();

        $tag = Tag::create();
        $post = $tag->posts()->save(new Post);

        Event::assertDispatched(
            MorphedByManyAttaching::class,
            function ($event) use ($post, $tag) {
                return $event->relation == 'posts' && $event->tag->is($tag) && $event->postId == $post->id;
            }
        );
        Event::assertDispatched(
            MorphedByManyAttached::class,
            function ($event) use ($post, $tag) {
                return $event->relation == 'posts' && $event->tag->is($tag) && $event->postId == $post->id;
            }
        );
    }

    /** @test */
    public function it_fires_morphedByManyAttaching_and_morphedByManyAttached_when_attached()
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $attributes = [
            'created_at' => now(),
        ];
        $tag->posts()->attach($post, $attributes);

        Event::assertDispatched(
            MorphedByManyAttaching::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'posts' && $event->tag->is($tag) && $event->postId == $post->id && $event->attributes == $attributes;
            }
        );
        Event::assertDispatched(
            MorphedByManyAttached::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'posts' && $event->tag->is($tag) && $event->postId == $post->id && $event->attributes == $attributes;
            }
        );
    }

    /** @test */
    public function it_fires_morphedByManyDetaching_and_morphedByManyDetached_when_detached()
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $tag->posts()->attach($post);
        $tag->posts()->detach($post);

        Event::assertDispatched(
            MorphedByManyDetaching::class,
            function ($event) use ($post, $tag) {
                return $event->relation == 'posts' && $event->tag->is($tag) && $event->postId == $post->id;
            }
        );
        Event::assertDispatched(
            MorphedByManyDetached::class,
            function ($event) use ($post, $tag) {
                return $event->relation == 'posts' && $event->tag->is($tag) && $event->postId == $post->id;
            }
        );
    }

    /** @test */
    public function it_fires_morphedByManySyncing_and_morphedByManySynced()
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $attributes = [
            'created_at' => now(),
        ];
        $tag->posts()->sync([
            $post->id => $attributes,
        ]);

        Event::assertDispatched(
            MorphedByManySyncing::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'posts' && $event->tag->is($tag) && $event->postId == $post->id && $event->attributes[$tag->id] == $attributes;
            }
        );
        Event::assertDispatched(
            MorphedByManySynced::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'posts' && $event->tag->is($tag) && $event->postId == $post->id && $event->attributes[$tag->id] == $attributes;
            }
        );
    }

    /** @test */
    public function it_fires_morphedByManyToggling_and_morphedByManyToggled()
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $attributes = [
            'created_at' => now(),
        ];
        $tag->posts()->toggle([
            $post->id => $attributes,
        ]);

        Event::assertDispatched(
            MorphedByManyToggling::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'posts' && $event->tag->is($tag) && $event->postId == $post->id && $event->attributes[$tag->id] == $attributes;
            }
        );
        Event::assertDispatched(
            MorphedByManyToggled::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'posts' && $event->tag->is($tag) && $event->postId == $post->id && $event->attributes[$tag->id] == $attributes;
            }
        );
    }

    /** @test */
    public function it_fires_morphedByManyUpdatingExistingPivot_and_morphedByManyUpdatedExistingPivot()
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $attributes = [
            'created_at' => now(),
        ];
        $tag->posts()->attach($post);
        $tag->posts()->updateExistingPivot(1, $attributes);

        Event::assertDispatched(
            MorphedByManyUpdatingExistingPivot::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'posts' && $event->tag->is($tag) && $event->postId == $post->id && $event->attributes == $attributes;
            }
        );
        Event::assertDispatched(
            MorphedByManyUpdatedExistingPivot::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'posts' && $event->tag->is($tag) && $event->postId == $post->id && $event->attributes == $attributes;
            }
        );
    }
}
