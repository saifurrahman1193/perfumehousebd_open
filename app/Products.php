<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';

    protected $primaryKey  = 'productId';

    protected $fillable = [
            'productName',
            'sku',
            'categoryId' ,
            'category' ,
            'description' ,
            'shortDescription' ,
            'youtube' ,
            'brandId',
            'genderId',
            'applicationAreaId',
            'productTypeId',
            'countryId',
            'formulationId',
            'skinTypeId',
            'searchTags',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'productId'=> 'integer',
        'categoryId'=> 'integer',
        'brandId' => 'integer' ,
        'genderId' => 'integer' ,
        'applicationAreaId' => 'integer' ,
        'productTypeId' => 'integer' ,
        'countryId' => 'integer' ,

        'formulationId' => 'integer' ,
        'skinTypeId' => 'integer' ,
    ];

    public function country()
    {
        return $this->hasOne(Country::class, 'countryId', 'countryId');
    }


}


