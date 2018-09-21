<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany;

class MorphedByManyDetaching
{
    public $tag;
    public $relation;
    public $postId;

    public function __construct($tag, $params)
    {
        $this->tag = $tag;
        $this->relation = $params[0];
        $this->postId = $params[1][0];
    }
}
