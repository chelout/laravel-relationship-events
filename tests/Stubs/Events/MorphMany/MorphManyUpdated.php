<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Events\MorphMany;

class MorphManyUpdated
{
    public $post;
    public $comment;

    public function __construct($post, $params)
    {
        $this->post = $post;
        $this->comment = $params[0];
    }
}
