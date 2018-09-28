<?php

namespace Chelout\RelationshipEvents\Tests\Feature\DispatchesEvents;

use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphMany\MorphManyCreated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphMany\MorphManyCreating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphMany\MorphManySaved;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphMany\MorphManySaving;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphMany\MorphManyUpdated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphMany\MorphManyUpdating;
use Chelout\RelationshipEvents\Tests\Stubs\Models\Comment;
use Chelout\RelationshipEvents\Tests\Stubs\Models\Post;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasMorphManyEventsViaDispatchesEventsTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        Post::setupTable();
        Comment::setupTable();
    }

    /** @test */
    public function it_fires_morphManyCreating_and_morphManyCreated_when_belonged_model_with_morph_many_created_via_dispatchesEvents()
    {
        Event::fake();

        $post = Post::create(['user_id' => 1]);
        $comment = $post->comments()->create([]);

        Event::assertDispatched(
            MorphManyCreating::class,
            function ($event) use ($post, $comment) {
                return $event->post->is($post) && $event->comment->is($comment);
            }
        );
        Event::assertDispatched(
            MorphManyCreated::class,
            function ($event) use ($post, $comment) {
                return $event->post->is($post) && $event->comment->is($comment);
            }
        );
    }

    /** @test */
    public function it_fires_morphManySaving_and_morphManySaved_when_belonged_model_with_morph_many_saved_via_dispatchesEvents()
    {
        Event::fake();

        $post = Post::create(['user_id' => 1]);
        $comment = $post->comments()->save(new Comment);

        Event::assertDispatched(
            MorphManySaving::class,
            function ($event) use ($post, $comment) {
                return $event->post->is($post) && $event->comment->is($comment);
            }
        );
        Event::assertDispatched(
            MorphManySaved::class,
            function ($event) use ($post, $comment) {
                return $event->post->is($post) && $event->comment->is($comment);
            }
        );
    }

    /** @test */
    public function it_fires_morphManyUpdating_and_morphManyUpdated_when_belonged_model_with_morph_many_updated_via_dispatchesEvents()
    {
        Event::fake();

        $post = Post::create(['user_id' => 1]);
        $comment = $post->comments()->save(new Comment);
        $post->comments()->update([]);

        Event::assertDispatched(
            MorphManyUpdating::class,
            function ($event) use ($post, $comment) {
                return $event->post->is($post);
            }
        );
        Event::assertDispatched(
            MorphManyUpdated::class,
            function ($event) use ($post, $comment) {
                return $event->post->is($post) && $event->comment[0]->is($comment);
            }
        );
    }
}
