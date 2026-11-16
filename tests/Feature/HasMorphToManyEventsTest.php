<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Chelout\RelationshipEvents\Tests\Stubs\Post;
use Chelout\RelationshipEvents\Tests\Stubs\Tag;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

final class HasMorphToManyEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        Post::setupTable();
        Tag::setupTable();
    }

    #[Test]
    public function it_fires_morphToManyAttaching_and_morphToManyAttached(): void
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

    #[Test]
    public function it_fires_morphToManyDetaching_and_morphToManyDetached(): void
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

    #[Test]
    public function it_fires_morphToManySyncing_and_morphToManySynced(): void
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

    #[Test]
    public function it_fires_morphToManyToggling_and_morphToManyToggled(): void
    {
        Event::fake();

        $post = Post::create();
        $tag = Tag::create();
        $post->tags()->toggle($tag);

        Event::assertDispatched(
            'eloquent.morphToManyToggling: ' . Post::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'tags' && $callback[1]->is($post) && $callback[2][0] == $tag->id;
            }
        );
        Event::assertDispatched(
            'eloquent.morphToManyToggled: ' . Post::class,
            function ($event, $callback) use ($post, $tag) {
                return $callback[0] == 'tags' && $callback[1]->is($post) && $callback[2][0] == $tag->id;
            }
        );
    }

    #[Test]
    public function it_fires_morphToManyUpdatingExistingPivot_and_morphToManyUpdatedExistingPivot(): void
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

    #[Test]
    public function it_detaches_through_a_relationship_helper_requiring_arguments(): void
    {
        $parent = Post::create();
        $related = Tag::create();
        $other = Tag::create();
        $parent->tags()->attach([$related->id, $other->id]);

        Event::fake();

        $this->assertSame(1, $parent->tagsFor($related->id)->detach());
        $this->assertSame([$other->id], $parent->tags()->allRelatedIds()->all());

        foreach (['morphToManyDetaching', 'morphToManyDetached'] as $event) {
            Event::assertDispatched('eloquent.' . $event . ': ' . Post::class, function ($event, $payload) use ($parent, $related) {
                return $payload[1]->is($parent) && $payload[2] === [$related->id];
            });
        }
    }

    #[Test]
    public function it_detaches_current_assignments_when_the_relationship_was_loaded_earlier(): void
    {
        $parent = Post::create();
        $related = Tag::create();
        $other = Tag::create();
        $parent->tags()->attach($related);
        $parent->load('tags');
        $parent->tags()->attach($other);

        Event::fake();

        $this->assertSame(2, $parent->tags()->detach());
        $this->assertSame([], $parent->tags()->allRelatedIds()->all());

        foreach (['morphToManyDetaching', 'morphToManyDetached'] as $event) {
            Event::assertDispatched('eloquent.' . $event . ': ' . Post::class, function ($event, $payload) use ($parent, $related, $other) {
                return $payload[1]->is($parent) && $payload[2] === [$related->id, $other->id];
            });
        }
    }
}
