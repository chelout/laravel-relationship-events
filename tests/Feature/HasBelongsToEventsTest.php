<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Chelout\RelationshipEvents\Tests\TestCase;
use Chelout\RelationshipEvents\Tests\Stubs\User;
use Chelout\RelationshipEvents\Tests\Stubs\Profile;

class HasBelongsToEventsTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        User::setupTable();
        Profile::setupTable();
    }

    /** @test */
    public function it_fires_belongsToAssociating_and_belongsToAssociated_when_a_model_associated()
    {
        Event::fake();
        
        Profile::create()->user()->associate(
            User::create()
        );

        Event::assertDispatched('eloquent.belongsToAssociating: ' . Profile::class);
        Event::assertDispatched('eloquent.belongsToAssociated: ' . Profile::class);
    }

    /** @test */
    public function it_fires_belongsToDissociating_and_belongsToDissociated_when_a_model_deassociated()
    {
        Event::fake();
        
        $profile = Profile::create();
        $profile->user()->associate(User::create());
        $profile->user()->dissociate();

        Event::assertDispatched('eloquent.belongsToDissociating: ' . Profile::class);
        Event::assertDispatched('eloquent.belongsToDissociated: ' . Profile::class);
    }

    /** @test */
    public function it_fires_belongsToUpdating_and_belongsToUpdated_when_a_model_deassociated()
    {
        Event::fake();
        
        $user = User::create();
        $profile = Profile::create();
        $profile->user()->associate($user);
        $profile->user()->update([]);

        Event::assertDispatched('eloquent.belongsToUpdating: ' . Profile::class);
        Event::assertDispatched('eloquent.belongsToUpdated: ' . Profile::class);
    }
}
