<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Chelout\RelationshipEvents\Tests\TestCase;
use Chelout\RelationshipEvents\Tests\Stubs\User;
use Chelout\RelationshipEvents\Tests\Stubs\Profile;

class HasOneEventsTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        User::setupTable();
        Profile::setupTable();
    }

    /** @test */
    public function it_fires_hasOneCreating_and_hasOneCreated_when_a_belonged_model_created()
    {
        Event::fake();
        
        User::create()
            ->profile()
            ->create([]);

        Event::assertDispatched('eloquent.hasOneCreating: ' . User::class);
        Event::assertDispatched('eloquent.hasOneCreated: ' . User::class);
    }

    /** @test */
    public function it_fires_hasOneSaving_and_hasOneSaved_when_a_belonged_model_saved()
    {
        Event::fake();

        User::create()
            ->profile()
            ->save(new Profile);

        Event::assertDispatched('eloquent.hasOneSaving: ' . User::class);
        Event::assertDispatched('eloquent.hasOneSaved: ' . User::class);
    }

    /** @test */
    public function it_fires_hasOneUpdating_and_hasOneUpdated_when_a_belonged_model_Updated()
    {
        Event::fake();
        
        $user = User::create();
        $profile = $user->profile()->save(new Profile);        
        $user->profile()->update([]);

        Event::assertDispatched('eloquent.hasOneUpdating: ' . User::class);
        Event::assertDispatched('eloquent.hasOneUpdated: ' . User::class);
    }
}
