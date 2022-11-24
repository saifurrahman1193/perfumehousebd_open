<?php

use Illuminate\Http\Request;



Route::group([

    // 'middleware' => 'api',
    'prefix' => 'auth',

// ], function ($router) {
], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('resetPassword', 'AuthController@resetPassword')->name('resetPassword');
    Route::post('dynamicChangepasswordFromEncryption', 'AuthController@dynamicChangepasswordFromEncryption')->name('dynamicChangepasswordFromEncryption');


    Route::post('loginAdmin', 'AuthController@loginAdmin')->name('loginAdmin');
    Route::get('getCurrentUserRoles', 'AuthController@getCurrentUserRoles')->name('getCurrentUserRoles');
    Route::get('getCurrentUserModules', 'AuthController@getCurrentUserModules')->name('getCurrentUserModules');

    Route::post('logout', 'AuthController@logout')->name('api.logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::get('getUserWithToken', 'AuthController@getUserWithToken');
    Route::post('meAdmin', 'AuthController@meAdmin');
    Route::post('meAdminWithUserModules', 'AuthController@meAdminWithUserModules');
    Route::post('payload', 'AuthController@payload');
    Route::post('/changePassword', 'AuthController@changePassword');
});



// super admin
Route::group([

    'middleware' => 'auth:api',
    'namespace' => 'Backend'

], function () {

    // users
    Route::get('getUsers', 'SuperAdminController@getUsers')->name('getUsers');
    Route::get('getUsersView', 'SuperAdminController@getUsersView')->name('getUsersView');
    Route::get('getUsersWithRoles', 'SuperAdminController@getUsersWithRoles')->name('getUsersWithRoles');

    Route::get('getUser/{userId}', 'SuperAdminController@getUser')->name('getUser');
    Route::post('addUser', 'SuperAdminController@addUser')->name('addUser');
    Route::post('editUser', 'SuperAdminController@editUser')->name('editUser');
    Route::post('deleteUser/{userId}', 'SuperAdminController@deleteUser')->name('deleteUser');

    // roles
    Route::get('getRoles', 'SuperAdminController@getRoles')->name('getRoles');
    Route::get('getRole/{roleId}', 'SuperAdminController@getRole')->name('getRole');
    Route::post('addRole', 'SuperAdminController@addRole')->name('addRole');
    Route::post('editRole', 'SuperAdminController@editRole')->name('editRole');
    Route::post('deleteRole/{roleId}', 'SuperAdminController@deleteRole')->name('deleteRole');

    // modules
    Route::get('/getModules', 'SuperAdminController@getModules')->name('getModules');
    Route::get('/getModule/{moduleId}', 'SuperAdminController@getModule')->name('getModule');
    Route::get('/getRoleModules', 'SuperAdminController@getRoleModules')->name('getRoleModules');

    // backups
    Route::post('storageBackup', 'SuperAdminController@storageBackup')->name('storageBackup');
    Route::post('storageBackupDelete', 'SuperAdminController@storageBackupDelete')->name('storageBackupDelete');
    Route::post('serverDBBackup', 'SuperAdminController@serverDBBackup')->name('serverDBBackup');
    Route::post('dbBackupDelete', 'SuperAdminController@dbBackupDelete')->name('dbBackupDelete');


    // sitemap
    Route::get('/getSitemaps', 'SitemapController@getSitemaps')->name('getSitemaps');
    Route::get('/getSitemap/{id}', 'SitemapController@getSitemap')->name('getSitemap');
    Route::post('/addSitemap', 'SitemapController@addSitemap')->name('addSitemap');
    Route::post('/editSitemap', 'SitemapController@editSitemap')->name('editSitemap');
    Route::post('/deleteSitemap/{id}', 'SitemapController@deleteSitemap')->name('deleteSitemap');

    Route::get('/generateSitemap', 'SitemapController@generateSitemap')->name('generateSitemap');


});




// chat
Route::group([

    'middleware' => 'auth:api',
    'namespace' => 'Backend'

], function () {
    Route::get('getContacts', 'ChatController@getContacts')->name('getContacts');
    Route::get('getAdminContacts', 'ChatController@getAdminContacts')->name('getAdminContacts');
    Route::get('getMessages', 'ChatController@getMessages')->name('getMessages');
    Route::get('getAContactMessages/{userId1}/{userId2}', 'ChatController@getAContactMessages')->name('getAContactMessages');
    Route::post('sendMessage', 'ChatController@sendMessage')->name('sendMessage');


});
Route::get('getMongodbMessages', 'Backend\ChatController@getMongodbMessages');





