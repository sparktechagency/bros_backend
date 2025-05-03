<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded=['id'];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function service(){
        return $this->belongsTo(Service::class);
    }
    public function getBookingDateAttribute($value){
        return Carbon::parse($value)->format('l,F d,Y');
    }
    public function getBookingTimeAttribute($value){
        return Carbon::parse($value)->format('h:i A');
    }
}
