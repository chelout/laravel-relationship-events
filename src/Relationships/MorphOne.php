<?php

namespace Chelout\RelationshipEvents\Relationships;

use Chelout\RelationshipEvents\Relationships\Traits\HasEventDispatcher;
use Chelout\RelationshipEvents\Relationships\Traits\HasOneOrManyMethods;
use Chelout\RelationshipEvents\Relationships\Contracts\EventDispatcher;
use Illuminate\Database\Eloquent\Relations\MorphOne as MorphOneBase;

class MorphOne extends MorphOneBase implements EventDispatcher
{
    use HasEventDispatcher;
    use HasOneOrManyMethods;
}
