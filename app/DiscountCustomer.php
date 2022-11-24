<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountCustomer extends Model
{
    protected $table = 'discountcustomer';

    protected $primaryKey  = 'discountCustomerId';

    protected $fillable = [
            'customerId',
            'discountCustomerPercent' ,
            'discountCustomerAmount' ,
            'discountCustomerExpireDate' ,
            'discountReason' ,

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'discountCustomerId'=> 'integer',
        'customerId'=> 'integer',
        'discountCustomerPercent'=> 'double',
        'discountCustomerAmount'=> 'double',
    ];

}
