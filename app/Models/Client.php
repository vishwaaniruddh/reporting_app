<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'customer';
    protected $fillable = ['name', 'status'];
    public $timestamps = false;
}
