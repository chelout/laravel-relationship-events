<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Models;

use Chelout\RelationshipEvents\Concerns\HasMorphToEvents;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphTo\MorphToAssociated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphTo\MorphToAssociating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphTo\MorphToDissociated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphTo\MorphToDissociating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphTo\MorphToUpdated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphTo\MorphToUpdating;
use Chelout\RelationshipEvents\Traits\HasDispatchableEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Comment extends Model
{
    use HasDispatchableEvents,
        HasMorphToEvents;

    protected $guarded = [];

    protected $dispatchesEvents = [
        'morphToAssociating' => MorphToAssociating::class,
        'morphToAssociated' => MorphToAssociated::class,
        'morphToDissociating' => MorphToDissociating::class,
        'morphToDissociated' => MorphToDissociated::class,
        'morphToUpdating' => MorphToUpdating::class,
        'morphToUpdated' => MorphToUpdated::class,
    ];

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
