<?php

namespace Chelout\RelationshipEvents\Relationships;

use Chelout\RelationshipEvents\Relationships\Traits\HasEventDispatcher;
use Chelout\RelationshipEvents\Relationships\Traits\HasOneOrManyMethods;
use Chelout\RelationshipEvents\Relationships\Contracts\EventDispatcher;
use Illuminate\Database\Eloquent\Relations\HasOne as HasOneBase;

class HasOne extends HasOneBase implements EventDispatcher
{
    use HasEventDispatcher;
    use HasOneOrManyMethods;

    protected static $relationEventName = 'hasOne';
}
