<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formulation extends Model
{
    protected $table = 'formulation';

    protected $primaryKey  = 'formulationId';

    protected $fillable = [
            'formulation',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'formulationId'=> 'integer',
    ];

}
