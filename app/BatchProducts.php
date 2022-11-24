<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatchProducts extends Model
{
    protected $table = 'batchproducts';

    protected $primaryKey  = 'batchProductId';

    protected $fillable = [
            'productId' ,
            'purchasePrice' ,
            'sellingPrice' ,
            'purchaseQty' ,
            'isStockOut' ,
            'manufactureDate' ,
            'expireDate' ,
            'supplierId' ,
            'batchDate' ,
            'contact' ,
            'representative' ,

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'batchProductId'=> 'integer',
        'productId'=> 'integer',
        'isStockOut'=> 'integer',
        'purchasePrice'=> 'double',
        'sellingPrice'=> 'double',
        'purchaseQty'=> 'double',
        'supplierId'=> 'double',
    ];

}
