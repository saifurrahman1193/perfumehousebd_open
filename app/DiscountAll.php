<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountAll extends Model
{
    protected $table = 'discountall';

    protected $primaryKey  = 'discountAllId';

    protected $fillable = [
            'discountAllPercent' ,
            'discountAllAmount' ,
            'discountAllExpireDate' ,
            'discountReason' ,

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'discountAllId'=> 'integer',
        'discountAllPercent'=> 'double',
        'discountAllAmount'=> 'double',
    ];

}

