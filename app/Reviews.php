<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    protected $table = 'reviews';

    protected $primaryKey  = 'reviewId';

    protected $fillable = [
            'productId' ,
            'rating' ,
            'review' ,
            'reviewerId' ,

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'reviewId'=> 'integer',
        'productId'=> 'integer',
        'rating'=> 'integer',
        'reviewerId'=> 'integer',
    ];

}
