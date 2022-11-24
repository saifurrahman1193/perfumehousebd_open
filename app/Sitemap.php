<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sitemap extends Model
{
    protected $table = 'sitemap';

    protected $primaryKey  = 'id';

    protected $fillable = [
            'sitemap' ,
            'sitemapUrl' ,

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'id'=> 'integer',
    ];

}
