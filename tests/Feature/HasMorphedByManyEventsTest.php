<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Chelout\RelationshipEvents\Tests\TestCase;
use Chelout\RelationshipEvents\Tests\Stubs\Tag;
use Chelout\RelationshipEvents\Tests\Stubs\Post;

class HasMorphedByManyEventsTest extends TestCase
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

        $tag  = Tag::create();
        $post = $tag->posts()->create([]);

        Event::assertDispatched(
            'eloquent.morphedByManyAttaching: ' . Tag::class, 
            function ($e, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphedByManyAttached: ' . Tag::class, 
            function ($e, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
    }

    /** @test */
    public function it_fires_morphedByManyAttaching_and_morphedByManyAttached_when_saved()
    {
        Event::fake();

        $tag  = Tag::create();
        $post = $tag->posts()->save(new Post);

        Event::assertDispatched(
            'eloquent.morphedByManyAttaching: ' . Tag::class, 
            function ($e, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphedByManyAttached: ' . Tag::class, 
            function ($e, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
    }
    
    /** @test */
    public function it_fires_morphedByManyAttaching_and_morphedByManyAttached_when_attached()
    {
        Event::fake();

        $post = Post::create();
        $tag  = Tag::create();
        $tag->posts()->attach($post);

        Event::assertDispatched(
            'eloquent.morphedByManyAttaching: ' . Tag::class, 
            function ($e, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphedByManyAttached: ' . Tag::class, 
            function ($e, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
    }

    /** @test */
    public function it_fires_morphedByManyDetaching_and_morphedByManyDetached_when_detached()
    {
        Event::fake();

        $post = Post::create();
        $tag  = Tag::create();
        $tag->posts()->attach($post);
        $tag->posts()->detach($post);

        Event::assertDispatched(
            'eloquent.morphedByManyDetaching: ' . Tag::class, 
            function ($e, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphedByManyDetached: ' . Tag::class, 
            function ($e, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
    }

    /** @test */
    public function it_fires_morphedByManySyncing_and_morphedByManySynced()
    {
        Event::fake();

        $post = Post::create();
        $tag  = Tag::create();
        $tag->posts()->sync($post);

        Event::assertDispatched(
            'eloquent.morphedByManySyncing: ' . Tag::class, 
            function ($e, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphedByManySynced: ' . Tag::class, 
            function ($e, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
    }

    /** @test */
    public function it_fires_morphedByManyToggling_and_morphedByManyToggled()
    {
        Event::fake();

        $post = Post::create();
        $tag  = Tag::create();
        $tag->posts()->toggle($post);

        Event::assertDispatched(
            'eloquent.morphedByManyToggling: ' . Tag::class, 
            function ($e, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphedByManyToggled: ' . Tag::class, 
            function ($e, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
    }

    /** @test */
    public function it_fires_morphedByManyUpdatingExistingPivot_and_morphedByManyUpdatedExistingPivot()
    {
        Event::fake();

        $post = Post::create();
        $tag  = Tag::create();
        $tag->posts()->attach($post);
        $tag->posts()->updateExistingPivot(1, ['created_at' => now()]);

        Event::assertDispatched(
            'eloquent.morphedByManyUpdatingExistingPivot: ' . Tag::class, 
            function ($e, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphedByManyUpdatedExistingPivot: ' . Tag::class, 
            function ($e, $callback) use ($post, $tag) {
                return $callback[0] == 'posts' && $callback[1]->is($tag) && $callback[2][0] == $post->id;
            }
        );
    }
}
