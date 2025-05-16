<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Observers;

use Chelout\RelationshipEvents\Tests\Stubs\Jobs\TestJob;

class UserObserver {
	public function belongsToManyAttached($relation, $user, $ids) {
		TestJob::dispatch();
	}
}
