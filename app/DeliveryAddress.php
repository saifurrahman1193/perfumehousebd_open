<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{
    protected $table = 'deliveryaddress';

    protected $primaryKey  = 'deliveryAddressId';

    protected $fillable = [
            'deliveryAddressTitle' ,
            'deliveryAddress' ,
            'userId',
            'areaId',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'deliveryAddressId'=> 'integer',
        'userId'=> 'integer',
        'areaId'=> 'integer',
    ];

}

