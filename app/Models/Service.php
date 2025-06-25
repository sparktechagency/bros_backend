<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = ['id'];



    public function getIconAttribute($value)
    {
        return asset('uploads/services') . "/" . $value;
    }
    public function getTimeAttribute($value){
       return json_decode($value);
    }

    public function serviceTimes()
    {
        return $this->hasMany(ServiceTime::class);
    }
}
