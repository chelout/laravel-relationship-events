<?php

namespace Chelout\RelationshipEvents\Tests\Feature\DispatchesEvents;

use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphOne\MorphOneCreated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphOne\MorphOneCreating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphOne\MorphOneSaved;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphOne\MorphOneSaving;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphOne\MorphOneUpdated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphOne\MorphOneUpdating;
use Chelout\RelationshipEvents\Tests\Stubs\Models\Address;
use Chelout\RelationshipEvents\Tests\Stubs\Models\User;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasMorphOneEventsViaDispatchesEventsTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        User::setupTable();
        Address::setupTable();
    }

    /** @test */
    public function it_fires_morphOneCreating_and_morphOneCreated_when_belonged_model_with_morph_one_created_via_dispatchesEvents()
    {
        Event::fake();

        $user = User::create();
        $address = $user->address()->create([]);

        Event::assertDispatched(
            MorphOneCreating::class,
            function ($event) use ($user, $address) {
                return $event->user->is($user) && $event->address->is($address);
            }
        );
        Event::assertDispatched(
            MorphOneCreated::class,
            function ($event) use ($user, $address) {
                return $event->user->is($user) && $event->address->is($address);
            }
        );
    }

    /** @test */
    public function it_fires_morphOneSaving_and_morphOneSaved_when_belonged_model_with_morph_one_saved_via_dispatchesEvents()
    {
        Event::fake();

        $user = User::create();
        $address = $user->address()->save(new Address);

        Event::assertDispatched(
            MorphOneSaving::class,
            function ($event) use ($user, $address) {
                return $event->user->is($user) && $event->address->is($address);
            }
        );
        Event::assertDispatched(
            MorphOneSaved::class,
            function ($event) use ($user, $address) {
                return $event->user->is($user) && $event->address->is($address);
            }
        );
    }

    /** @test */
    public function it_fires_morphOneUpdating_and_morphOneUpdated_when_belonged_model_with_morph_one_updated_via_dispatchesEvents()
    {
        Event::fake();

        $user = User::create();
        $address = $user->address()->save(new Address);
        $user->address()->update([]);

        Event::assertDispatched(
            MorphOneUpdating::class,
            function ($event) use ($user, $address) {
                return $event->user->is($user) && $event->address->is($address);
            }
        );
        Event::assertDispatched(
            MorphOneUpdated::class,
            function ($event) use ($user, $address) {
                return $event->user->is($user) && $event->address->is($address);
            }
        );
    }
}
