<?php

namespace Chelout\RelationshipEvents;

use Chelout\RelationshipEvents\Traits\HasEventDispatcher;
use Chelout\RelationshipEvents\Traits\HasOneOrManyMethods;
use Chelout\RelationshipEvents\Contracts\EventDispatcher;
use Illuminate\Database\Eloquent\Relations\MorphOne as MorphOneBase;

class MorphOne extends MorphOneBase implements EventDispatcher
{
    use HasEventDispatcher;
    use HasOneOrManyMethods;
}
