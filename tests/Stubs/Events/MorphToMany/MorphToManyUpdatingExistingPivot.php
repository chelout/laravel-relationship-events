<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany;

class MorphToManyUpdatingExistingPivot
{
    public $post;
    public $relation;
    public $tagId;
    public $attributes;

    public function __construct($post, $params)
    {
        $this->post = $post;
        $this->relation = $params[0];
        $this->tagId = $params[1][0];
        $this->attributes = $params[2];
    }
}
