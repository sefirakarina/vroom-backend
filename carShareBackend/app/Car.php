<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    public $timestamps = false;
    protected $table = 'cars';
    protected $fillable = ['location_id', 'plate','type', 'capacity', 'availability'];

    public function location(){
        return $this->belongsTo('App\Location', 'location_id');
    }

    public static function car(){
        return Car::all();
    }
}
