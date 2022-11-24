<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeaturedProducts extends Model
{
    protected $table = 'featuredproducts';

    protected $primaryKey  = 'featuredProductId';

    protected $fillable = [
            'productId',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'featuredProductId'=> 'integer',
        'productId'=> 'integer',
    ];


}


