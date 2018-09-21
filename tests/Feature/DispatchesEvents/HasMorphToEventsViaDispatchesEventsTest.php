<?php

namespace Chelout\RelationshipEvents\Tests\Feature\DispatchesEvents;

use Chelout\RelationshipEvents\Tests\Stubs\Comment;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphTo\MorphToAssociated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphTo\MorphToAssociating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphTo\MorphToDissociated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphTo\MorphToDissociating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphTo\MorphToUpdated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphTo\MorphToUpdating;
use Chelout\RelationshipEvents\Tests\Stubs\Post;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasMorphToEventsViaDispatchesEventsTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        Post::setupTable();
        Comment::setupTable();
    }

    /** @test */
    public function it_fires_morphToAssociating_and_morphToAssociated_via_dispatchesEvents_via_dispatchesEvents()
    {
        Event::fake();

        $post = Post::create();
        $comment = Comment::create();
        $comment->post()->associate($post);

        Event::assertDispatched(
            MorphToAssociating::class,
            function ($event) use ($post, $comment) {
                return $event->relation == Post::class && $comment->is($comment) && $post->is($post);
            }
        );
        Event::assertDispatched(
            MorphToAssociated::class,
            function ($event) use ($post, $comment) {
                return $event->relation == Post::class && $comment->is($comment) && $post->is($post);
            }
        );
    }

    /** @test */
    public function it_fires_morphToDissociating_and_morphToDissociated_via_dispatchesEvents_via_dispatchesEvents()
    {
        Event::fake();

        $post = Post::create();
        $comment = Comment::create();
        $comment->post()->associate($post);
        $comment->post()->dissociate($post);

        Event::assertDispatched(
            MorphToDissociating::class,
            function ($event) use ($post, $comment) {
                return $event->relation == Post::class && $comment->is($comment) && $post->is($post);
            }
        );
        Event::assertDispatched(
            MorphToDissociated::class,
            function ($event) use ($post, $comment) {
                return $event->relation == Post::class && $comment->is($comment) && $post->is($post);
            }
        );
    }

    /** @test */
    public function it_fires_morphToUpdating_and_morphToUpdated_via_dispatchesEvents_via_dispatchesEvents()
    {
        Event::fake();

        $post = Post::create();
        $comment = Comment::create();
        $comment->post()->associate($post);
        $comment->post()->update([]);

        Event::assertDispatched(
            MorphToUpdating::class,
            function ($event) use ($post, $comment) {
                return $event->relation == Post::class && $comment->is($comment) && $post->is($post);
            }
        );
        Event::assertDispatched(
            MorphToUpdated::class,
            function ($event) use ($post, $comment) {
                return $event->relation == Post::class && $comment->is($comment) && $post->is($post);
            }
        );
    }
}
