<?php

namespace Chelout\RelationshipEvents\Tests\Stubs;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Chelout\RelationshipEvents\Concerns\HasMorphToEvents;

class Comment extends Model
{
    use HasMorphToEvents;
    
    protected $guarded = [];

    public static function setupTable()
    {
        Schema::create('comments', function ($table) {
            $table->increments('id');
            $table->string('commentable_id')->nullable();
            $table->string('commentable_type')->nullable();
            $table->timestamps();
        });
    }

    public function post()
    {
        return $this->morphTo(Post::class);
    }
}
