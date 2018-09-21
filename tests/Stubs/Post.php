<?php

namespace Chelout\RelationshipEvents\Tests\Stubs;

use Chelout\RelationshipEvents\Concerns\HasMorphManyEvents;
use Chelout\RelationshipEvents\Concerns\HasMorphToManyEvents;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphMany\MorphManyCreated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphMany\MorphManyCreating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphMany\MorphManySaved;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphMany\MorphManySaving;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphMany\MorphManyUpdated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphMany\MorphManyUpdating;
use Chelout\RelationshipEvents\Traits\HasDispatchableEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Post extends Model
{
    use HasDispatchableEvents,
        HasMorphManyEvents,
        HasMorphToManyEvents;

    protected $guarded = [];

    protected $dispatchesEvents = [
        // MorphMany
        'morphManyCreating' => MorphManyCreating::class,
        'morphManyCreated' => MorphManyCreated::class,
        'morphManySaving' => MorphManySaving::class,
        'morphManySaved' => MorphManySaved::class,
        'morphManyUpdating' => MorphManyUpdating::class,
        'morphManyUpdated' => MorphManyUpdated::class,
    ];

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

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
