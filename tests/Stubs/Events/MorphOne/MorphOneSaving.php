<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Events\MorphOne;

class MorphOneSaving
{
    public $user;
    public $address;

    public function __construct($user, $params)
    {
        $this->user = $user;
        $this->address = $params[0];
    }
}
