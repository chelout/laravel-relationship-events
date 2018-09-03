<?php

namespace Chelout\RelationshipEvents\Tests\Stubs;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $guarded = [];
    
    public static function setupTable()
    {
        Schema::create('profiles', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });
    }
}
