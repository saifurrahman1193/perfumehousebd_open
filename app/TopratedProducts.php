<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopratedProducts extends Model
{
    protected $table = 'topratedproducts';

    protected $primaryKey  = 'topatedProductId';

    protected $fillable = [
            'productId',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'topatedProductId'=> 'integer',
        'productId'=> 'integer',
    ];


}


