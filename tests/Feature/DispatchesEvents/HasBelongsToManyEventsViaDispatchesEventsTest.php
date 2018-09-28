<?php

namespace Chelout\RelationshipEvents\Tests\Feature\DispatchesEvents;

use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyAttached;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyAttaching;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyDetached;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyDetaching;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManySynced;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManySyncing;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyToggled;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyToggling;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyUpdatedExistingPivot;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyUpdatingExistingPivot;
use Chelout\RelationshipEvents\Tests\Stubs\Models\Role;
use Chelout\RelationshipEvents\Tests\Stubs\Models\User;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasBelongsToManyEventsViaDispatchesEventsTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        User::setupTable();
        Role::setupTable();
    }

    /** @test */
    public function it_fires_belongsToManyAttaching_and_belongsToManyAttached_when_a_model_attached_via_dispatchesEvents()
    {
        Event::fake();

        $user = User::create();
        $role = Role::create(['name' => 'admin']);
        $attributes = [
            'note' => 'bla bla',
        ];
        $user->roles()->attach($role, $attributes);

        Event::assertDispatched(
            BelongsToManyAttaching::class,
            function ($event) use ($user, $role, $attributes) {
                return $event->relation == 'roles' && $event->user->is($user) && $event->roleId == $role->id && $event->attributes == $attributes;
            }
        );
        Event::assertDispatched(
            BelongsToManyAttached::class,
            function ($event) use ($user, $role, $attributes) {
                return $event->relation == 'roles' && $event->user->is($user) && $event->roleId == $role->id && $event->attributes == $attributes;
            }
        );
    }

    /** @test */
    public function it_fires_belongsToManyDetaching_and_belongsToManyDetached_when_a_model_detached_via_dispatchesEvents()
    {
        Event::fake();

        $user = User::create();
        $role = Role::create(['name' => 'admin']);
        $user->roles()->attach($role);
        $user->roles()->detach($role);

        Event::assertDispatched(
            BelongsToManyDetaching::class,
            function ($event) use ($user, $role) {
                return $event->relation == 'roles' && $event->user->is($user) && $event->roleId == $role->id;
            }
        );
        Event::assertDispatched(
            BelongsToManyDetached::class,
            function ($event) use ($user, $role) {
                return $event->relation == 'roles' && $event->user->is($user) && $event->roleId == $role->id;
            }
        );
    }

    /** @test */
    public function it_fires_belongsToManySyncing_and_belongsToManySynced_when_a_model_synced_via_dispatchesEvents()
    {
        Event::fake();

        $user = User::create();
        $role = Role::create(['name' => 'admin']);
        $attributes = [
            'note' => 'bla bla',
        ];
        $user->roles()->sync([
            $role->id => $attributes,
        ]);

        Event::assertDispatched(
            BelongsToManySyncing::class,
            function ($event) use ($user, $role, $attributes) {
                return $event->relation == 'roles' && $event->user->is($user) && $event->roleId == $role->id && $event->attributes[$role->id] == $attributes;
            }
        );
        Event::assertDispatched(
            BelongsToManySynced::class,
            function ($event) use ($user, $role, $attributes) {
                return $event->relation == 'roles' && $event->user->is($user) && $event->roleId == $role->id && $event->attributes[$role->id] == $attributes;
            }
        );
    }

    /** @test */
    public function it_fires_belongsToManyToggling_and_belongsToManyToggled_when_a_model_toggled_via_dispatchesEvents()
    {
        Event::fake();

        $user = User::create();
        $role = Role::create(['name' => 'role']);
        $attributes = [
            'note' => 'bla bla',
        ];
        $user->roles()->toggle([$role->id => $attributes]);

        Event::assertDispatched(
            BelongsToManyToggling::class,
            function ($event) use ($user, $role, $attributes) {
                return $event->relation == 'roles' && $event->user->is($user) && $event->roleId == $role->id && $event->attributes[$role->id] == $attributes;
            }
        );
        Event::assertDispatched(
            BelongsToManyToggled::class,
            function ($event) use ($user, $role, $attributes) {
                return $event->relation == 'roles' && $event->user->is($user) && $event->roleId == $role->id && $event->attributes[$role->id] == $attributes;
            }
        );
    }

    /** @test */
    public function it_fires_belongsToManyUpdatingExistingPivot_and_belongsToManyUpdatedExistingPivot_when_updaing_pivot_table_via_dispatchesEvents()
    {
        Event::fake();

        $user = User::create();
        $role = Role::create(['name' => 'admin']);
        $attributes = [
            'note' => 'bla bla',
        ];
        $user->roles()->attach($role);
        $user->roles()->updateExistingPivot(1, $attributes);

        Event::assertDispatched(
            BelongsToManyUpdatingExistingPivot::class,
            function ($event) use ($user, $role, $attributes) {
                return $event->relation == 'roles' && $event->user->is($user) && $event->roleId == $role->id && $event->attributes == $attributes;
            }
        );
        Event::assertDispatched(
            BelongsToManyUpdatedExistingPivot::class,
            function ($event) use ($user, $role, $attributes) {
                return $event->relation == 'roles' && $event->user->is($user) && $event->roleId == $role->id && $event->attributes == $attributes;
            }
        );
    }
}
