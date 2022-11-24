<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productvisit extends Model
{
    protected $table = 'productvisit';

    protected $primaryKey  = 'productvisitId';

    protected $fillable = [
            'productId' ,
            'userId' ,

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'productvisitId'=> 'integer',
        'productId'=> 'integer',
        'userId'=> 'integer',
    ];

}

