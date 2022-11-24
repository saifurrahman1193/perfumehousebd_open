<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';

    protected $primaryKey  = 'cartId';

    protected $fillable = [
            'totalQty' ,
            'cartProductsSubTotalSum',
            'totalDiscountAmount',
            'cartProductsTotalSum',
            'areaId',
            'area',
            'shippingAddress',
            'contactNumber',
            'deliveryCharge',
            'deliveryMan',
            'deliveryManContactNumber',
            'isDeliveryComplete',
            'isPaymentComplete',
            'customerDeliveryComment',
            'customerReview',
            'customerRating',
            'userId',
            'paid',
            'due',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'cartId'=> 'integer',
        'totalQty'=> 'double',
        'cartProductsSubTotalSum'=> 'double',
        'totalDiscountAmount'=> 'double',
        'cartProductsTotalSum'=> 'double',
        'areaId'=> 'iinteger',
    ];

}

