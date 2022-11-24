<!doctype html>
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> --}}
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <meta name="author" content="{{ config('app.name') }}">
    {{-- <meta http-equiv="refresh" content="1800;URL='{{ Request::fullUrl() }}'" > --}}

    <link rel="canonical" href="{{ url('/') }}">
    <link rel="alternate" href="{{ url('/') }}" hreflang="x-default"/>

    <meta property="og:title"  content="{{ config('app.name') }}" />
    <meta name="twitter:title" content="{{ config('app.name') }}">

    <meta name="description"   content="The Perfume House Bangladesh is the BD's largest specialist fragrance retailer selling all authentic & major designer fragrance brands, classic and celebrity perfumes with unrivalled expertise at value for money prices." />
    <meta property="og:description"   content="The Perfume House Bangladesh is the BD's largest specialist fragrance retailer selling all authentic & major designer fragrance brands, classic and celebrity perfumes with unrivalled expertise at value for money prices." />
    <meta name="twitter:description" content="The Perfume House Bangladesh is the BD's largest specialist fragrance retailer selling all authentic & major designer fragrance brands, classic and celebrity perfumes with unrivalled expertise at value for money prices.">

    <meta name="keywords" content="perfumehousebd, perfumehouse, perfume, bd, scent, menperfume, womenperfume, giftperfume, kidsperfume, bdperfume">
    <meta property="og:url"  content="{{ Request::fullUrl() }}" />

    <meta property="og:image"  content="{{ asset('/uploads/company/logo/company_logo.png') }}" />
    <meta name="twitter:image:src" content="{{ asset('/uploads/company/logo/company_logo.png') }}">
    <meta name="twitter:image" content="{{ asset('/uploads/company/logo/company_logo.png') }}">

    <meta property="og:type"  content="website" />
    <meta property="og:site_name"  content="{{ config('app.name') }}" />


    <meta property="og:locale" content="en_US"/>

    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@1193saifur">
    <meta name="twitter:creator" content="1193saifur">

    <meta name="copyright" content="{{ config('app.name') }}">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Perfume House BD') }}</title> --}}

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}
    <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link rel="shortcut icon" type="image/png" href="{{ asset('/uploads/company/logo/company_logo.png') }}"/>


    {{-- <meta name="google-site-verification" content="hmCibO0nqwDCIWWJBjeNx6yWn_R-wQpRFI3g15wbr_s" /> --}}

    <!-- Global site tag (gtag.js) - Google Analytics -->
    {{-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-4XJE8MRL5S"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-4XJE8MRL5S');
    </script> --}}

    <!--GEO Tags-->
    {{-- <meta name="DC.title" content="@yield('title', config('app.name'))"/>
    <meta name="geo.region" content="GB-HMF"/>
    <meta name="geo.placename" content="London"/>
    <meta name="geo.position" content="51.493272;-0.239747"/>
    <meta name="ICBM" content="51.493272, -0.239747"/> --}}

    <meta name="robots" content="noodp,nodir,noydir">
</head>
<body>
    <div id="app" >
        <maincontainer></maincontainer>
    </div>
</body>
</html>
