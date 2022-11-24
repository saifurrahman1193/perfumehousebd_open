<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BestsellingProducts extends Model
{
    protected $table = 'bestsellingproducts';

    protected $primaryKey  = 'bestsellingProductId';

    protected $fillable = [
            'productId',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'bestsellingProductId'=> 'integer',
        'productId'=> 'integer',
    ];


}


