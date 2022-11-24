<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $table = 'transactions';

    protected $primaryKey  = 'transactionId';

    protected $fillable = [
            'transactionAmount' ,
            'transactionDate' ,
            'customerId' ,
            'cartId' ,
            'paymentMethodId' ,
            'entryByUserId' ,

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'transactionId'=> 'integer',
        'transactionAmount'=> 'double',
        'customerId'=> 'integer',
        'cartId'=> 'integer',
        'paymentMethodId'=> 'integer',
        'entryByUserId'=> 'integer',
        'transactionDate'=> 'date',
    ];

}