<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsTo;

class BelongsToDissociated
{
    public $user;
    public $relation;
    public $profile;

    public function __construct($profile, $params)
    {
        $this->profile = $profile;
        $this->relation = $params[0];
        $this->user = $params[1];
    }
}
