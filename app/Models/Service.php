<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'provider',
        'representative',
        'serviceType',
        'coveredArea',
        'contactInform',
        'availableTime',
        'moreInfo'
    ];
}
