<?php

namespace Chelout\RelationshipEvents\Tests\Stubs\Models;

use Chelout\RelationshipEvents\Concerns\HasBelongsToManyEvents;
use Chelout\RelationshipEvents\Concerns\HasManyEvents;
use Chelout\RelationshipEvents\Concerns\HasMorphOneEvents;
use Chelout\RelationshipEvents\Concerns\HasOneEvents;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyAttached;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyAttaching;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyDetached;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyDetaching;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManySynced;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManySyncing;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyToggled;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyToggling;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyUpdatedExistingPivot;
use Chelout\RelationshipEvents\Tests\Stubs\Events\BelongsToMany\BelongsToManyUpdatingExistingPivot;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasMany\HasManyCreated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasMany\HasManyCreating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasMany\HasManySaved;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasMany\HasManySaving;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasMany\HasManyUpdated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasMany\HasManyUpdating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasOne\HasOneCreated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasOne\HasOneCreating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasOne\HasOneSaved;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasOne\HasOneSaving;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasOne\HasOneUpdated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\HasOne\HasOneUpdating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphOne\MorphOneCreated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphOne\MorphOneCreating;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphOne\MorphOneSaved;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphOne\MorphOneSaving;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphOne\MorphOneUpdated;
use Chelout\RelationshipEvents\Tests\Stubs\Events\MorphOne\MorphOneUpdating;
use Chelout\RelationshipEvents\Traits\HasDispatchableEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class User extends Model
{
    use HasDispatchableEvents,
        HasOneEvents,
        HasManyEvents,
        HasMorphOneEvents,
        HasBelongsToManyEvents;

    protected $dispatchesEvents = [
        // HasOne
        'hasOneCreating' => HasOneCreating::class,
        'hasOneCreated' => HasOneCreated::class,
        'hasOneSaving' => HasOneSaving::class,
        'hasOneSaved' => HasOneSaved::class,
        'hasOneUpdating' => HasOneUpdating::class,
        'hasOneUpdated' => HasOneUpdated::class,
        // HasMany
        'hasManyCreating' => HasManyCreating::class,
        'hasManyCreated' => HasManyCreated::class,
        'hasManySaving' => HasManySaving::class,
        'hasManySaved' => HasManySaved::class,
        'hasManyUpdating' => HasManyUpdating::class,
        'hasManyUpdated' => HasManyUpdated::class,
        // BelongsToMany
        'belongsToManyAttaching' => BelongsToManyAttaching::class,
        'belongsToManyAttached' => BelongsToManyAttached::class,
        'belongsToManyDetaching' => BelongsToManyDetaching::class,
        'belongsToManyDetached' => BelongsToManyDetached::class,
        'belongsToManySyncing' => BelongsToManySyncing::class,
        'belongsToManySynced' => BelongsToManySynced::class,
        'belongsToManyToggling' => BelongsToManyToggling::class,
        'belongsToManyToggled' => BelongsToManyToggled::class,
        'belongsToManyUpdatingExistingPivot' => BelongsToManyUpdatingExistingPivot::class,
        'belongsToManyUpdatedExistingPivot' => BelongsToManyUpdatedExistingPivot::class,
        // MorphOne
        'morphOneCreating' => MorphOneCreating::class,
        'morphOneCreated' => MorphOneCreated::class,
        'morphOneSaving' => MorphOneSaving::class,
        'morphOneSaved' => MorphOneSaved::class,
        'morphOneUpdating' => MorphOneUpdating::class,
        'morphOneUpdated' => MorphOneUpdated::class,
    ];

    public static function setupTable()
    {
        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withPivot('note');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
