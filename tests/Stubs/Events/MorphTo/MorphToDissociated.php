<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Events\MorphTo;

class MorphToDissociated
{
    public $post;
    public $relation;
    public $comment;

    public function __construct($comment, $params)
    {
        $this->comment = $comment;
        $this->relation = $params[0];
        $this->post = $params[1];
    }
}
