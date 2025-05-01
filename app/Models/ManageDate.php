<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageDate extends Model
{
    /** @use HasFactory<\Database\Factories\ManageDateFactory> */
    use HasFactory;
    protected $guarded = ['id'];
}
