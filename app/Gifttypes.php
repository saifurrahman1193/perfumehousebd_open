<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gifttypes extends Model
{
    protected $table = 'gifttypes';

    protected $primaryKey  = 'giftTypeId';

    protected $fillable = [
            'giftType' ,
            'giftTypeSlug' ,

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'giftTypeId'=> 'integer',
    ];

}
