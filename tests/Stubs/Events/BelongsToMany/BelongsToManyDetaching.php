<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany;

class BelongsToManyDetaching
{
    public $user;
    public $relation;
    public $roleId;

    public function __construct($user, $params)
    {
        $this->user = $user;
        $this->relation = $params[0];
        $this->roleId = $params[1][0];
    }
}
