<?php

namespace Chelout\RelationshipEvents;

use Chelout\RelationshipEvents\Contracts\EventDispatcher;
use Chelout\RelationshipEvents\Traits\HasEventDispatcher;
use Chelout\RelationshipEvents\Traits\HasOneOrManyMethods;
use Illuminate\Database\Eloquent\Relations\HasOne as HasOneBase;

/**
 * Class HasOne.
 */
class HasOne extends HasOneBase implements EventDispatcher
{
    use HasEventDispatcher;
    use HasOneOrManyMethods;
}
