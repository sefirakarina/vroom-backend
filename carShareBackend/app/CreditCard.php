<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    protected $timestamps = false;
    protected $table = 'credit_cards';
    protected $fillable = ['name', 'number', 'exp_date'];

    public function users(){
        return $this->belongsTo('App\User', 'user_id');
    }
}