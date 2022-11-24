<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'paymentmethods';

    protected $primaryKey  = 'paymentMethodId';

    protected $fillable = [
            'paymentMethod' ,
            'accountNumber',
            'accountType',
            'option',
            'instruction',
            'isVisible',
            'qrCode',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'paymentMethodId'=> 'integer',
    ];

}


