<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountProductCustomer extends Model
{
    protected $table = 'discountproductcustomer';

    protected $primaryKey  = 'discountProductCustomerId';

    protected $fillable = [
            'productId',
            'customerId',
            'discountProductCustomerPercent' ,
            'discountProductCustomerAmount' ,
            'discountProductCustomerExpireDate' ,
            'discountReason' ,

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'discountProductCustomerId'=> 'integer',
        'productId'=> 'integer',
        'customerId'=> 'integer',
        'discountProductCustomerPercent'=> 'double',
        'discountProductCustomerAmount'=> 'double',
    ];

}
