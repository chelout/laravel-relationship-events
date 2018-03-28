<?php

namespace Chelout\RelationshipEvents\Relationships;

use Chelout\RelationshipEvents\Relationships\Traits\HasEventDispatcher;
use Chelout\RelationshipEvents\Relationships\Traits\HasOneOrManyMethods;
use Chelout\RelationshipEvents\Relationships\Contracts\EventDispatcher;
use Illuminate\Database\Eloquent\Relations\HasMany as HasManyBase;

class HasMany extends HasManyBase implements EventDispatcher
{
    use HasEventDispatcher;
    use HasOneOrManyMethods;
}
