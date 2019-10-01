<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    public $timestamps = false;
    protected $table = 'bookings';
    protected $fillable = ['user_id', 'car_id', 'return_location_id', 'begin_time','return_time', 'customer_id', 'status'];

    public function customers(){
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public function cars(){
        return $this->hasOne('App\Cars');
    }

    public static function booking(){
        return Booking::all();
    }
}
