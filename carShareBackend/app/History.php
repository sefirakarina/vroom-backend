<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public $timestamps = false;
    protected $table = 'histories';
    protected $fillable = ['user_id', 'car_id', 'return_location_id', 'begin_time','return_time'];

    public function users(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public static function history(){
        return History::all();
    }
}
