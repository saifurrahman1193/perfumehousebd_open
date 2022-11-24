// frontend
import home_f from "./frontend/components/home.vue";
import brands_f from "./frontend/components/brands.vue";
import login_f from "./frontend/components/login.vue";
import register_f from "./frontend/components/register.vue";
import profilecontainer_f from "./frontend/components/profilecontainer.vue";
import changepasswordcontainer_f from "./frontend/components/changepasswordcontainer.vue";
import checkout_f from "./frontend/components/checkout.vue";
import payment_f from "./frontend/components/payment.vue";
import productdetails_f from "./frontend/components/productdetails.vue";
import productlist_f from "./frontend/components/productlist.vue";
import orderhistory_f from "./frontend/components/orderhistory.vue";
import wishlist_f from "./frontend/components/wishlist.vue";
import comparelist_f from "./frontend/components/comparelist.vue";
import pages_f from "./frontend/components/pages.vue";
import resetpassword_f from "./frontend/components/resetpassword.vue";
import dynamicChangepasswordFromEncryption_f from "./frontend/components/dynamicChangepasswordFromEncryption.vue";
import chat_f from "./frontend/components/chat.vue";

// backend
import login_b from "./backend/components/login.vue";
import dashboard_b from "./backend/components/dashboard.vue";
import services_b from "./backend/components/services.vue";
import changepasswordcontainer_b from "./backend/components/changepasswordcontainer.vue";
import profilecontainer_b from "./backend/components/profilecontainer.vue";
import categories_b from "./backend/components/categories.vue";
import uoms_b from "./backend/components/uoms.vue";
import brands_b from "./backend/components/brands.vue";
import producttypes_b from "./backend/components/producttypes.vue";
import country_b from "./backend/components/country.vue";
import areas_b from "./backend/components/areas.vue";
import suppliers_b from "./backend/components/suppliers.vue";
import products_b from "./backend/components/products.vue";
import skintypes_b from "./backend/components/skintypes.vue";
import applicationareas_b from "./backend/components/applicationareas.vue";
import formulation_b from "./backend/components/formulation.vue";
import featuredproducts_b from "./backend/components/featuredproducts.vue";

import latestproducts_b from "./backend/components/latestproducts.vue";
import bestsellingproducts_b from "./backend/components/bestsellingproducts.vue";
import toprated_b from "./backend/components/toprated.vue";
import gifts_b from "./backend/components/gifts.vue";

import limitededitions_b from "./backend/components/limitededitions.vue";
import newarrivals_b from "./backend/components/newarrivals.vue";
import advertisementslider_b from "./backend/components/advertisementslider.vue";
import batch_b from "./backend/components/batch.vue";
import deliverysettings_b from "./backend/components/deliverysettings.vue";
import carts_b from "./backend/components/carts.vue";
import manualcart_b from "./backend/components/manualcart.vue";
import frontend_b from "./backend/components/frontend.vue";
import company_b from "./backend/components/company.vue";
import discount_settings_b from "./backend/components/discountsettings.vue";
import discount_badges_b from "./backend/components/discountbadges.vue";
import paymentsettings_b from "./backend/components/paymentsettings.vue";
import pages_b from "./backend/components/pages.vue";

import accesscontrol_b from "./backend/components/superadmin/accesscontrol.vue";
import sitemap_generator_b from "./backend/components/superadmin/sitemap_generator.vue";

import reports_b from "./backend/components/report/reports.vue";
import productvisit_b from "./backend/components/report/productvisit.vue";
import customerproductvisit_b from "./backend/components/report/customerproductvisit.vue";
import wishlistall_b from "./backend/components/report/wishlistall.vue";
import comparelist_b from "./backend/components/report/comparelist.vue";
import lowstockreport_b from "./backend/components/report/lowstockreport.vue";
import salesreport_b from "./backend/components/report/salesreport.vue";
import valuablecustomers_b from "./backend/components/report/valuablecustomers.vue";
import dmy_sales_report_b from "./backend/components/report/dmy_sales_report.vue";

import chat_b from "./backend/components/chat/chat.vue";

// errors
import notfound from "./errors/notfound.vue";


