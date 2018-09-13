<?php

namespace Chelout\RelationshipEvents\Tests\Stubs;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = [];

    public static function setupTable()
    {
        Schema::create('comments', function ($table) {
            $table->increments('id');
            $table->string('commentable_id');
            $table->string('commentable_type');
            $table->timestamps();
        });
    }
}
