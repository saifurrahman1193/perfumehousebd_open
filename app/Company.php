<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company';

    protected $primaryKey  = 'companyId';

    protected $fillable = [
            'name' ,
            'address' ,
            'phone',
            'email',
            'facebookIconClass',
            'facebookLink',
            'linkedinIconClass',
            'linkedinLink',
            'googlePlusIconClass',
            'googlePlusLink',
            'instagramIconClass',
            'instagramLink',
            'twitterIconClass',
            'twitterLink',
            'logoPath',
            'about',
            'aboutPicPath',

            
            'website',
            'contactMails',
            'numberTitle',
            'number',
            'mailSenderName',
            'mailSenderEmail',

            'topSystemQuote',

			'created_at',
			'updated_at',
    ];

    protected $casts = [
        'companyId'=> 'integer',
    ];

}