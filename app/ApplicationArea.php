<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationArea extends Model
{
    protected $table = 'applicationarea';

    protected $primaryKey  = 'applicationAreaId';

    protected $fillable = [
            'applicationArea',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'applicationAreaId'=> 'integer',
    ];

}