Route::group([
    'middleware' => 'auth:api',
    'namespace' => 'Frontend'
], function () {
    Route::post('/profileUpdate', 'HomeController@profileUpdate')->name('profileUpdate');

    // services
    Route::post('/addService', 'HomeController@addService')->name('addService');
    Route::post('/editService', 'HomeController@editService')->name('editService');
    Route::post('/deleteService/{serviceId}', 'HomeController@deleteService')->name('deleteService');

    // delivery address
    Route::get('/getDeliveryAddresses', 'DeliveryController@getDeliveryAddresses')->name('getDeliveryAddresses');
    Route::get('/getCurrentUserDeliveryAddresses', 'DeliveryController@getCurrentUserDeliveryAddresses')->name('getCurrentUserDeliveryAddresses');
    Route::get('/getDeliveryAddress/{deliveryAddressId}', 'DeliveryController@getDeliveryAddress')->name('getDeliveryAddress');
    Route::post('/addCurrentUserDeliveryAddress', 'DeliveryController@addCurrentUserDeliveryAddress')->name('addCurrentUserDeliveryAddress');
    Route::post('/editDeliveryAddress', 'DeliveryController@editDeliveryAddress')->name('editDeliveryAddress');
    Route::post('/deleteDeliveryAddress/{deliveryAddressId}', 'DeliveryController@deleteDeliveryAddress')->name('deleteDeliveryAddress');
});


// products

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getProducts', 'ProductController@getProducts')->name('getProducts');
    Route::post('/getProductsWithProductIds', 'ProductController@getProductsWithProductIds')->name('getProductsWithProductIds');

    Route::get('/getRandomProducts/{numberOfProductReturn}', 'ProductController@getRandomProducts')->name('getRandomProducts');
    Route::get('/getLastNNumberProducts/{numberOfProductReturn}/{rangeStart?}', 'ProductController@getLastNNumberProducts')->name('getLastNNumberProducts');

    Route::get('/getRelatedProducts', 'ProductController@getRelatedProducts')->name('getRelatedProducts');
    Route::get('/getProductPictures', 'ProductController@getProductPictures')->name('getProductPictures');
    Route::get('/getSingleProductPictures/{productId}', 'ProductController@getSingleProductPictures')->name('getSingleProductPictures');
    Route::post('/getProductPicturesWithProductIds', 'ProductController@getProductPicturesWithProductIds')->name('getProductPicturesWithProductIds');
    Route::get('/getProduct/{productId}', 'ProductController@getProduct')->name('getProduct');
    Route::get('/getProductAllData/{productId}', 'ProductController@getProductAllData')->name('getProductAllData');
    Route::get('/getFeaturedProducts', 'ProductController@getFeaturedProducts')->name('getFeaturedProducts');
    Route::get('/getLimitedEditions', 'ProductController@getLimitedEditions')->name('getLimitedEditions');
    Route::get('/getNewArrivals', 'ProductController@getNewArrivals')->name('getNewArrivals');

    Route::get('/getProductCategories', 'ProductController@getProductCategories')->name('getProductCategories');


    Route::get('/getLatestProducts', 'ProductController@getLatestProducts')->name('getLatestProducts');
    Route::get('/getBestsellingProducts', 'ProductController@getBestsellingProducts')->name('getBestsellingProducts');
    Route::get('/getTopratedProducts', 'ProductController@getTopratedProducts')->name('getTopratedProducts');

    Route::get('/getGiftProducts', 'ProductController@getGiftProducts')->name('getGiftProducts');



    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/addProduct', 'ProductController@addProduct')->name('addProduct');
        Route::post('/addProductPicture', 'ProductController@addProductPicture')->name('addProductPicture');
        Route::post('/editProduct', 'ProductController@editProduct')->name('editProduct');
        Route::post('/deleteProduct/{productId}', 'ProductController@deleteProduct')->name('deleteProduct');
        Route::post('/deleteProductPicture/{productPictureId}', 'ProductController@deleteProductPicture')->name('deleteProductPicture');
        Route::post('/setProductPictureDefault/{productPictureId}', 'ProductController@setProductPictureDefault')->name('setProductPictureDefault');

        Route::post('/addFeaturedProduct', 'ProductController@addFeaturedProduct')->name('addFeaturedProduct');
        Route::post('/deleteFeaturedProduct/{featuredProductId}', 'ProductController@deleteFeaturedProduct')->name('deleteFeaturedProduct');
        Route::post('/deleteFeaturedProductWithProductId/{productId}', 'ProductController@deleteFeaturedProductWithProductId')->name('deleteFeaturedProductWithProductId');



        Route::post('/addLatestProduct', 'ProductController@addLatestProduct')->name('addLatestProduct');
        Route::post('/deleteLatestProduct/{latestProductId}', 'ProductController@deleteLatestProduct')->name('deleteLatestProduct');

        Route::post('/addBestsellingProduct', 'ProductController@addBestsellingProduct')->name('addBestsellingProduct');
        Route::post('/deleteBestsellingProduct/{bestsellingProductId}', 'ProductController@deleteBestsellingProduct')->name('deleteBestsellingProduct');

        Route::post('/addTopratedProduct', 'ProductController@addTopratedProduct')->name('addTopratedProduct');
        Route::post('/deleteTopratedProduct/{topratedProductId}', 'ProductController@deleteTopratedProduct')->name('deleteTopratedProduct');



        Route::post('/addLimitedEdition', 'ProductController@addLimitedEdition')->name('addLimitedEdition');
        Route::post('/deleteLimitedEdition/{limitedEditionId}', 'ProductController@deleteLimitedEdition')->name('deleteLimitedEdition');

        Route::post('/addNewArrival', 'ProductController@addNewArrival')->name('addNewArrival');
        Route::post('/deleteNewArrival/{newArrivalId}', 'ProductController@deleteNewArrival')->name('deleteNewArrival');
        Route::get('/verifiedReviewer/{productId}', 'ProductController@verifiedReviewer')->name('verifiedReviewer');


    });


});


