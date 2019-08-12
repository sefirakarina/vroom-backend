<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public $timestamps = false;
    protected $table = 'customers';
    protected $fillable = ['user_id', 'credit_card_id', 'address', 'phone_number', 'license_number','status'];

    public function users(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function creditCards(){
        return $this->hasOne('App\CreditCard');
    }

    public static function customer(){
        return Customer::all();
    }
}
