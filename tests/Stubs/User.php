<?php

namespace Chelout\RelationshipEvents\Tests\Stubs;

use Chelout\RelationshipEvents\Concerns\HasBelongsToManyEvents;
use Chelout\RelationshipEvents\Concerns\HasManyEvents;
use Chelout\RelationshipEvents\Concerns\HasMorphOneEvents;
use Chelout\RelationshipEvents\Concerns\HasOneEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class User extends Model
{
    use HasOneEvents;
    use HasManyEvents;
    use HasMorphOneEvents;
    use HasBelongsToManyEvents;

    public static function setupTable()
    {
        Schema::create('users', function (Blueprint $table) {
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
        return $this->belongsToMany(Role::class, 'role_user');
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
