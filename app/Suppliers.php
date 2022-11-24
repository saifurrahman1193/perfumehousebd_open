<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suppliers extends Model
{
    protected $table = 'suppliers';

    protected $primaryKey  = 'supplierId';

    protected $fillable = [
            'supplier' ,
            'phone' ,
            'description' ,
            'address' ,
            'email' ,
            'logoPath' ,

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'supplierId'=> 'integer',
    ];

}


