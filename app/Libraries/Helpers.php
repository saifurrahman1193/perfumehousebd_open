<?php

    // ==============number related=================
    // ==============number related=================
    function nvl($number)
    {
        if (isset($number))
        {
            return $number;
        }
        else
        {
            return 0;
        }
    }

    function numberFormat($number, $decimals=0)
    {

        // $number = 555;
        // $decimals=0;
        // $number = 555.000;
        // $number = 555.123456;

        if (strpos($number,'.')!=null)
        {
            $decimalNumbers = substr($number, strpos($number,'.'));
            $decimalNumbers = substr($decimalNumbers, 1, $decimals);
        }
        else
        {
            $decimalNumbers = 0;
            for ($i = 2; $i <=$decimals ; $i++)
            {
                $decimalNumbers = $decimalNumbers.'0';
            }
        }
        // return $decimalNumbers;



        $number = (int) $number;
        // reverse
        $number = strrev($number);

        $n = '';
        $stringlength = strlen($number);

        for ($i = 0; $i < $stringlength; $i++)
        {
            if ($i%2==0 && $i!=$stringlength-1 && $i>1)
            {
                $n = $n.$number[$i].',';
            }
            else
            {
                $n = $n.$number[$i];
            }
        }

        $number = $n;
        // reverse
        $number = strrev($number);

        ($decimals!=0)? $number=$number.'.'.$decimalNumbers : $number ;

        return $number;
    }


    // ================string related==================
    // ================string related==================

    function partially_hide_email($email)
    {
        $em   = explode("@",$email);
        $name = implode(array_slice($em, 0, count($em)-1), '@');
        $len  = floor(strlen($name)/2);

        return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
    }

    function getLastWord($sentence)
    {
        $pieces = explode(' ', $sentence);
        $last_word = array_pop($pieces);

        return $last_word;
    }

    // Converting array of string to array of integers in PHP
    function getArrOfStrToArrOfInt($arrOfStr=[])
    {
        $arr = [];
        $arr = array_map('intval', $arrOfStr);
        return $arr;
    }



    // ===============date related=================
    // ===============date related=================
    function dmyToYmd($date)
    {
        if ($date) {
            $date = substr($date, 6,4).'-'.substr($date, 3,2).'-'.substr($date, 0,2);
            return $date;
        } else {
            return '';
        }
    }

    function YmdTodmY($date)
    {
        if ($date) {
            $date = \Carbon\Carbon::parse($date)->format('d-m-Y');
            return $date;
        } else {
            return '';
        }
    }

    function YmdTodmYPm($date)
    {
        if ($date) {
            $date = \Carbon\Carbon::parse($date)->format('d-m-Y  g:i A');
            return $date;
        } else {
            return '';
        }
    }

    function YmdTodmYPmDay($date)
    {
        if ($date) {
            $date = \Carbon\Carbon::parse($date)->format('d-m-Y  g:i A  (l)');
            return $date;
        } else {
            return '';
        }
    }

    function YmdToY($date)
    {
        if ($date) {
            $date = \Carbon\Carbon::parse($date)->format('Y');
            return $date;
        } else {
            return '';
        }
    }

    function YmdToM($date)
    {
        if ($date) {
            $date = \Carbon\Carbon::parse($date)->format('m');
            return $date;
        } else {
            return '';
        }
    }


    function getToday()
    {
        return date('Y-m-d');
    }



    function getCurrentYear()
    {
        return date('Y');
    }

    function getCurrentYearFirstDate()
    {
        return date('Y-m-d', strtotime('first day of january this year'));
    }

    function getCurrentYearLastDate()
    {
        return date('Y-m-d', strtotime('last day of december this year'));
    }

    function getYearFromYmdDate($ymdDate)
    {
        return date('Y', strtotime($ymdDate));
    }

    function getSpecificYearFirstDate($year)
    {
        return date('Y-m-d', strtotime('first day of january '.$year));
    }

    function getSpecificYearLastDate($year)
    {
        return date('Y-m-d', strtotime('last day of december '.$year));
    }

    function getCurrentMonthNumber()
    {
        return date("m");
    }

    function getCurrentMonthFirstDate()
    {
        return date('Y-m-01');
    }
    function getCurrentMonthLastDate()
    {
        $date = new DateTime('now');
        $date->modify('last day of this month');
        $date =  $date->format('Y-m-d');
        return  $date;
    }

    function getSpecificYearMonthFirstDate($year, $monthNumber)
    {
        return  date('Y-m-d', strtotime('first day of '.getMonthLongLowerCase($monthNumber).' '.$year));
    }
    function getSpecificYearMonthLastDate($year, $monthNumber)
    {
        return  date('Y-m-d', strtotime('last day of '.getMonthLongLowerCase($monthNumber).' '.$year));
    }


    function getMonths(){
        $months = [
            array('monthId' => 1, 'monthLong' => 'January', 'monthLongLowerCase' => 'january', 'monthShort' => 'Jan'),
            array('monthId' => 2, 'monthLong' => 'February', 'monthLongLowerCase' => 'february', 'monthShort' => 'Feb'),
            array('monthId' => 3, 'monthLong' => 'March', 'monthLongLowerCase' => 'march', 'monthShort' => 'Mar'),
            array('monthId' => 4, 'monthLong' => 'April', 'monthLongLowerCase' => 'april', 'monthShort' => 'Apr'),
            array('monthId' => 5, 'monthLong' => 'May', 'monthLongLowerCase' => 'may', 'monthShort' => 'May'),
            array('monthId' => 6, 'monthLong' => 'Jun', 'monthLongLowerCase' => 'jun', 'monthShort' => 'Jun'),
            array('monthId' => 7, 'monthLong' => 'July', 'monthLongLowerCase' => 'july', 'monthShort' => 'Jul'),
            array('monthId' => 8, 'monthLong' => 'August', 'monthLongLowerCase' => 'august', 'monthShort' => 'Aug'),
            array('monthId' => 9, 'monthLong' => 'September', 'monthLongLowerCase' => 'september', 'monthShort' => 'Sep'),
            array('monthId' => 10, 'monthLong' => 'October', 'monthLongLowerCase' => 'october', 'monthShort' => 'Oct'),
            array('monthId' => 11, 'monthLong' => 'November', 'monthLongLowerCase' => 'november', 'monthShort' => 'Nov'),
            array('monthId' => 12, 'monthLong' => 'December', 'monthLongLowerCase' => 'december', 'monthShort' => 'Dec'),
        ];
        return $months;
    }

    function getMonthLongLowerCase($monthId)
    {
        $monthLongLowerCase = '';

        $months = getMonths();

        foreach ($months as $key => $value)
        {
            if(($key+1)==$monthId)
            {
                $monthLongLowerCase=$value['monthLongLowerCase'];
            }
        }

        return $monthLongLowerCase;
    }


    function getNumberOfDaysFrom2YmdDates($YmdDate1='', $YmdDate2='')
    {
        $date1 = new DateTime($YmdDate1);
        $date2 = new DateTime($YmdDate2);

        $interval = $date1->diff($date2);
        return $interval->days;
    }





    // ====================Mail related==========================
    // ====================Mail related==========================
    function mailformat1($mailReceiverEmail, $mailReceiverName, $mailSenderEmail, $mailSenderName , $subject, $bodyMessage, $website, $contactMails, $numberTitle, $number,  $cartData, $cartdetailsData, $userdata)
    {
        $data = array(
            [
                'mailReceiverEmail' => $mailReceiverEmail,
                'mailReceiverName' => $mailReceiverName,
                'mailSenderEmail' => $mailSenderEmail,
                'mailSenderName' => $mailSenderName ,
                'subject' => $subject,
                'bodyMessage' => $bodyMessage,
                'website' => $website,
                'contactMails' => $contactMails,
                'numberTitle' => $numberTitle,
                'number' => $number,
                'cartData' => $cartData,
                'cartdetailsData' => $cartdetailsData,
                'userdata' => $userdata,

            ]
        );

        // dd($data);
        // dd($data[0]);
        // dd($data[0]['mailSenderEmail']);



        try{
            Mail::send('mails.mailformat1', $data[0], function ($message)  use ($data) {
                $message->to($data[0]['mailReceiverEmail'], $data[0]['mailReceiverName'])
                        ->bcc('saifuroracle@gmail.com')
                        ->replyTo($data[0]['mailSenderEmail'], $data[0]['mailSenderName'])
                        ->sender($data[0]['mailSenderEmail'], $data[0]['mailSenderName'])
                        ->priority(1)
                        ->returnPath($data[0]['mailSenderEmail'])
                        ->subject($data[0]['subject']);
                $message->from($data[0]['mailSenderEmail'], $data[0]['mailSenderName']);
            });
        }
        catch (Exception $e) {
            DB::table('errors')->insert([
                'error' => 'Mail Sending Error - Order related',
                'message' => $e->getMessage()
            ]);
        }

    }

    function mailformat2($mailReceiverEmail, $mailReceiverName, $mailSenderEmail, $mailSenderName , $subject, $bodyMessage, $website, $contactMails, $numberTitle, $number)
    {
        $data = array(
            [
                'mailReceiverEmail' => $mailReceiverEmail,
                'mailReceiverName' => $mailReceiverName,
                'mailSenderEmail' => $mailSenderEmail,
                'mailSenderName' => $mailSenderName ,
                'subject' => $subject,
                'bodyMessage' => $bodyMessage,
                'website' => $website,
                'contactMails' => $contactMails,
                'numberTitle' => $numberTitle,
                'number' => $number,
            ]
        );

        // dd($data);
        // dd($data[0]);
        // dd($data[0]['mailSenderEmail']);



        try{
            Mail::send('mails.mailformat2', $data[0], function ($message)  use ($data) {
                $message->to($data[0]['mailReceiverEmail'], $data[0]['mailReceiverName'])
                        ->bcc('saifuroracle@gmail.com')
                        ->replyTo($data[0]['mailSenderEmail'], $data[0]['mailSenderName'])
                        ->sender($data[0]['mailSenderEmail'], $data[0]['mailSenderName'])
                        ->priority(1)
                        ->returnPath($data[0]['mailSenderEmail'])
                        ->subject($data[0]['subject']);

                $message->from($data[0]['mailSenderEmail'], $data[0]['mailSenderName']);
            });
        }
        catch (Exception $e) {
            DB::table('errors')->insert([
                'error' => 'Mail Sending Error -'.$e->getMessage()
            ]);
        }

    }


    function getProductDiscount($productId)
    {
        if (auth()->user())
        {
            $user = auth()->user();

            if ($user->discountBadgeId==null)
            {
                // ==============all=========
                $discountallgetProdDiscAll = Cache::rememberForever('discountallgetProdDiscAll', function () {
                    return DB::table('discountall')->get();
                });
                $discountallgetProdDiscAll = $discountallgetProdDiscAll
                                ->where('discountAllExpireDate','>=', date('Y-m-d') )->last();
                // ==============all=========


                // ==============all=========
                if (isset($discountallgetProdDiscAll) and $discountallgetProdDiscAll!=null )
                {
                    $percent = $discountallgetProdDiscAll->discountAllPercent;
                    $amount = $discountallgetProdDiscAll->discountAllAmount;
                    $expireDate = $discountallgetProdDiscAll->discountAllExpireDate;
                    $discountReason = $discountallgetProdDiscAll->discountReason;
                    return array($percent, $amount, $expireDate, $discountReason);
                }
                // ==============all=========
            }

        }
        else
        {
            // ==============all=========
            $discountallgetProdDiscAllElse = Cache::rememberForever('discountallgetProdDiscAllElse', function () {
                return DB::table('discountall')->get();
            });
            $discountallgetProdDiscAllElse = $discountallgetProdDiscAllElse
                            ->where('discountAllExpireDate','>=', date('Y-m-d') )->last();
            if (isset($discountallgetProdDiscAllElse) and $discountallgetProdDiscAllElse!=null )
            {
                $percent = $discountallgetProdDiscAllElse->discountAllPercent;
                $amount = $discountallgetProdDiscAllElse->discountAllAmount;
                $expireDate = $discountallgetProdDiscAllElse->discountAllExpireDate;
                $discountReason = $discountallgetProdDiscAllElse->discountReason;
                return array($percent, $amount, $expireDate, $discountReason);
            }

            // ==============all=========
        }
    }



    function getProductDiscountOld($productId)
    {
        if (auth()->user())
        {
            $user = auth()->user();

            // ========product customer=======
            $discprodcustgetProdDisc = Cache::rememberForever('discprodcustgetProdDisc', function () {
                return DB::table('discountproductcustomer')->get();
            });

            $discprodcustgetProdDisc = $discprodcustgetProdDisc
                            ->where('productId', $productId)
                            ->where('customerId',$user->id )
                            ->where('discountProductCustomerExpireDate','>=', date('Y-m-d') )->last();
            // ========product customer=======

            // ==============customer=========
            $disccustGetProdDiscCust = Cache::rememberForever('disccustGetProdDiscCust', function () {
                return DB::table('discountcustomer')->get();
            });
            $disccustGetProdDiscCust = $disccustGetProdDiscCust
                            ->where('customerId',$user->id )
                            ->where('discountCustomerExpireDate','>=', date('Y-m-d') )->last();
            // ==============customer=========


             // ========product =======
             $discountproductgetProdDiscProd = Cache::rememberForever('discountproductgetProdDiscProd', function () {
                return DB::table('discountproduct')->get();
            });
            $discountproductgetProdDiscProd = $discountproductgetProdDiscProd
                            ->where('productId', $productId)
                            ->where('discountProductExpireDate','>=', date('Y-m-d') )->last();
            // ========product =======

            // ==============all=========
            $discountallgetProdDiscAll = Cache::rememberForever('discountallgetProdDiscAll', function () {
                return DB::table('discountall')->get();
            });
            $discountallgetProdDiscAll = $discountallgetProdDiscAll
                            ->where('discountAllExpireDate','>=', date('Y-m-d') )->last();
            // ==============all=========


            // ========product customer=======
            if (isset($discprodcustgetProdDisc) and $discprodcustgetProdDisc!=null )
            {
                $percent = $discprodcustgetProdDisc->discountProductCustomerPercent;
                $amount = $discprodcustgetProdDisc->discountProductCustomerAmount;
                $expireDate = $discprodcustgetProdDisc->discountProductCustomerExpireDate;
                $discountReason = $discprodcustgetProdDisc->discountReason;
                if ($percent>0 || $amount>0)
                {
                    return array($percent, $amount, $expireDate, $discountReason);
                }
            }

            // ========product customer=======


            // ========discount badge=======

            if($user->discountBadgeId!=null and !(
                (isset($discprodcustgetProdDisc) and $discprodcustgetProdDisc!=null )
                or (isset($disccustGetProdDiscCust) and $disccustGetProdDiscCust!=null )
                or (isset($discountproductgetProdDiscProd) and $discountproductgetProdDiscProd!=null )
                or (isset($discountallgetProdDiscAll) and $discountallgetProdDiscAll!=null )
            ))
            {
                $discountbadges = Cache::rememberForever('discountbadges', function () {
                    return DB::table('discountbadges')->get();
                });

                $discountbadge = $discountbadges->where('discountBadgeId', $user->discountBadgeId)->first();
                $discBadgeDiscountPercent = $discountbadge->discBadgeDiscountPercent;
                $discountReason = 'Discount Badge Discount';

                return array($discBadgeDiscountPercent, null, null, $discountReason);
            }
            // ========discount badge=======



            // ========product customer=======
            if (isset($discprodcustgetProdDisc) and $discprodcustgetProdDisc!=null )
            {
                $percent = $discprodcustgetProdDisc->discountProductCustomerPercent;
                $amount = $discprodcustgetProdDisc->discountProductCustomerAmount;
                $expireDate = $discprodcustgetProdDisc->discountProductCustomerExpireDate;
                $discountReason = $discprodcustgetProdDisc->discountReason;
                if ($percent>0 || $amount>0)
                {
                    return array($percent, $amount, $expireDate, $discountReason);
                }
            }

            // ========product customer=======


            // ==============customer=========
            if (isset($disccustGetProdDiscCust) and $disccustGetProdDiscCust!=null )
            {
                $percent = $disccustGetProdDiscCust->discountCustomerPercent;
                $amount = $disccustGetProdDiscCust->discountCustomerAmount;
                $expireDate = $disccustGetProdDiscCust->discountCustomerExpireDate;
                $discountReason = $disccustGetProdDiscCust->discountReason;
                if ($percent>0 || $amount>0)
                {
                    return array($percent, $amount, $expireDate, $discountReason);
                }
            }
            // ==============customer=========

            // ========product =======
            if (isset($discountproductgetProdDiscProd) and $discountproductgetProdDiscProd!=null )
            {
                $percent = $discountproductgetProdDiscProd->discountProductPercent;
                $amount = $discountproductgetProdDiscProd->discountProductAmount;
                $expireDate = $discountproductgetProdDiscProd->discountProductExpireDate;
                $discountReason = $discountproductgetProdDiscProd->discountReason;
                if ($percent>0 || $amount>0)
                {
                    return array($percent, $amount, $expireDate, $discountReason);
                }
            }
            // ========product =======


            // ==============all=========
            if (isset($discountallgetProdDiscAll) and $discountallgetProdDiscAll!=null )
            {
                $percent = $discountallgetProdDiscAll->discountAllPercent;
                $amount = $discountallgetProdDiscAll->discountAllAmount;
                $expireDate = $discountallgetProdDiscAll->discountAllExpireDate;
                $discountReason = $discountallgetProdDiscAll->discountReason;
                return array($percent, $amount, $expireDate, $discountReason);
            }
            // ==============all=========

        }
        else
        {
            // ========product =======
            $discountproductgetProdDiscProdElse = Cache::rememberForever('discountproductgetProdDiscProdElse', function () {
                return DB::table('discountproduct')->get();
            });
            $discountproductgetProdDiscProdElse = $discountproductgetProdDiscProdElse
                            ->where('productId', $productId)
                            ->where('discountProductExpireDate','>=', date('Y-m-d') )->last();


            if (isset($discountproductgetProdDiscProdElse) and $discountproductgetProdDiscProdElse!=null )
            {
                $percent = $discountproductgetProdDiscProdElse->discountProductPercent;
                $amount = $discountproductgetProdDiscProdElse->discountProductAmount;
                $expireDate = $discountproductgetProdDiscProdElse->discountProductExpireDate;
                $discountReason = $discountproductgetProdDiscProdElse->discountReason;
                if ($percent>0 || $amount>0)
                {
                    return array($percent, $amount, $expireDate, $discountReason);
                }
            }
            // ========product =======


            // ==============all=========
            $discountallgetProdDiscAllElse = Cache::rememberForever('discountallgetProdDiscAllElse', function () {
                return DB::table('discountall')->get();
            });
            $discountallgetProdDiscAllElse = $discountallgetProdDiscAllElse
                            ->where('discountAllExpireDate','>=', date('Y-m-d') )->last();
            if (isset($discountallgetProdDiscAllElse) and $discountallgetProdDiscAllElse!=null )
            {
                $percent = $discountallgetProdDiscAllElse->discountAllPercent;
                $amount = $discountallgetProdDiscAllElse->discountAllAmount;
                $expireDate = $discountallgetProdDiscAllElse->discountAllExpireDate;
                $discountReason = $discountallgetProdDiscAllElse->discountReason;
                return array($percent, $amount, $expireDate, $discountReason);
            }

            // ==============all=========

        }
    }


    function getProductDiscountForManualCart($productId, $userId)
    {
        if ($userId)
        {
            // ========product customer=======
            $discprodcustgetProdDisc = Cache::rememberForever('discprodcustgetProdDisc', function () {
                return DB::table('discountproductcustomer')->get();
            });

            $percent = $discprodcustgetProdDisc
                            ->where('productId', $productId)
                            ->where('customerId',$userId->id )
                            ->where('discountProductCustomerExpireDate','>=', date('Y-m-d') )->pluck('discountProductCustomerPercent')->last();
            $amount = $discprodcustgetProdDisc
                            ->where('productId', $productId)
                            ->where('customerId',$userId->id )
                            ->where('discountProductCustomerExpireDate','>=', date('Y-m-d') )->pluck('discountProductCustomerAmount')->last();
            if ($percent>0 || $amount>0)
            {
                return array($percent, $amount);
            }
            // ========product customer=======


            // ==============customer=========
            $disccustGetProdDiscCust = Cache::rememberForever('disccustGetProdDiscCust', function () {
                return DB::table('discountcustomer')->get();
            });
            $percent = $disccustGetProdDiscCust
                            ->where('customerId',$userId->id )
                            ->where('discountCustomerExpireDate','>=', date('Y-m-d') )->pluck('discountCustomerPercent')->last();
            $amount = $disccustGetProdDiscCust
                            ->where('customerId',$userId->id )
                            ->where('discountCustomerExpireDate','>=', date('Y-m-d') )->pluck('discountCustomerAmount')->last();
            if ($percent>0 || $amount>0)
            {
                return array($percent, $amount);
            }
            // ==============customer=========

            // ========product =======
            $discountproductgetProdDiscProd = Cache::rememberForever('discountproductgetProdDiscProd', function () {
                return DB::table('discountproduct')->get();
            });
            $percent = $discountproductgetProdDiscProd
                            ->where('productId', $productId)
                            ->where('discountProductExpireDate','>=', date('Y-m-d') )->pluck('discountProductPercent')->last();
            $amount = $discountproductgetProdDiscProd
                            ->where('productId', $productId)
                            ->where('discountProductExpireDate','>=', date('Y-m-d') )->pluck('discountProductAmount')->last();
            if ($percent>0 || $amount>0)
            {
                return array($percent, $amount);
            }
            // ========product =======


            // ==============all=========
            $discountallgetProdDiscAll = Cache::rememberForever('discountallgetProdDiscAll', function () {
                return DB::table('discountall')->get();
            });
            $percent = $discountallgetProdDiscAll
                            ->where('discountAllExpireDate','>=', date('Y-m-d') )->pluck('discountAllPercent')->last();
            $amount = $discountallgetProdDiscAll
                            ->where('discountAllExpireDate','>=', date('Y-m-d') )->pluck('discountAllAmount')->last();
            return array($percent, $amount);
            // ==============all=========

        }
        else
        {
            // ========product =======
            $discountproductgetProdDiscProdElse = Cache::rememberForever('discountproductgetProdDiscProdElse', function () {
                return DB::table('discountproduct')->get();
            });
            $percent = $discountproductgetProdDiscProdElse
                            ->where('productId', $productId)
                            ->where('discountProductExpireDate','>=', date('Y-m-d') )->pluck('discountProductPercent')->last();
            $amount = $discountproductgetProdDiscProdElse
                            ->where('productId', $productId)
                            ->where('discountProductExpireDate','>=', date('Y-m-d') )->pluck('discountProductAmount')->last();
            if ($percent>0 || $amount>0)
            {
                return array($percent, $amount);
            }
            // ========product =======


            // ==============all=========
            $discountallgetProdDiscAllElse = Cache::rememberForever('discountallgetProdDiscAllElse', function () {
                return DB::table('discountall')->get();
            });
            $percent = $discountallgetProdDiscAllElse
                            ->where('discountAllExpireDate','>=', date('Y-m-d') )->pluck('discountAllPercent')->last();
            $amount = $discountallgetProdDiscAllElse
                            ->where('discountAllExpireDate','>=', date('Y-m-d') )->pluck('discountAllAmount')->last();

            return array($percent, $amount);
            // ==============all=========

        }
    }




    function cacheRemove()
    {
        try {
            Artisan::call('view:clear');
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('config:cache');
            Artisan::call('clear-compiled');
            Artisan::call('route:clear');
        } catch (\Throwable $th) {
        }
    }


    function getProductImage($productId)
    {
        $productPicturePath = DB::table('productpictures')->where('isDefault', 1)->where('productId', $productId)->pluck('productPicturePath')->first();
        return $productPicturePath;
    }




?>
