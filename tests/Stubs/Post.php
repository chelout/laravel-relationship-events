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
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyAttached;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyAttaching;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyDetached;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyDetaching;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManySynced;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManySyncing;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyToggled;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyToggling;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyUpdatedExistingPivot;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphToMany\MorphToManyUpdatingExistingPivot;
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
        // MorphToMany
        'morphToManyAttaching' => MorphToManyAttaching::class,
        'morphToManyAttached' => MorphToManyAttached::class,
        'morphToManyDetaching' => MorphToManyDetaching::class,
        'morphToManyDetached' => MorphToManyDetached::class,
        'morphToManySyncing' => MorphToManySyncing::class,
        'morphToManySynced' => MorphToManySynced::class,
        'morphToManyToggling' => MorphToManyToggling::class,
        'morphToManyToggled' => MorphToManyToggled::class,
        'morphToManyUpdatingExistingPivot' => MorphToManyUpdatingExistingPivot::class,
        'morphToManyUpdatedExistingPivot' => MorphToManyUpdatedExistingPivot::class,
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
