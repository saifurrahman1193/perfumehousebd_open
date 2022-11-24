<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brand';

    protected $primaryKey  = 'brandId';

    protected $fillable = [
            'brand',
            'logo',
            'brandSlug',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'brandId'=> 'integer',
    ];

}