// product types

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getProductTypes', 'ProductTypeController@getProductTypes')->name('getProductTypes');
    Route::get('/getProductType/{productTypeId}', 'ProductTypeController@getProductType')->name('getProductType');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/addProductType', 'ProductTypeController@addProductType')->name('addProductType');
        Route::post('/editProductType', 'ProductTypeController@editProductType')->name('editProductType');
        Route::post('/deleteProductType/{productTypeId}', 'ProductTypeController@deleteProductType')->name('deleteProductType');
    });
});

// skin types

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getSkinTypes', 'SkinTypeController@getSkinTypes')->name('getSkinTypes');
    Route::get('/getSkinType/{skinTypeId}', 'SkinTypeController@getSkinType')->name('getSkinType');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/addSkinType', 'SkinTypeController@addSkinType')->name('addSkinType');
        Route::post('/editSkinType', 'SkinTypeController@editSkinType')->name('editSkinType');
        Route::post('/deleteSkinType/{skinTypeId}', 'SkinTypeController@deleteSkinType')->name('deleteSkinType');
    });
});



// Application areas

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getApplicationAreas', 'ApplicationAreaController@getApplicationAreas')->name('getApplicationAreas');
    Route::get('/getApplicationArea/{applicationAreaId}', 'ApplicationAreaController@getApplicationArea')->name('getApplicationArea');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/addApplicationArea', 'ApplicationAreaController@addApplicationArea')->name('addApplicationArea');
        Route::post('/editApplicationArea', 'ApplicationAreaController@editApplicationArea')->name('editApplicationArea');
        Route::post('/deleteApplicationArea/{applicationAreaId}', 'ApplicationAreaController@deleteApplicationArea')->name('deleteApplicationArea');
    });
});

// Gender

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getGenders', 'GenderController@getGenders')->name('getGenders');
    Route::get('/getGender/{genderId}', 'GenderController@getGender')->name('getGender');
});

// Formulation

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getFormulations', 'FormulationController@getFormulations')->name('getFormulations');
    Route::get('/getFormulation/{formulationId}', 'FormulationController@getFormulation')->name('getFormulation');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/addFormulation', 'FormulationController@addFormulation')->name('addFormulation');
        Route::post('/editFormulation', 'FormulationController@editFormulation')->name('editFormulation');
        Route::post('/deleteFormulation/{formulationId}', 'FormulationController@deleteFormulation')->name('deleteFormulation');
    });
});



