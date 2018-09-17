<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Events\HasOne;

class HasOneUpdating
{
    public $user;
    public $profile;

    public function __construct($user, $params)
    {
        $this->user = $user;
        $this->profile = $params[0];
    }
}
