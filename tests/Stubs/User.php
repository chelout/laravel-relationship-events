<?php

namespace Chelout\RelationshipEvents\Tests\Stubs;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Chelout\RelationshipEvents\Concerns\HasOneEvents;
use Chelout\RelationshipEvents\Concerns\HasManyEvents;

class User extends Model
{
    use HasOneEvents,
        HasManyEvents;

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

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
