<?php

namespace Chelout\RelationshipEvents\Tests\Feature;

use Chelout\RelationshipEvents\Tests\Stubs\Role;
use Chelout\RelationshipEvents\Tests\Stubs\User;
use Chelout\RelationshipEvents\Tests\TestCase;

class HasRelationshipObservablesTraitTest extends TestCase
{
	public function setup(): void
	{
		parent::setup();

		User::setupTable();
		Role::setupTable();
	}

	/** @test */
	public function it_fails_first_time()
	{
		$user = User::create();
		$role = Role::create(['name' => 'admin']);
		$user->roles()->attach($role);


		$this->assertEquals(
			collect(User::getRelationshipObservables())->count(),
			collect(User::getRelationshipObservables())->unique()->count()
		);
	}

	/** @test */
	public function it_fails_even_greater_second_time()
	{
		$this->withoutJobs();
		$this->beforeApplicationDestroyed(function () {
			$this->assertCount(1, $this->dispatchedJobs);
		});

		$user = User::create();
		$role = Role::create(['name' => 'admin']);
		$user->roles()->attach($role);

		$this->assertEquals(
			collect(User::getRelationshipObservables())->count(),
			collect(User::getRelationshipObservables())->unique()->count()
		);

	}
}