// categories

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getCategories', 'CategoryController@getCategories')->name('getCategories');
    Route::get('/getCategory/{categoryId}', 'CategoryController@getCategory')->name('getCategory');
    Route::get('/getCategoryWithSlug/{categorySlug}', 'CategoryController@getCategoryWithSlug')->name('getCategoryWithSlug');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/addCategory', 'CategoryController@addCategory')->name('addCategory');
        Route::post('/editCategory', 'CategoryController@editCategory')->name('editCategory');
        Route::post('/deleteCategory/{categoryId}', 'CategoryController@deleteCategory')->name('deleteCategory');
    });
});


// uom

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getUoms', 'UomController@getUoms')->name('getUoms');
    Route::get('/getUom/{uomId}', 'UomController@getUom')->name('getUom');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/addUom', 'UomController@addUom')->name('addUom');
        Route::post('/editUom', 'UomController@editUom')->name('editUom');
        Route::post('/deleteUom/{uomId}', 'UomController@deleteUom')->name('deleteUom');
    });
});


// brands

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getBrands', 'BrandController@getBrands')->name('getBrands');
    Route::get('/getBrand/{brandId}', 'BrandController@getBrand')->name('getBrand');
    Route::get('/getBrandWithSlug/{brandSlug}', 'BrandController@getBrandWithSlug')->name('getBrandWithSlug');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/addBrand', 'BrandController@addBrand')->name('addBrand');
        Route::post('/editBrand', 'BrandController@editBrand')->name('editBrand');
        Route::post('/deleteBrand/{brandId}', 'BrandController@deleteBrand')->name('deleteBrand');
    });
});



// gift types

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getGiftTypes', 'GiftTypeController@getGiftTypes')->name('getGiftTypes');
    Route::get('/getGiftType/{giftTypeId}', 'GiftTypeController@getGiftType')->name('getGiftType');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/addGiftType', 'GiftTypeController@addGiftType')->name('addGiftType');
        Route::post('/editGiftType', 'GiftTypeController@editGiftType')->name('editGiftType');
        Route::post('/deleteGiftType/{giftTypeId}', 'GiftTypeController@deleteGiftType')->name('deleteGiftType');
    });
});



// advertisement slider

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getAdvertisementSliders', 'AdvertisementSliderController@getAdvertisementSliders')->name('getAdvertisementSliders');
    Route::get('/getAdvertisementSlider/{advertisementSliderId}', 'AdvertisementSliderController@getAdvertisementSlider')->name('getAdvertisementSlider');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/addAdvertisementSlider', 'AdvertisementSliderController@addAdvertisementSlider')->name('addAdvertisementSlider');
        Route::post('/deleteAdvertisementSlider/{advertisementSliderId}', 'AdvertisementSliderController@deleteAdvertisementSlider')->name('deleteAdvertisementSlider');
    });
});


// country

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getCountries', 'CountryController@getCountries')->name('getCountries');
    Route::get('/getCountry/{countryId}', 'CountryController@getCountry')->name('getCountry');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/addCountry', 'CountryController@addCountry')->name('addCountry');
        Route::post('/editCountry', 'CountryController@editCountry')->name('editCountry');
        Route::post('/deleteCountry/{countryId}', 'CountryController@deleteCountry')->name('deleteCountry');
    });
});

// suppliers

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getSuppliers', 'SupplierController@getSuppliers')->name('getSuppliers');
    Route::get('/getSupplier/{supplierId}', 'SupplierController@getSupplier')->name('getSupplier');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/addSupplier', 'SupplierController@addSupplier')->name('addSupplier');
        Route::post('/editSupplier', 'SupplierController@editSupplier')->name('editSupplier');
        Route::post('/deleteSupplier/{supplierId}', 'SupplierController@deleteSupplier')->name('deleteSupplier');
    });
});


