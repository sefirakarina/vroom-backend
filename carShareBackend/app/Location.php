<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public $timestamps = false;
    protected $table = 'locations';
    protected $fillable = ['address', 'coordinate', 'slot', 'current_car_num'];

    public function customers(){
        return $this->hasMany('App\Car');
    }

    public static function location(){
        return Location::all();
    }

}
