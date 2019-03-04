<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use Chelout\RelationshipEvents\Tests\Stubs\Comment;
use Chelout\RelationshipEvents\Tests\Stubs\Post;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasMorphManyEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        Post::setupTable();
        Comment::setupTable();
    }

    /** @test */
    public function it_fires_morphManyCreating_and_morphManyCreated_when_belonged_model_with_morph_many_created()
    {
        Event::fake();

        $post = Post::create(['user_id' => 1]);
        $comment = $post->comments()->create([]);

        Event::assertDispatched(
            'eloquent.morphManyCreating: ' . Post::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0]->is($post) && $callback[1]->is($comment);
            }
        );
        Event::assertDispatched(
            'eloquent.morphManyCreated: ' . Post::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0]->is($post) && $callback[1]->is($comment);
            }
        );
    }

    /** @test */
    public function it_fires_morphManySaving_and_morphManySaved_when_belonged_model_with_morph_many_saved()
    {
        Event::fake();

        $post = Post::create(['user_id' => 1]);
        $comment = $post->comments()->save(new Comment);

        Event::assertDispatched(
            'eloquent.morphManySaving: ' . Post::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0]->is($post) && $callback[1]->is($comment);
            }
        );
        Event::assertDispatched(
            'eloquent.morphManySaved: ' . Post::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0]->is($post) && $callback[1]->is($comment);
            }
        );
    }

    /** @test */
    public function it_fires_morphManyUpdating_and_morphManyUpdated_when_belonged_model_with_morph_many_updated()
    {
        Event::fake();

        $post = Post::create(['user_id' => 1]);
        $comment = $post->comments()->save(new Comment);
        $post->comments()->update([]);

        Event::assertDispatched(
            'eloquent.morphManyUpdating: ' . Post::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0]->is($post);
            }
        );
        Event::assertDispatched(
            'eloquent.morphManyUpdated: ' . Post::class,
            function ($event, $callback) use ($post, $comment) {
                return $callback[0]->is($post) && $callback[1][0]->is($comment);
            }
        );
    }
}