export const routes = [{
        path: '/',
        name: 'home',
        component: home_f
    },
    {
        path: '/brands',
        name: 'brands',
        component: brands_f
    },


    {
        path: '/login',
        name: 'login',
        component: login_f
    },
    {
        path: '/register',
        name: 'register',
        component: register_f
    },

    {
        path: '/changepassword',
        name: 'changepassword',
        component: changepasswordcontainer_f,
        meta: { requiresLogin: true }
    },
    {
        path: '/profile',
        name: 'profile',
        component: profilecontainer_f,
        meta: { requiresLogin: true }
    },
    {
        path: '/checkout',
        name: 'checkout',
        component: checkout_f,
        meta: { requiresLogin: true }
    },
    {
        path: '/payment/:cartId',
        name: 'payment',
        component: payment_f,
        meta: { requiresLogin: true }
    },
    {
        path: '/productdetails/:productName/:productId',
        name: 'productdetails',
        component: productdetails_f,
    },
    {
        path: '/productlist',
        name: 'productlist',
        component: productlist_f,
    },
    {
        path: '/orderhistory',
        name: 'orderhistory',
        component: orderhistory_f,
        meta: { requiresLogin: true }
    },
    {
        path: '/wishlist',
        name: 'wishlist',
        component: wishlist_f,
        meta: { requiresLogin: true }
    },
    {
        path: '/comparelist',
        name: 'comparelist',
        component: comparelist_f,
    },
    {
        path: '/pages/:pageSlug',
        name: 'pages',
        component: pages_f,
    },
    {
        path: '/resetpassword',
        name: 'resetpassword',
        component: resetpassword_f,
    },
    {
        path: '/dynamicChangepasswordFromEncryption/:email/:password',
        name: 'dynamicChangepasswordFromEncryption',
        component: dynamicChangepasswordFromEncryption_f,
    },
    {
        path: '/chat',
        name: 'chat',
        component: chat_f,
        meta: { requiresLogin: true }
    },



    // ===============admin routes===================
    // ===============admin routes===================
    {
        path: '/admin',
        name: 'admin',
        component: login_b
    },
    {
        path: '/admin/dashboard',
        name: 'dashboard_b',
        component: dashboard_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/services',
        name: 'services_b',
        component: services_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/changepassword',
        name: 'changepassword_b',
        component: changepasswordcontainer_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/profile',
        name: 'profile_b',
        component: profilecontainer_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/categories',
        name: 'categories_b',
        component: categories_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/uoms',
        name: 'uoms_b',
        component: uoms_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/brands',
        name: 'brands_b',
        component: brands_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/producttypes',
        name: 'producttypes_b',
        component: producttypes_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/skintypes',
        name: 'skintypes_b',
        component: skintypes_b,
        meta: { requiresAdminLogin: true },
    },

    {
        path: '/admin/country',
        name: 'country_b',
        component: country_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/areas',
        name: 'areas_b',
        component: areas_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/suppliers',
        name: 'suppliers_b',
        component: suppliers_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/products',
        name: 'products_b',
        component: products_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/applicationareas',
        name: 'applicationareas_b',
        component: applicationareas_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/formulation',
        name: 'formulation_b',
        component: formulation_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/featuredproducts',
        name: 'featuredproducts_b',
        component: featuredproducts_b,
        meta: { requiresAdminLogin: true },
    },


    {
        path: '/admin/latestproducts',
        name: 'latestproducts_b',
        component: latestproducts_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/bestsellingproducts',
        name: 'bestsellingproducts_b',
        component: bestsellingproducts_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/topratedproducts',
        name: 'toprated_b',
        component: toprated_b,
        meta: { requiresAdminLogin: true },
    },

    {
        path: '/admin/gifts',
        name: 'gifts_b',
        component: gifts_b,
        meta: { requiresAdminLogin: true },
    },

    {
        path: '/admin/limitededitions',
        name: 'limitededitions_b',
        component: limitededitions_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/newarrivals',
        name: 'newarrivals_b',
        component: newarrivals_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/advertisementsliders',
        name: 'advertisementslider_b',
        component: advertisementslider_b,
        meta: { requiresAdminLogin: true },
    },

    {
        path: '/admin/batch',
        name: 'batch_b',
        component: batch_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/deliverysettings',
        name: 'deliverysettings_b',
        component: deliverysettings_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/carts',
        name: 'carts_b',
        component: carts_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/manualcart',
        name: 'manualcart_b',
        component: manualcart_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/frontend',
        name: 'frontend_b',
        component: frontend_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/company',
        name: 'company_b',
        component: company_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/discountsettings',
        name: 'discount_settings_b',
        component: discount_settings_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/discountbadges',
        name: 'discount_badges_b',
        component: discount_badges_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/paymentsettings',
        name: 'paymentsettings_b',
        component: paymentsettings_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/pages',
        name: 'pages_b',
        component: pages_b,
        meta: { requiresAdminLogin: true },
    },


    {
        path: '/admin/reports',
        name: 'reports_b',
        component: reports_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/productvisit',
        name: 'productvisit_b',
        component: productvisit_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/customerproductvisit',
        name: 'customerproductvisit_b',
        component: customerproductvisit_b,
        meta: { requiresAdminLogin: true },
    },

    {
        path: '/admin/wishlistall',
        name: 'wishlistall_b',
        component: wishlistall_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/comparelist',
        name: 'comparelist_b',
        component: comparelist_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/lowstockreport',
        name: 'lowstockreport_b',
        component: lowstockreport_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/salesreport',
        name: 'salesreport_b',
        component: salesreport_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/valuablecustomers',
        name: 'valuablecustomers_b',
        component: valuablecustomers_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/dmysalesreport',
        name: 'dmy_sales_report_b',
        component: dmy_sales_report_b,
        meta: { requiresAdminLogin: true },
    },

    {
        path: '/admin/chat',
        name: 'chat_b',
        component: chat_b,
        meta: { requiresAdminLogin: true },
    },







    // super admin
    {
        path: '/admin/superadmin/accesscontrol',
        name: 'accesscontrol_b',
        component: accesscontrol_b,
        meta: { requiresAdminLogin: true },
    },
    {
        path: '/admin/superadmin/sitemap_generator',
        name: 'sitemap_generator_b',
        component: sitemap_generator_b,
        meta: { requiresAdminLogin: true },
    },




    // not found page
    {
        path: '*',
        component: notfound
    }


];
