<?php

namespace Chelout\RelationshipEvents\Tests\Feature\DispatchesEvents;

use Chelout\RelationshipEvents\Tests\Stubs\Events\HasOne\HasOneCreated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasOne\HasOneCreating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasOne\HasOneSaved;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasOne\HasOneSaving;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasOne\HasOneUpdated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasOne\HasOneUpdating;
use Chelout\RelationshipEvents\Tests\Stubs\Profile;
use Chelout\RelationshipEvents\Tests\Stubs\User;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasOneEventsViaDispatchesEventsTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        User::setupTable();
        Profile::setupTable();
    }

    /** @test */
    public function it_fires_HasOneCreating_and_HasOneCreated_when_a_belonged_model_created_via_dispatchesEvents()
    {
        Event::fake();

        $user = User::create();
        $profile = $user->profile()->create([]);

        Event::assertDispatched(
            HasOneCreating::class,
            function ($event) use ($user, $profile) {
                return $event->user->is($user) && $event->profile->is($profile);
            }
        );
        Event::assertDispatched(
            HasOneCreated::class,
            function ($event) use ($user, $profile) {
                return $event->user->is($user) && $event->profile->is($profile);
            }
        );
    }

    /** @test */
    public function it_fires_HasOneSaving_and_HasOneSaved_when_a_belonged_model_saved_via_dispatchesEvents()
    {
        Event::fake();

        $user = User::create();
        $profile = $user->profile()->save(new Profile);

        Event::assertDispatched(
            HasOneSaving::class,
            function ($event) use ($user, $profile) {
                return $event->user->is($user) && $event->profile->is($profile);
            }
        );
        Event::assertDispatched(
            HasOneSaved::class,
            function ($event) use ($user, $profile) {
                return $event->user->is($user) && $event->profile->is($profile);
            }
        );
    }

    /** @test */
    public function it_fires_HasOneSaving_and_HasOneSaved_when_a_belonged_model_updated_via_dispatchesEvents()
    {
        Event::fake();

        $user = User::create();
        $profile = $user->profile()->save(new Profile);
        $user->profile()->update([]);

        Event::assertDispatched(
            HasOneUpdating::class,
            function ($event) use ($user, $profile) {
                return $event->user->is($user) && $event->profile->is($profile);
            }
        );
        Event::assertDispatched(
            HasOneUpdated::class,
            function ($event) use ($user, $profile) {
                return $event->user->is($user) && $event->profile->is($profile);
            }
        );
    }
}
