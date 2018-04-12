<?php

namespace Chelout\RelationshipEvents\Relationships;

use Chelout\RelationshipEvents\Traits\HasEventDispatcher;
use Chelout\RelationshipEvents\Traits\HasOneOrManyMethods;
use Chelout\RelationshipEvents\Contracts\EventDispatcher;
use Illuminate\Database\Eloquent\Relations\HasOne as HasOneBase;

class HasOne extends HasOneBase implements EventDispatcher
{
    use HasEventDispatcher;
    use HasOneOrManyMethods;
}
