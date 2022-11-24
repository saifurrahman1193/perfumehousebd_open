<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SkinTypes extends Model
{
    protected $table = 'skintypes';

    protected $primaryKey  = 'skinTypeId';

    protected $fillable = [
            'skinType',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'skinTypeId'=> 'integer',
    ];

}

