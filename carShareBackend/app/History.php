<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public $timestamps = false;
    protected $table = 'histories';
    protected $fillable = ['customer_id', 'car_id', 'return_location_id', 'begin_time','return_time'];

    public function customers(){
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function cars(){
        return $this->hasOne('App\Cars');
    }

    public static function history(){
        return History::all();
    }
}
