<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsTo\BelongsToAssociated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsTo\BelongsToAssociating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsTo\BelongsToDissociated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsTo\BelongsToDissociating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsTo\BelongsToUpdated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsTo\BelongsToUpdating;
use Chelout\RelationshipEvents\Tests\Stubs\Models\Profile;
use Chelout\RelationshipEvents\Tests\Stubs\Models\User;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasBelongsToEventsViaDispatchesEventsTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        User::setupTable();
        Profile::setupTable();
    }

    /** @test */
    public function it_fires_belongsToAssociating_and_belongsToAssociated_when_a_model_associated_via_dispatchesEvents()
    {
        Event::fake();

        $profile = Profile::create();
        $profile->user()->associate($user = User::create());

        Event::assertDispatched(
            BelongsToAssociating::class,
            function ($event) use ($user, $profile) {
                return $event->relation == 'user' && $event->user->is($user) && $event->profile->is($profile);
            }
        );
        Event::assertDispatched(
            BelongsToAssociated::class,
            function ($event) use ($user, $profile) {
                return $event->relation == 'user' && $event->user->is($user) && $event->profile->is($profile);
            }
        );
    }

    /** @test */
    public function it_fires_belongsToDissociating_and_belongsToDissociated_when_a_model_dissociated_via_dispatchesEvents()
    {
        Event::fake();

        $profile = Profile::create();
        $profile->user()->associate($user = User::create());
        $profile->user()->dissociate();

        Event::assertDispatched(
            BelongsToDissociating::class,
            function ($event) use ($user, $profile) {
                return $event->relation == 'user' && $event->user->is($user) && $event->profile->is($profile);
            }
        );
        Event::assertDispatched(
            BelongsToDissociated::class,
            function ($event) use ($user, $profile) {
                return $event->relation == 'user' && $event->user->is($user) && $event->profile->is($profile);
            }
        );
    }

    /** @test */
    public function it_fires_belongsToUpdating_and_belongsToUpdated_when_a_parent_model_updated_via_dispatchesEvents()
    {
        Event::fake();

        $user = User::create();
        $profile = Profile::create();
        $profile->user()->associate($user);
        $profile->user()->update([]);

        Event::assertDispatched(
            BelongsToUpdating::class,
            function ($event) use ($user, $profile) {
                return $event->relation == 'user' && $event->user->is($user) && $event->profile->is($profile);
            }
        );
        Event::assertDispatched(
            BelongsToUpdated::class,
            function ($event) use ($user, $profile) {
                return $event->relation == 'user' && $event->user->is($user) && $event->profile->is($profile);
            }
        );
    }
}
