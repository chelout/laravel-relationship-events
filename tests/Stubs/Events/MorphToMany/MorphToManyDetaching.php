<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany;

class MorphToManyDetaching
{
    public $post;
    public $relation;
    public $tagId;

    public function __construct($post, $params)
    {
        $this->post = $post;
        $this->relation = $params[0];
        $this->tagId = $params[1][0];
    }
}
