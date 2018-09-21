<?php

namespace Chelout\RelationshipEvents\Tests\Feature\DispatchesEvents;

use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyAttached;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyAttaching;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyDetached;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyDetaching;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManySynced;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManySyncing;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyToggled;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyToggling;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyUpdatedExistingPivot;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyUpdatingExistingPivot;
use Chelout\RelationshipEvents\Tests\Stubs\Post;
use Chelout\RelationshipEvents\Tests\Stubs\Tag;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasMorphToManyEventsViaDispatchesEventsTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        Post::setupTable();
        Tag::setupTable();
    }

    /** @test */
    public function it_fires_morphToManyAttaching_and_morphToManyAttached_via_dispatchesEvents()
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $attributes = [
            'created_at' => now(),
        ];
        $post->tags()->attach($tag, $attributes);

        Event::assertDispatched(
            MorphToManyAttaching::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'tags' && $event->post->is($post) && $event->tagId == $tag->id && $event->attributes == $attributes;
            }
        );
        Event::assertDispatched(
            MorphToManyAttached::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'tags' && $event->post->is($post) && $event->tagId == $tag->id && $event->attributes == $attributes;
            }
        );
    }

    /** @test */
    public function it_fires_morphToManyDetaching_and_morphToManyDetached_via_dispatchesEvents()
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $post->tags()->attach($tag);
        $post->tags()->detach($tag);

        Event::assertDispatched(
            MorphToManyDetaching::class,
            function ($event) use ($post, $tag) {
                return $event->relation == 'tags' && $event->post->is($post) && $event->tagId == $tag->id;
            }
        );
        Event::assertDispatched(
            MorphToManyDetached::class,
            function ($event) use ($post, $tag) {
                return $event->relation == 'tags' && $event->post->is($post) && $event->tagId == $tag->id;
            }
        );
    }

    /** @test */
    public function it_fires_morphToManySyncing_and_morphToManySynced_via_dispatchesEvents()
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $attributes = [
            'created_at' => now(),
        ];
        $post->tags()->sync([
            $tag->id => $attributes,
        ]);

        Event::assertDispatched(
            MorphToManySyncing::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'tags' && $event->post->is($post) && $event->tagId == $tag->id && $event->attributes[$tag->id] == $attributes;
            }
        );
        Event::assertDispatched(
            MorphToManySynced::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'tags' && $event->post->is($post) && $event->tagId == $tag->id && $event->attributes[$tag->id] == $attributes;
            }
        );
    }

    /** @test */
    public function it_fires_morphToManyToggling_and_morphToManyToggled_via_dispatchesEvents()
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $attributes = [
            'created_at' => now(),
        ];
        $post->tags()->toggle([
            $tag->id => $attributes,
        ]);

        Event::assertDispatched(
            MorphToManyToggling::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'tags' && $event->post->is($post) && $event->tagId == $tag->id && $event->attributes[$tag->id] == $attributes;
            }
        );
        Event::assertDispatched(
            MorphToManyToggled::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'tags' && $event->post->is($post) && $event->tagId == $tag->id && $event->attributes[$tag->id] == $attributes;
            }
        );
    }

    /** @test */
    public function it_fires_morphToManyUpdatingExistingPivot_and_morphToManyUpdatedExistingPivot_via_dispatchesEvents()
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $attributes = [
            'created_at' => now(),
        ];
        $post->tags()->sync($tag);
        $post->tags()->updateExistingPivot(1, $attributes);

        Event::assertDispatched(
            MorphToManyUpdatingExistingPivot::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'tags' && $event->post->is($post) && $event->tagId == $tag->id && $event->attributes == $attributes;
            }
        );
        Event::assertDispatched(
            MorphToManyUpdatedExistingPivot::class,
            function ($event) use ($post, $tag, $attributes) {
                return $event->relation == 'tags' && $event->post->is($post) && $event->tagId == $tag->id && $event->attributes == $attributes;
            }
        );
    }
}
