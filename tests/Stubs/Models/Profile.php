<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Models;

use Chelout\RelationshipEvents\Concerns\HasBelongsToEvents;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsTo\BelongsToAssociated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsTo\BelongsToAssociating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsTo\BelongsToDissociated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsTo\BelongsToDissociating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsTo\BelongsToUpdated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsTo\BelongsToUpdating;
use Chelout\RelationshipEvents\Traits\HasDispatchableEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Profile extends Model
{
    use HasDispatchableEvents,
        HasBelongsToEvents;

    protected $guarded = [];

    protected $dispatchesEvents = [
        'belongsToAssociating' => BelongsToAssociating::class,
        'belongsToAssociated' => BelongsToAssociated::class,
        'belongsToDissociating' => BelongsToDissociating::class,
        'belongsToDissociated' => BelongsToDissociated::class,
        'belongsToUpdating' => BelongsToUpdating::class,
        'belongsToUpdated' => BelongsToUpdated::class,
    ];

    public static function setupTable()
    {
        Schema::create('profiles', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
