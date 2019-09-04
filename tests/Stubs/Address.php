<?php

namespace Chelout\RelationshipEvents\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Address extends Model
{
    protected $guarded = [];

    public static function setupTable()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('addressable_id');
            $table->string('addressable_type');
            $table->timestamps();
        });
    }
}
