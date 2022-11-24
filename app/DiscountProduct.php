<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountProduct extends Model
{
    protected $table = 'discountproduct';

    protected $primaryKey  = 'discountProductId';

    protected $fillable = [
            'productId',
            'discountProductPercent' ,
            'discountProductAmount' ,
            'discountProductExpireDate' ,
            'discountReason' ,

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'discountProductId'=> 'integer',
        'productId'=> 'integer',
        'discountProductPercent'=> 'double',
        'discountProductAmount'=> 'double',
    ];

}