// areas

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getAreas', 'AreaController@getAreas')->name('getAreas');
    Route::get('/getArea/{areaId}', 'AreaController@getArea')->name('getArea');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/addArea', 'AreaController@addArea')->name('addArea');
        Route::post('/editArea', 'AreaController@editArea')->name('editArea');
        Route::post('/deleteArea/{areaId}', 'AreaController@deleteArea')->name('deleteArea');
    });
});


//  batch product
Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getBatchProducts', 'BatchController@getBatchProducts')->name('getBatchProducts');
    Route::get('/getBatchProduct/{batchProductId}', 'BatchController@getBatchProduct');

    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/addBatchProduct', 'BatchController@addBatchProduct')->name('addBatchProduct');
        Route::post('/editBatchProduct', 'BatchController@editBatchProduct')->name('editBatchProduct');
        Route::post('/deleteBatchProduct/{batchProductId}', 'BatchController@deleteBatchProduct')->name('deleteBatchProduct');
    });
});


//  delivery settings
Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getDeliverySetting', 'DeliveryController@getDeliverySetting')->name('getDeliverySetting');

    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/editDeliverySetting', 'DeliveryController@editDeliverySetting')->name('editDeliverySetting');
        Route::get('/getAUserDeliveryAddresses/{userId}', 'DeliveryController@getAUserDeliveryAddresses')->name('getAUserDeliveryAddresses');
        Route::post('/addAUserDeliveryAddress/{userId}', 'DeliveryController@addAUserDeliveryAddress')->name('addAUserDeliveryAddress');


    });
});




//  carts
Route::group([
    'namespace' => 'Frontend'
], function () {

    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::get('/getCarts', 'OrderController@getCarts');
        Route::get('/getCartsWithUser/{userId}', 'OrderController@getCartsWithUser');
        Route::get('/getCartDetails', 'OrderController@getCartDetails');
    });
});



// reviews related
Route::group([
    'namespace' => 'Backend'
    // 'middleware' => 'auth:api',
], function () {
    Route::get('/getAllReviews', 'ReviewController@getAllReviews');
    Route::get('/getProductReviews/{productId}', 'ReviewController@getProductReviews');
    Route::get('/getProductReviewsWithPaginate/{productId}', 'ReviewController@getProductReviewsWithPaginate');


    Route::post('/addReview', 'ReviewController@addReview');
});


// discountsettings

Route::group([
    'namespace' => 'Backend'
], function () {
    // discount all
    Route::get('/getDiscountAlls', 'DiscountController@getDiscountAlls');
    Route::get('/getDiscountAll/{discountAllId}', 'DiscountController@getDiscountAll');

    // discount product
    Route::get('/getDiscountProducts', 'DiscountController@getDiscountProducts');
    Route::get('/getDiscountProduct/{discountProductId}', 'DiscountController@getDiscountProduct');

    // discount customer
    Route::get('/getDiscountCustomers', 'DiscountController@getDiscountCustomers')->name('getDiscountCustomers');
    Route::get('/getDiscountCustomer/{discountCustomerId}', 'DiscountController@getDiscountCustomer')->name('getDiscountCustomer');

    // discount product customer
    Route::get('/getDiscountProductCustomers', 'DiscountController@getDiscountProductCustomers')->name('getDiscountProductCustomers');
    Route::get('/getDiscountProductCustomer/{discountProductCustomerId}', 'DiscountController@getDiscountProductCustomer')->name('getDiscountProductCustomer');


    Route::post('/getDiscountCalculation', 'DiscountController@getDiscountCalculation')->name('getDiscountCalculation');
    Route::post('/getDiscountCalculationforManualCart/{customerId}', 'DiscountController@getDiscountCalculationforManualCart')->name('getDiscountCalculationforManualCart');
    Route::post('/getProductsWithDiscountCalculation', 'DiscountController@getProductsWithDiscountCalculation')->name('getProductsWithDiscountCalculation');

    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        // discount all
        Route::post('/addDiscountAll', 'DiscountController@addDiscountAll')->name('addDiscountAll');
        Route::post('/editDiscountAll', 'DiscountController@editDiscountAll')->name('editDiscountAll');
        Route::post('/deleteDiscountAll/{discountAllId}', 'DiscountController@deleteDiscountAll')->name('deleteDiscountAll');

        // discount product
        Route::post('/addDiscountProduct', 'DiscountController@addDiscountProduct')->name('addDiscountProduct');
        Route::post('/editDiscountProduct', 'DiscountController@editDiscountProduct')->name('editDiscountProduct');
        Route::post('/deleteDiscountProduct/{discountProductId}', 'DiscountController@deleteDiscountProduct')->name('deleteDiscountProduct');

        // discount product
        Route::post('/addDiscountCustomer', 'DiscountController@addDiscountCustomer')->name('addDiscountCustomer');
        Route::post('/editDiscountCustomer', 'DiscountController@editDiscountCustomer')->name('editDiscountCustomer');
        Route::post('/deleteDiscountCustomer/{discountCustomerId}', 'DiscountController@deleteDiscountCustomer')->name('deleteDiscountCustomer');

        // discount product customer
        Route::post('/addDiscountProductCustomer', 'DiscountController@addDiscountProductCustomer')->name('addDiscountProductCustomer');
        Route::post('/editDiscountProductCustomer', 'DiscountController@editDiscountProductCustomer')->name('editDiscountProductCustomer');
        Route::post('/deleteDiscountProductCustomer/{discountProductCustomerId}', 'DiscountController@deleteDiscountProductCustomer')->name('deleteDiscountProductCustomer');

    });
});


