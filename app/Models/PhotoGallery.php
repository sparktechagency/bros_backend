<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoGallery extends Model
{
    protected $guarded = ['id'];

    public function getPhotoAttribute($value)
    {
        return asset('uploads/photo_gallery') . "/" . $value;
    }
}
