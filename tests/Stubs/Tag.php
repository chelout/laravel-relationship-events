<?php

namespace Chelout\RelationshipEvents\Tests\Stubs;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{   
    protected $guarded = [];

    public static function setupTable()
    {
        Schema::create('tags', function ($table) {
            $table->increments('id');
            $table->timestamps();
        });
        Schema::create('taggables', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('tag_id');
            $table->unsignedInteger('taggable_id');
            $table->string('taggable_type');
            $table->timestamps();
        });
    }
}
