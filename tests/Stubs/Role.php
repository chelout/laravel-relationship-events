<?php

namespace Chelout\RelationshipEvents\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Role extends Model
{
    protected $guarded = [];

    public static function setupTable()
    {
        Schema::create('roles', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('role_user', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('user_id');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }
}
