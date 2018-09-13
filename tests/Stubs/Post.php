<?php

namespace Chelout\RelationshipEvents\Tests\Stubs;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Chelout\RelationshipEvents\Concerns\HasMorphManyEvents;

class Post extends Model
{
    use HasMorphManyEvents;
    
    protected $guarded = [];

    public static function setupTable()
    {
        Schema::create('posts', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
