<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountBadges extends Model
{
    protected $table = 'discountbadges';

    protected $primaryKey  = 'discountBadgeId';

    protected $fillable = [
            'discountBadge' ,
            'reqPointsToAchieve' ,
            'discBadgeDiscountPercent' ,
            'color' ,
            'discountUpToAmount' ,

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'discountBadgeId'=> 'integer',
        'reqPointsToAchieve'=> 'double',
        'discBadgeDiscountPercent'=> 'double',
        'discountUpToAmount'=> 'double',
    ];

}

