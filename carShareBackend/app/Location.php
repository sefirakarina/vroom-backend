<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $timestamps = false;
    protected $table = 'locations';
    protected $fillable = ['address', 'coordinate', 'slot', 'current_car_num'];

}
