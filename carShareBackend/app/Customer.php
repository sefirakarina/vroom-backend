<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $timestamps = false;
    protected $table = 'customers';
    protected $fillable = ['user_id', 'credit_card_id', 'address', 'phone_number', 'license_number'];

    public function users(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
