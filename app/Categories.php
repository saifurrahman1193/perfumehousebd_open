<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';

    protected $primaryKey  = 'categoryId';

    protected $fillable = [
            'parentCategoryId' ,
            'category' ,
            'description' ,
            'picPath' ,
            'categorySlug' ,

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'categoryId'=> 'integer',
    ];

}
