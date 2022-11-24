<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LimitedEditions extends Model
{
    protected $table = 'limitededitions';

    protected $primaryKey  = 'limitedEditionId';

    protected $fillable = [
            'productId',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'limitedEditionId'=> 'integer',
        'productId'=> 'integer',
    ];


}


