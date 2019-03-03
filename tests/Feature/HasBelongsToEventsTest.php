<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use Chelout\RelationshipEvents\Tests\Stubs\Profile;
use Chelout\RelationshipEvents\Tests\Stubs\User;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasBelongsToEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        User::setupTable();
        Profile::setupTable();
    }

    /** @test */
    public function it_fires_belongsToAssociating_and_belongsToAssociated_when_a_model_associated()
    {
        Event::fake();

        $profile = Profile::create();
        $profile->user()->associate($user = User::create());

        Event::assertDispatched(
            'eloquent.belongsToAssociating: ' . Profile::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0] == 'user' && $callback[1]->is($profile) && $callback[2]->is($user);
            }
        );
        Event::assertDispatched(
            'eloquent.belongsToAssociated: ' . Profile::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0] == 'user' && $callback[1]->is($profile) && $callback[2]->is($user);
            }
        );
    }

    /** @test */
    public function it_fires_belongsToDissociating_and_belongsToDissociated_when_a_model_dissociated()
    {
        Event::fake();

        $profile = Profile::create();
        $profile->user()->associate($user = User::create());
        $profile->user()->dissociate();

        Event::assertDispatched(
            'eloquent.belongsToDissociating: ' . Profile::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0] == 'user' && $callback[1]->is($profile) && $callback[2]->is($user);
            }
        );
        Event::assertDispatched(
            'eloquent.belongsToDissociated: ' . Profile::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0] == 'user' && $callback[1]->is($profile) && $callback[2]->is($user);
            }
        );
    }

    /** @test */
    public function it_fires_belongsToUpdating_and_belongsToUpdated_when_a_parent_model_updated()
    {
        Event::fake();

        $user = User::create();
        $profile = Profile::create();
        $profile->user()->associate($user);
        $profile->user()->update([]);

        Event::assertDispatched(
            'eloquent.belongsToUpdating: ' . Profile::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0] == 'user' && $callback[1]->is($profile) && $callback[2]->is($user);
            }
        );
        Event::assertDispatched(
            'eloquent.belongsToUpdated: ' . Profile::class,
            function ($event, $callback) use ($user, $profile) {
                return $callback[0] == 'user' && $callback[1]->is($profile) && $callback[2]->is($user);
            }
        );
    }
}
