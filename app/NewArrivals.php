<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewArrivals extends Model
{
    protected $table = 'newarrivals';

    protected $primaryKey  = 'newArrivalId';

    protected $fillable = [
            'productId',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'newArrivalId'=> 'integer',
        'productId'=> 'integer',
    ];


}


