<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvertisementSlider extends Model
{
    protected $table = 'advertisementslider';

    protected $primaryKey  = 'advertisementSliderId';

    protected $fillable = [
            'picPath',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'advertisementSliderId'=> 'integer',
    ];

}

