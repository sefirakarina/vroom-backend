<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $timestamps = false;
    protected $table = 'cars';
    protected $fillable = ['location_id', 'plate','type', 'capacity', 'availability'];

}