// Discount Badge

Route::group([
    'namespace' => 'Backend'
], function () {
    // discount badge
    Route::get('/getDiscountBadges', 'DiscountBadgeController@getDiscountBadges');
    Route::get('/getDiscountBadge/{discountBadgeId}', 'DiscountBadgeController@getDiscountBadge');

    // discount point
    Route::get('/getDiscountPoint', 'DiscountBadgeController@getDiscountPoint');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        // discount badge
        Route::post('/addDiscountBadge', 'DiscountBadgeController@addDiscountBadge');
        Route::post('/editDiscountBadge', 'DiscountBadgeController@editDiscountBadge');
        Route::post('/deleteDiscountBadge/{discountBadgeId}', 'DiscountBadgeController@deleteDiscountBadge');


        // discount point
        Route::post('/discountPointUpdate', 'DiscountBadgeController@discountPointUpdate');


        // frontend
        Route::post('/enableCustToHoldBadgeDiscount', 'DiscountBadgeController@enableCustToHoldBadgeDiscount');

        // discount badge uses
        Route::get('/getTotAvailValidDiscBadgePointsForCU', 'DiscountBadgeController@getTotAvailValidDiscBadgePointsForCU');


    });
});


// payment settings

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getPaymentMethods', 'PaymentMethodController@getPaymentMethods')->name('getPaymentMethods');
    Route::get('/getPaymentMethod/{paymentMethodId}', 'PaymentMethodController@getPaymentMethod')->name('getPaymentMethod');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/addPaymentMethod', 'PaymentMethodController@addPaymentMethod')->name('addPaymentMethod');
        Route::post('/editPaymentMethod', 'PaymentMethodController@editPaymentMethod')->name('editPaymentMethod');
        Route::post('/deletePaymentMethod/{paymentMethodId}', 'PaymentMethodController@deletePaymentMethod')->name('deletePaymentMethod');
    });
});

// transactions

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::get('/getTransaction/{transactionId}', 'TransactionController@getTransaction')->name('getTransaction');
        Route::get('/getTransactions', 'TransactionController@getTransactions')->name('getTransactions');
        Route::post('/addTransaction', 'TransactionController@addTransaction')->name('addTransaction');
        Route::post('/editTransaction', 'TransactionController@editTransaction')->name('editTransaction');
        Route::post('/deleteTransaction/{transactionId}', 'TransactionController@deleteTransaction')->name('deleteTransaction');
    });
});



// reports related

