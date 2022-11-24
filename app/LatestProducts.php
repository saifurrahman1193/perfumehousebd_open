<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LatestProducts extends Model
{
    protected $table = 'latestproducts';

    protected $primaryKey  = 'latestProductId';

    protected $fillable = [
            'productId',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'latestProductId'=> 'integer',
        'productId'=> 'integer',
    ];


}


