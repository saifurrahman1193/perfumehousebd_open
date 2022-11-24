<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTypes extends Model
{
    protected $table = 'producttypes';

    protected $primaryKey  = 'productTypeId';

    protected $fillable = [
            'productType',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'productTypeId'=> 'integer',
    ];

}

