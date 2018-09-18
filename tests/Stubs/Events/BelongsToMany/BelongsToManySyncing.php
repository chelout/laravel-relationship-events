<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany;

class BelongsToManySyncing
{
    public $user;
    public $relation;
    public $roleId;
    public $attributes;

    public function __construct($user, $params)
    {
        $this->user = $user;
        $this->relation = $params[0];
        $this->roleId = $params[1][0];
        $this->attributes = $params[2];
    }
}
