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

        // given we have user and profile with hasOne relation
        // once the profile created
        User::create()
            ->profile()
            ->create([]);

        // hasOneCreating and hasOneCreated should be fired.
        Event::assertDispatched('eloquent.hasOneCreating: ' . User::class);
        Event::assertDispatched('eloquent.hasOneCreated: ' . User::class);
    }
}