Route::group([
    'namespace' => 'Backend'
], function () {

    Route::post('/addProductVisit', 'ProductController@addProductVisit')->name('addProductVisit');

    Route::post('/addWishlistall', 'ProductController@addWishlistall')->name('addWishlistall');
    Route::post('/addComparelist', 'ProductController@addComparelist')->name('addComparelist');

    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::get('/getProductVisits', 'ReportController@getProductVisits');
        Route::get('/getCurrentUserProductVisits', 'ProductController@getCurrentUserProductVisits');
        Route::get('/getYearlyProductVisitsReport/{productId?}', 'ReportController@getYearlyProductVisitsReport');
        Route::get('/getPorductVisitsYears/{productId?}', 'ReportController@getPorductVisitsYears');
        Route::get('/getMonthlyProductVisitsReport/{year}/{productId?}', 'ReportController@getMonthlyProductVisitsReport');
        Route::get('/getProductVisitsMonths/{year}/{productId?}', 'ReportController@getProductVisitsMonths');
        Route::get('/getDailyProductVisitsReport/{year}/{monthNumber}/{productId?}', 'ReportController@getDailyProductVisitsReport');


        // customer product visit report
        Route::get('/getCustProductVisitReportCategories', 'ReportController@getCustProductVisitReportCategories');
        Route::post('/getCustProductVisitReport', 'ReportController@getCustProductVisitReport');
        Route::get('/customerproductvisitExcelReportDownload', 'ReportController@customerproductvisitExcelReportDownload');


        Route::get('/getWishlistall', 'ReportController@getWishlistall');
        Route::get('/getComparelist', 'ReportController@getComparelist');
        Route::get('/getMonthlySalesReport', 'ReportController@getMonthlySalesReport');
        Route::get('/getMonthlySalesReport/{year}', 'ReportController@getMonthlySalesReport');
        Route::get('/getCartYears', 'ReportController@getCartYears');
        Route::get('/getYearlySalesReport', 'ReportController@getYearlySalesReport');
        Route::get('/getCartMonths/{year}', 'ReportController@getCartMonths');
        Route::get('/getDailySalesReport/{year}/{monthNumber}', 'ReportController@getDailySalesReport');

        Route::get('/getValuableCustomers', 'ReportController@getValuableCustomers');

        Route::get('/stockReportFullExcelReportDownload', 'ReportController@stockReportFullExcelReportDownload');


        Route::get('/getDmySalesReportData', 'ReportController@getDmySalesReportData');
        Route::get('/getCartYearMonths', 'ReportController@getCartYearMonths');

    });
});

// dashboard
Route::group([
    'namespace' => 'Backend'
], function () {

    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::get('/getSalesDashboardReport', 'DashboardController@getSalesDashboardReport');
    });
});


// =============================front end==================================
// =============================front end==================================
Route::group([
    // 'middleware' => 'auth:api',
    'namespace' => 'Frontend'
], function () {
    Route::get('/getService/{serviceId}', 'HomeController@getService')->name('getService');
    Route::get('/getServices', 'HomeController@getServices')->name('getServices');
    Route::get('/getSubServices/{serviceId}', 'HomeController@getSubServices')->name('getSubServices');
    Route::get('/getSubService/{subServiceId}', 'HomeController@getSubService')->name('getSubService');
    // Route::get('/getProducts/{subServiceId}', 'HomeController@getProducts')->name('getProducts');

    Route::get('/getPackages/{subServiceId}', 'HomeController@getPackages')->name('getPackages');
    // Route::get('/getAdvertisementSliders', 'HomeController@getAdvertisementSliders')->name('getAdvertisementSliders');

    Route::get('/getCompanyInfo', 'HomeController@getCompanyInfo')->name('getCompanyInfo');
    Route::post('/updateCompanyInfo', 'HomeController@updateCompanyInfo')->name('updateCompanyInfo');
    // Route::get('/getAreas', 'HomeController@getAreas')->name('getAreas');

    // search
    Route::get('/getSearchProducts/{searchInput}', 'SearchController@getSearchProducts');
    Route::post('/getSearchProductsWithDiscountCalculationWP/{searchInput}', 'SearchController@getSearchProductsWithDiscountCalculationWP');
    Route::post('/getSearchProductsWithDiscountCalculation/{searchInput}', 'SearchController@getSearchProductsWithDiscountCalculation');
    Route::get('/getSearchCategoryProducts/{category}', 'SearchController@getSearchCategoryProducts');
    Route::post('/getSearchCategoryProductsWithDiscountCalculation/{category}', 'SearchController@getSearchCategoryProductsWithDiscountCalculation');
    Route::post('/getSearchCategoryProductsWithDiscountCalculationWP/{categorySlug}', 'SearchController@getSearchCategoryProductsWithDiscountCalculationWP');
    Route::post('/getSearchBrandProductsWithDiscountCalculationWP/{brandSlug}', 'SearchController@getSearchBrandProductsWithDiscountCalculationWP');
    Route::post('/getSearchLimitedEditionProductsWithDiscountCalculationWP/{limitedEditionSlug}', 'SearchController@getSearchLimitedEditionProductsWithDiscountCalculationWP');
    Route::post('/getSearchNewArrivalProductsWithDiscountCalculationWP/{newArrivalSlug}', 'SearchController@getSearchNewArrivalProductsWithDiscountCalculationWP');
    Route::post('/getSearchGiftTypeProductsWithDiscountCalculationWP/{giftTypeSlug}', 'SearchController@getSearchGiftTypeProductsWithDiscountCalculationWP');





    Route::group([
        'middleware' => 'auth:api',
    ], function () {

        Route::get('/getCurrentUserWishLists', 'WishListController@getCurrentUserWishLists')->name('getCurrentUserWishLists');
        Route::post('/addCurrentUserProductToWishlist/{productId}', 'WishListController@addCurrentUserProductToWishlist')->name('addCurrentUserProductToWishlist');
        Route::post('/removeCurrentUserProductFromWishlist/{productId}', 'WishListController@removeCurrentUserProductFromWishlist')->name('removeCurrentUserProductFromWishlist');

    });


    // order related
    Route::group([
        'middleware' => 'auth:api',
    ], function () {

        Route::post('/confirmOrder', 'OrderController@confirmOrder')->name('confirmOrder');
        Route::post('/confirmOrderForManualCart', 'OrderController@confirmOrderForManualCart');

    });

    // orderhistory related
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::get('/getCurrentUserCarts', 'OrderController@getCurrentUserCarts')->name('getCurrentUserCarts');
        Route::get('/getCurrentUserCart/{cartId}', 'OrderController@getCurrentUserCart')->name('getCurrentUserCart');

        Route::get('/getCurrentUserCartDetails', 'OrderController@getCurrentUserCartDetails')->name('getCurrentUserCartDetails');
        Route::post('/cusotomerGivingCartRating', 'OrderController@cusotomerGivingCartRating')->name('cusotomerGivingCartRating');

        Route::post('/cartUpdate', 'OrderController@cartUpdate')->name('cartUpdate');

    });



});



