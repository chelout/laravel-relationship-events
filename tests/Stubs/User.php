<?php

namespace Chelout\RelationshipEvents\Tests\Stubs;

use Chelout\RelationshipEvents\Concerns\HasBelongsToManyEvents;
use Chelout\RelationshipEvents\Concerns\HasManyEvents;
use Chelout\RelationshipEvents\Concerns\HasMorphOneEvents;
use Chelout\RelationshipEvents\Concerns\HasOneEvents;
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
            ->withPivot('note');;
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
