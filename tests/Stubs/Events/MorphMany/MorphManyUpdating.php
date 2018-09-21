<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Events\MorphMany;

class MorphManyUpdating
{
    public $post;
    public $comment;

    public function __construct($post, $params)
    {
        $this->post = $post;
        $this->comment = $params[0];
    }
}
