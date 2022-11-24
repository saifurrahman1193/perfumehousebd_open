<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $table = 'services';

    protected $primaryKey  = 'serviceId';

    protected $fillable = [
            'service' ,
            'description' ,
            'picPath' ,

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'serviceId'=> 'integer',
    ];

}