// in admin frontend group -> pages

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getPages', 'PageController@getPages')->name('getPages');
    Route::get('/getPage/{pageId}', 'PageController@getPage')->name('getPage');
    Route::get('/getPageWithSlug/{pageSlug}', 'PageController@getPageWithSlug')->name('getPageWithSlug');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/editPage', 'PageController@editPage')->name('editPage');
    });
});


// in admin frontend group -> seo

Route::group([
    'namespace' => 'Backend'
], function () {
    Route::get('/getSeoDefault', 'SEOController@getSeoDefault')->name('getSeoDefault');
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::post('/editSeoDefault', 'SEOController@editSeoDefault')->name('editSeoDefault');
    });
});





Route::group([
    'middleware' => 'auth:api',
    'namespace' => 'Backend'
], function () {
    Route::get('printInvoice/{cartId}', 'PDFController@printInvoice')->name('printInvoice');
});


Route::post('cacheRemove', 'Backend\CacheController@cacheRemove');





// notifications
Route::group([
    'namespace' => 'Backend'
], function () {
    Route::group([
        'middleware' => 'auth:api',
    ], function () {
        Route::get('/getAdminNotifications', 'NotificationController@getAdminNotifications');
        Route::post('/setAdminNotificationsReadAt/{notificationId}', 'NotificationController@setAdminNotificationsReadAt');
    });
});









// testing
Route::group([
    'namespace' => 'Backend'
], function () {

    Route::get('getTestTrait', 'TestController@getTestTrait');

    Route::post('getUsers', 'TestController@getUsers');
    Route::post('getRoles', 'TestController@getRoles');
});




// image caching
Route::get('getImage', 'Backend\CacheController@getImage')->name('getImage');
Route::get('testOneToOneRelationsShips', 'Backend\TestController@testOneToOneRelationsShips');
Route::get('testOneToManyRelationsShips', 'Backend\TestController@testOneToManyRelationsShips');
Route::get('testManyToManyRelationsShips', 'Backend\TestController@testManyToManyRelationsShips');

Route::get('testAccessor', 'Backend\TestController@testAccessor');
