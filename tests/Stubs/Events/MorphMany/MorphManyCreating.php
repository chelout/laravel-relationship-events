<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Events\MorphMany;

class MorphManyCreating
{
    public $post;
    public $comment;

    public function __construct($post, $params)
    {
        $this->post = $post;
        $this->comment = $params[0];
    }
}
