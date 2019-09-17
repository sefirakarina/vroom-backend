<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public $timestamps = false;
    protected $table = 'bookings';
    protected $fillable = ['user_id', 'car_id', 'return_location_id', 'begin_time','return_time', 'customer_id', 'status'];

    public function users(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public static function booking(){
        return Booking::all();
    }
}
