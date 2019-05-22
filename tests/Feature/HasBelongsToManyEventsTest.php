<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use Chelout\RelationshipEvents\Tests\Stubs\Role;
use Chelout\RelationshipEvents\Tests\Stubs\User;
use Chelout\RelationshipEvents\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class HasBelongsToManyEventsTest extends TestCase
{
    public function setup(): void
    {
        parent::setup();

        User::setupTable();
        Role::setupTable();
    }

    /** @test */
    public function it_fires_belongsToManyAttaching_and_belongsToManyAttached_when_a_model_attached()
    {
        Event::fake();

        $user = User::create();
        $role = Role::create(['name' => 'admin']);
        $user->roles()->attach($role);

        Event::assertDispatched(
            'eloquent.belongsToManyAttaching: ' . User::class,
            function ($event, $callback) use ($user, $role) {
                return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
            }
        );
        Event::assertDispatched(
            'eloquent.belongsToManyAttached: ' . User::class,
            function ($event, $callback) use ($user, $role) {
                return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
            }
        );
    }

    /** @test */
    public function it_fires_belongsToManyDetaching_and_belongsToManyDetached_when_a_model_detached()
    {
        Event::fake();

        $user = User::create();
        $role = Role::create(['name' => 'admin']);
        $user->roles()->attach($role);
        $user->roles()->detach($role);

        Event::assertDispatched(
            'eloquent.belongsToManyDetaching: ' . User::class,
            function ($event, $callback) use ($user, $role) {
                return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
            }
        );
        Event::assertDispatched(
            'eloquent.belongsToManyDetached: ' . User::class,
            function ($event, $callback) use ($user, $role) {
                return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
            }
        );
    }

    /** @test */
    public function it_fires_belongsToManySyncing_and_belongsToManySynced_when_a_model_synced()
    {
        Event::fake();

        $user = User::create();
        $role = Role::create(['name' => 'admin']);
        $user->roles()->sync($role);

        Event::assertDispatched(
            'eloquent.belongsToManySyncing: ' . User::class,
            function ($event, $callback) use ($user, $role) {
                return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
            }
        );
        Event::assertDispatched(
            'eloquent.belongsToManySynced: ' . User::class,
            function ($event, $callback) use ($user, $role) {
                return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
            }
        );
    }

    /** @test */
    public function it_fires_belongsToManyToggling_and_belongsToManyToggled_when_a_model_toggled()
    {
        Event::fake();

        $user = User::create();
        $role = Role::create(['name' => 'admin']);
        $user->roles()->toggle($role);

        Event::assertDispatched(
            'eloquent.belongsToManyToggling: ' . User::class,
            function ($event, $callback) use ($user, $role) {
                return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
            }
        );
        Event::assertDispatched(
            'eloquent.belongsToManyToggled: ' . User::class,
            function ($event, $callback) use ($user, $role) {
                return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
            }
        );
    }

    /** @test */
    public function it_fires_belongsToManyUpdatingExistingPivot_and_belongsToManyUpdatedExistingPivot_when_updaing_pivot_table()
    {
        Event::fake();

        $user = User::create();
        $role = Role::create(['name' => 'admin']);
        $user->roles()->attach($role);
        $user->roles()->updateExistingPivot(1, ['note' => 'bla bla']);

        Event::assertDispatched(
            'eloquent.belongsToManyUpdatingExistingPivot: ' . User::class,
            function ($event, $callback) use ($user, $role) {
                return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
            }
        );
        Event::assertDispatched(
            'eloquent.belongsToManyUpdatedExistingPivot: ' . User::class,
            function ($event, $callback) use ($user, $role) {
                return $callback[0] == 'roles' && $callback[1]->is($user) && $callback[2][0] == $role->id;
            }
        );
    }
}
