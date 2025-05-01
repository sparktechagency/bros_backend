<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarImage extends Model
{
    protected $guarded=['id'];

    public function getPhotoAttribute($value)
    {
        return asset('uploads/user_car_photo/') . "/" . $value;
    }
}
