<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany;

class MorphedByManySyncing
{
    public $tag;
    public $relation;
    public $postId;
    public $attributes;

    public function __construct($tag, $params)
    {
        $this->tag = $tag;
        $this->relation = $params[0];
        $this->postId = $params[1][0];
        $this->attributes = $params[2];
    }
}
