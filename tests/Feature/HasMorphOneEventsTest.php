<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use Illuminate\Support\Facades\Event;
use Chelout\RelationshipEvents\Tests\TestCase;
use Chelout\RelationshipEvents\Tests\Stubs\User;
use Chelout\RelationshipEvents\Tests\Stubs\Profile;
use Chelout\RelationshipEvents\Tests\Stubs\Address;

class HasMorphOneEventsTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        User::setupTable();
        Address::setupTable();
    }

    /** @test */
    public function it_fires_morphOneCreating_and_morphOneCreated_when_belonged_model_with_many_created()
    {
        Event::fake();

        $user = User::create();
        $address = $user->address()->create([]);

        Event::assertDispatched(
            'eloquent.morphOneCreating: ' . User::class,
            function ($event, $callback) use ($user, $address) {
                return $callback[0]->is($user) && $callback[1]->is($address);
            }
        );
        Event::assertDispatched(
            'eloquent.morphOneCreated: ' . User::class,
            function ($event, $callback) use ($user, $address) {
                return $callback[0]->is($user) && $callback[1]->is($address);
            }
        );
    }
}
