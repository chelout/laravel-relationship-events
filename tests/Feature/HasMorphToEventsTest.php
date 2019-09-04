<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use Chelout\RelationshipEvents\Tests\Stubs\Comment;
use Chelout\RelationshipEvents\Tests\Stubs\Post;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasMorphToEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        Post::setupTable();
        Comment::setupTable();
    }

    /** @test */
    public function it_fires_morphToAssociating_and_morphToAssociated()
    {
        Event::fake();

        $post = Post::create();
        $comment = Comment::create();
        $comment->post()->associate($post);

        Event::assertDispatched(
            'eloquent.morphToAssociating: ' . Comment::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0] == 'Chelout\RelationshipEvents\Tests\Stubs\Post' && $callback[1]->is($comment) && $callback[2]->is($post);
            }
        );
        Event::assertDispatched(
            'eloquent.morphToAssociated: ' . Comment::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0] == 'Chelout\RelationshipEvents\Tests\Stubs\Post' && $callback[1]->is($comment) && $callback[2]->is($post);
            }
        );
    }

    /** @test */
    public function it_fires_morphToDissociating_and_morphToDissociated()
    {
        Event::fake();

        $post = Post::create();
        $comment = Comment::create();
        $comment->post()->associate($post);
        $comment->post()->dissociate($post);

        Event::assertDispatched(
            'eloquent.morphToDissociating: ' . Comment::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0] == 'Chelout\RelationshipEvents\Tests\Stubs\Post' && $callback[1]->is($comment) && $callback[2]->is($post);
            }
        );
        Event::assertDispatched(
            'eloquent.morphToDissociated: ' . Comment::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0] == 'Chelout\RelationshipEvents\Tests\Stubs\Post' && $callback[1]->is($comment) && $callback[2]->is($post);
            }
        );
    }

    /** @test */
    public function it_fires_morphToUpdating_and_morphToUpdated()
    {
        Event::fake();

        $post = Post::create();
        $comment = Comment::create();
        $comment->post()->associate($post);
        $comment->post()->update([]);

        Event::assertDispatched(
            'eloquent.morphToUpdating: ' . Comment::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0] == 'Chelout\RelationshipEvents\Tests\Stubs\Post' && $callback[1]->is($comment) && $callback[2]->is($post);
            }
        );
        Event::assertDispatched(
            'eloquent.morphToUpdated: ' . Comment::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0] == 'Chelout\RelationshipEvents\Tests\Stubs\Post' && $callback[1]->is($comment) && $callback[2]->is($post);
            }
        );
    }
}
