<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Models;

use Chelout\RelationshipEvents\Concerns\HasMorphedByManyEvents;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyAttached;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyAttaching;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyDetached;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyDetaching;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManySynced;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManySyncing;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyToggled;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyToggling;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyUpdatedExistingPivot;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphedByMany\MorphedByManyUpdatingExistingPivot;
use Chelout\RelationshipEvents\Traits\HasDispatchableEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Tag extends Model
{
    use HasDispatchableEvents,
        HasMorphedByManyEvents;

    protected $guarded = [];

    protected $dispatchesEvents = [
        // MorphedByMany
        'morphedByManyAttaching' => MorphedByManyAttaching::class,
        'morphedByManyAttached' => MorphedByManyAttached::class,
        'morphedByManyDetaching' => MorphedByManyDetaching::class,
        'morphedByManyDetached' => MorphedByManyDetached::class,
        'morphedByManySyncing' => MorphedByManySyncing::class,
        'morphedByManySynced' => MorphedByManySynced::class,
        'morphedByManyToggling' => MorphedByManyToggling::class,
        'morphedByManyToggled' => MorphedByManyToggled::class,
        'morphedByManyUpdatingExistingPivot' => MorphedByManyUpdatingExistingPivot::class,
        'morphedByManyUpdatedExistingPivot' => MorphedByManyUpdatedExistingPivot::class,
    ];

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

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
}
