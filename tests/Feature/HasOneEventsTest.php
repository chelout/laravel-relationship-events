<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Chelout\RelationshipEvents\Tests\Stubs\Profile;
use Chelout\RelationshipEvents\Tests\Stubs\User;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

final class HasOneEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        User::setupTable();
        Profile::setupTable();
    }

    #[Test]
    public function it_fires_hasOneCreating_and_hasOneCreated_when_a_belonged_model_created(): void
    {
        Event::fake();

        $user = User::create();
        $profile = $user->profile()->create([]);

        Event::assertDispatched(
            'eloquent.hasOneCreating: ' . User::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0]->is($user) && $callback[1]->is($profile);
            }
        );
        Event::assertDispatched(
            'eloquent.hasOneCreated: ' . User::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0]->is($user) && $callback[1]->is($profile);
            }
        );
    }

    #[Test]
    public function it_fires_hasOneSaving_and_hasOneSaved_when_a_belonged_model_saved(): void
    {
        Event::fake();

        $user = User::create();
        $profile = $user->profile()->save(new Profile);

        Event::assertDispatched(
            'eloquent.hasOneSaving: ' . User::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0]->is($user) && $callback[1]->is($profile);
            }
        );
        Event::assertDispatched(
            'eloquent.hasOneSaved: ' . User::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0]->is($user) && $callback[1]->is($profile);
            }
        );
    }

    #[Test]
    public function it_fires_hasOneUpdating_and_hasOneUpdated_when_a_belonged_model_updated(): void
    {
        Event::fake();

        $user = User::create();
        $profile = $user->profile()->save(new Profile);
        $user->profile()->update([]);

        Event::assertDispatched(
            'eloquent.hasOneUpdating: ' . User::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0]->is($user) && $callback[1]->is($profile);
            }
        );
        Event::assertDispatched(
            'eloquent.hasOneUpdated: ' . User::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0]->is($user) && $callback[1]->is($profile);
            }
        );
    }
}
