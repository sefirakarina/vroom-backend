<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    public $timestamps = false;
    protected $table = 'credit_cards';
    protected $fillable = ['name', 'number', 'exp_date','customer_id'];

    public function customer(){
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    public static function creditCard(){
        return CreditCard::all();
    }
}
