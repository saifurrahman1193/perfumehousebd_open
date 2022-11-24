export default {
    state: {
        isLoggedIn: false,
        userInfo:{},
        companyInfo:{},
        cart:(localStorage.getItem('guestCart') ? JSON.parse(localStorage.getItem('guestCart')) : []),
        compareList: (localStorage.getItem('compareList') ? JSON.parse(localStorage.getItem('compareList')) : []),
        wishList: [],
        availableBadgeDiscountPoints: 0, // not solved yet, don't use it anywhere

        // search
        searchInput:'',
        searchProducts:[],
        searchProductsPaginationData: {},
        // search

        searchCategoryProducts:[],
        searchCategoryProductsPaginationData: {},

        searchLoading:false,

        // category
        searchCategory:'',
        searchCategorySlug:'',
        // category

        // brand
        searchBrandSlug:'',
        searchBrandProducts:[],
        searchBrandProductsPaginationData: {},
        // brand

        // limited edition
        searchLimitedEditionSlug:'',
        searchLimitedEditionProducts:[],
        searchLimitedEditionProductsPaginationData: {},
        // limited edition

        // new arrival
        searchNewArrivalSlug:'',
        searchNewArrivalProducts:[],
        searchNewArrivalProductsPaginationData: {},
        // new arrival

        // gift type
        searchGiftTypeSlug:'',
        searchGiftTypeProducts:[],
        searchGiftTypeProductsPaginationData: {},
        // gift type

        // admin
        isAdminLoggedIn: false,
        currentUserRoles:[],
        currentUserModules:[],
        currentUserModulesRouteNames:[],

        // order delivery
        deliveryaddress:{},
        admin_subheading_parent_moduleId:[],
        admin_subheading_moduleId:'',

        f_dynamic_page_info:{}



    },
    getters:{

        getIsLoggedIn(state){
            return state.isLoggedIn;
        },
        getUserInfo(state){
            return state.userInfo;
        },
        getCompanyInfo(state){
            return state.companyInfo;
        },
        getCartList(state){
            return state.cart;
        },
        getCartListCount(state){
            return state.cart.length;
        },

        getCompareList(state){
            return state.compareList;
        },


        getWishList(state){
            return state.wishList;
        },

        getSearchProducts(state){
            return state.searchProducts;
        },
        getSearchProductsPaginationData(state){
            return state.searchProductsPaginationData;
        },
        getSearchCategoryProducts(state){
            return state.searchCategoryProducts;
        },
        getSearchCategoryProductsPaginationData(state){
            return state.searchCategoryProductsPaginationData;
        },

        getSearchBrandProducts(state){
            return state.searchBrandProducts;
        },

        getSearchBrandProductsPaginationData(state){
            return state.searchBrandProductsPaginationData;
        },


        getSearchLimitedEditionProducts(state){
            return state.searchLimitedEditionProducts;
        },

        getSearchLimitedEditionProductsPaginationData(state){
            return state.searchLimitedEditionProductsPaginationData;
        },


        getSearchNewArrivalProducts(state){
            return state.searchNewArrivalProducts;
        },

        getSearchNewArrivalProductsPaginationData(state){
            return state.searchNewArrivalProductsPaginationData;
        },


        getSearchGiftTypeProducts(state){
            return state.searchGiftTypeProducts;
        },

        getSearchGiftTypeProductsPaginationData(state){
            return state.searchGiftTypeProductsPaginationData;
        },




        getSearchLoading(state){
            return state.searchLoading;
        },





        // admin
        getIsAdminLoggedIn(state){
            return state.isAdminLoggedIn;
        },
        getCurrentUserRoles(state){
            return state.currentUserRoles;
        },
        getCurrentUserModules(state){
            return state.currentUserModules;
        },
        getCurrentUserModulesRouteNames(state){
            return state.currentUserModulesRouteNames;
        },



    },
    mutations:{
        SET_ISLOGGEDIN (state, value) {
            state.isLoggedIn = value;
            // console.log('state.isLoggedIn = '+value)
        },
        SET_USERINFO (state, value) {
            state.userInfo = value;
        },
        SET_COMPANYINFO (state, value) {
            state.companyInfo = value;
        },
        ADD_TO_CART (state, obj) {
            state.cart.push(obj);
        },
        REMOVE_FROM_CART (state, productId) {
            state.cart = state.cart.filter(function(product){
                return product.productId != productId
            });
        },
        CLEAR_CART (state) {
            state.cart = []
        },

        ADD_TO_COMPARELIST (state, productId) {
            state.compareList.push(productId);
        },
        REMOVE_FROM_COMPARELIST (state, productId) {
            console.log(state.compareList)
            state.compareList = state.compareList.filter(function(compareListProductId){
                return compareListProductId != productId
            });
        },


        ADD_TO_WISHLIST (state, productId) {
            state.wishList.push(productId);
        },
        REMOVE_FROM_WISHLIST (state, productId) {
            state.wishList = state.wishList.filter(function(wishListProductId){
                return wishListProductId != productId
            });
        },

        SET_CURRENTUSERWISHLISTS (state, wishlist) {
            state.wishList = wishlist;
        },




        SET_ISADMINLOGGEDIN (state, value) {
            state.isAdminLoggedIn = value;
            // console.log('state.isAdminLoggedIn = '+value)
        },
        SET_CURRENTUSERROLES (state, value) {
            state.currentUserRoles = value;
        },
        SET_CURRENTUSERMODULES (state, value) {
            state.currentUserModules = value;
        },
        SET_CURRENTUSERMODULESROUTENAMES (state, value) {
            state.currentUserModulesRouteNames = value;
        },

        SET_SEARCH_INPUT (state, value) {
            state.searchInput = value;
        },

        SET_SEARCH_PRODUCTS (state, value) {
            state.searchProducts = value;
        },
        SET_SEARCH_PRODUCTS_PAGINATION_DATA (state, payload) {
            state.searchProductsPaginationData = payload
        },
        SET_SEARCH_CATEGORY_PRODUCTS (state, value) {
            state.searchCategoryProducts = value;
        },
        SET_SEARCH_CATEGORY_PRODUCTS_PAGINATION_DATA (state, payload) {
            state.searchCategoryProductsPaginationData = payload
        },
        SET_SEARCH_BRAND_PRODUCTS (state, value) {
            state.searchBrandProducts = value;
        },
        SET_SEARCH_BRAND_PRODUCTS_PAGINATION_DATA (state, payload) {
            state.searchBrandProductsPaginationData = payload
        },


        SET_SEARCH_LIMITED_EDITION_PRODUCTS (state, value) {
            state.searchLimitedEditionProducts = value;
        },
        SET_SEARCH_LIMITED_EDITION_PRODUCTS_PAGINATION_DATA (state, payload) {
            state.searchLimitedEditionProductsPaginationData = payload
        },


        SET_SEARCH_NEW_ARRIVAL_PRODUCTS (state, value) {
            state.searchNewArrivalProducts = value;
        },
        SET_SEARCH_NEW_ARRIVAL_PRODUCTS_PAGINATION_DATA (state, payload) {
            state.searchNewArrivalProductsPaginationData = payload
        },


        SET_SEARCH_GIFT_TYPE_PRODUCTS (state, value) {
            state.searchGiftTypeProducts = value;
        },
        SET_SEARCH_GIFT_TYPE_PRODUCTS_PAGINATION_DATA (state, payload) {
            state.searchGiftTypeProductsPaginationData = payload
        },


        SET_SEARCH_LOADING (state, value) {
            state.searchLoading = value;
        },


        SET_CATEGORY_PRODUCTS (state, value) {
            state.searchProducts = value;
        },
        SET_SEARCH_CATEGORY(state, value){
            state.searchCategory = value;
        },

        SET_SEARCH_CATEGORY_SLUG(state, value){
            state.searchCategorySlug = value;
        },
        SET_SEARCH_BRAND_SLUG(state, value){
            state.searchBrandSlug = value;
        },

        SET_SEARCH_LIMITED_EDITION_SLUG(state, value){
            state.searchLimitedEditionSlug = value;
        },

        SET_SEARCH_NEW_ARRIVALS_SLUG(state, value){
            state.searchNewArrivalSlug = value;
        },

        SET_SEARCH_GIFT_TYPE_SLUG(state, value){
            state.searchGiftTypeSlug = value;
        },

        SET_DELIVERYADDRESS(state, value){
            state.deliveryaddress = value;
        },

        ADD_TO_ADMIN_SUBHEADING_PARENTMODULEID(state, value){
            state.admin_subheading_parent_moduleId = [value];
        },
        ADD_TO_ADMIN_SUBHEADING_MODULEID(state, value){
            state.admin_subheading_moduleId = value;
        },
        SET_F_DYNAMIC_PAGE_INFO(state, value){
            state.f_dynamic_page_info = value
        },


    },
    actions:{
        checkIsLoggedIn({commit}){
            // console.log('checkIsLoggedIn called')
            // console.log('checkIsLoggedIn called and token = '+localStorage.getItem('token'))

            axios.post('/api/auth/me?token='+localStorage.getItem('token'))
            .then(function (response) {
                commit('SET_ISLOGGEDIN', true);
                commit('SET_USERINFO', response.data);
            })
            .catch(function (error) {
                commit('SET_ISLOGGEDIN', false);
                commit('SET_USERINFO', {});
            })

        },
        setCompanyInfo({commit}){
            axios.get('/api/getCompanyInfo')
            .then(function (response) {
                commit('SET_COMPANYINFO', response.data.data);
            })
            .catch(function (error) {
                commit('SET_COMPANYINFO', {});
            })
        },
        checkIsAdminLoggedIn({commit}){
            console.log('checkIsAdminLoggedIn called')
            console.log('checkIsAdminLoggedIn called and token = '+localStorage.getItem('token'))

            axios.post('/api/auth/meAdmin?token='+localStorage.getItem('token'))
            .then(function (response) {
                commit('SET_ISADMINLOGGEDIN', true);
                commit('SET_USERINFO', response.data);
            })
            .catch(function (error) {
                commit('SET_ISADMINLOGGEDIN', false);
                commit('SET_USERINFO', {});
            })

        },
        setCurrentUserRoles({commit}){

            axios.get('/api/auth/getCurrentUserRoles?token='+localStorage.getItem('token'))
            .then(function (response) {
                commit('SET_CURRENTUSERROLES', response.data.data);
            })
            .catch(function (error) {
                commit('SET_CURRENTUSERROLES', []);
            })

        },
        setCurrentUserModules({commit}){

            axios.get('/api/auth/getCurrentUserModules?token='+localStorage.getItem('token'))
            .then(function (response) {
                commit('SET_CURRENTUSERMODULES', response.data.modules);
                commit('SET_CURRENTUSERMODULESROUTENAMES', response.data.routenames);
            })
            .catch(function (error) {
                commit('SET_CURRENTUSERMODULES', []);
                commit('SET_CURRENTUSERMODULESROUTENAMES', []);
            })

        },
        setCurrentUserWishLists({commit}){
            axios.get('/api/getCurrentUserWishLists?token='+localStorage.getItem('token'))
            .then(function (response) {
                commit('SET_CURRENTUSERWISHLISTS', response.data.data);
            })
            .catch(function (error) {
                commit('SET_CURRENTUSERWISHLISTS', []);
            })
        },

        setSearchProducts({commit}){
            if (this.state.searchInput) {
                axios.post('/api/getSearchProductsWithDiscountCalculation/'+(this.state.searchInput || ''), {token: localStorage.getItem('token')})
                .then(function (response) {
                    commit('SET_SEARCH_PRODUCTS', response.data.data);
                    commit('SET_SEARCH_LOADING', false);
                })
                    .catch(function (error) {
                        commit('SET_SEARCH_PRODUCTS', []);
                })
            }

        },

        setSearchProductsWP({commit}){
            if (this.state.searchInput) {
                axios.post('/api/getSearchProductsWithDiscountCalculationWP/'+(this.state.searchInput || ''), {token: localStorage.getItem('token')})
                .then(function (response) {
                    commit('SET_SEARCH_PRODUCTS', response.data.data);
                    commit('SET_SEARCH_PRODUCTS_PAGINATION_DATA', response.data.paginationData);
                    commit('SET_SEARCH_LOADING', false);
                })
                    .catch(function (error) {
                        commit('SET_SEARCH_PRODUCTS', []);
                })
            }
        },
        setSearchLoading({commit}){
            commit('SET_SEARCH_LOADING', true);
        },
        setSearchLoadingFalse({commit}){
            commit('SET_SEARCH_LOADING', false);
        },


        setSearchCategoryProducts({commit}){
            axios.post('/api/getSearchCategoryProductsWithDiscountCalculation/'+this.state.searchCategorySlug, {token: localStorage.getItem('token')})
            .then(function (response) {
                commit('SET_SEARCH_CATEGORY_PRODUCTS', response.data.data);
                commit('SET_SEARCH_LOADING', false);
            })
            .catch(function (error) {
                commit('SET_SEARCH_CATEGORY_PRODUCTS', []);
            })
        },

        setSearchCategoryProductsWP({commit}){
            axios.post('/api/getSearchCategoryProductsWithDiscountCalculationWP/'+this.state.searchCategorySlug, {token: localStorage.getItem('token')})
            .then(function (response) {
                commit('SET_SEARCH_CATEGORY_PRODUCTS', response.data.data);
                console.log(response.data.paginationData);
                commit('SET_SEARCH_CATEGORY_PRODUCTS_PAGINATION_DATA', response.data.paginationData)
                commit('SET_SEARCH_LOADING', false);
            })
            .catch(function (error) {
                commit('SET_SEARCH_CATEGORY_PRODUCTS', []);
                commit('SET_SEARCH_CATEGORY_PRODUCTS_PAGINATION_DATA', {})
            })
        },

        setSearchBrandProductsWP({commit}){
            axios.post('/api/getSearchBrandProductsWithDiscountCalculationWP/'+this.state.searchBrandSlug, {token: localStorage.getItem('token')})
            .then(function (response) {
                commit('SET_SEARCH_BRAND_PRODUCTS', response.data.data);
                // console.log(response.data.paginationData);
                commit('SET_SEARCH_BRAND_PRODUCTS_PAGINATION_DATA', response.data.paginationData)
                commit('SET_SEARCH_LOADING', false);
            })
            .catch(function (error) {
                commit('SET_SEARCH_BRAND_PRODUCTS', []);
                commit('SET_SEARCH_BRAND_PRODUCTS_PAGINATION_DATA', {})
            })
        },

        setSearchLimitedEditionProductsWP({commit}){
            axios.post('/api/getSearchLimitedEditionProductsWithDiscountCalculationWP/'+this.state.searchLimitedEditionSlug, {token: localStorage.getItem('token')})
            .then(function (response) {
                commit('SET_SEARCH_LIMITED_EDITION_PRODUCTS', response.data.data);
                // console.log(response.data.paginationData);
                commit('SET_SEARCH_LIMITED_EDITION_PRODUCTS_PAGINATION_DATA', response.data.paginationData)
                commit('SET_SEARCH_LOADING', false);
            })
            .catch(function (error) {
                commit('SET_SEARCH_LIMITED_EDITION_PRODUCTS', []);
                commit('SET_SEARCH_LIMITED_EDITION_PRODUCTS_PAGINATION_DATA', {})
            })
        },


        setSearchNewArrivalProductsWP({commit}){
            axios.post('/api/getSearchNewArrivalProductsWithDiscountCalculationWP/'+this.state.searchNewArrivalSlug, {token: localStorage.getItem('token')})
            .then(function (response) {
                commit('SET_SEARCH_NEW_ARRIVAL_PRODUCTS', response.data.data);
                // console.log(response.data.paginationData);
                commit('SET_SEARCH_NEW_ARRIVAL_PRODUCTS_PAGINATION_DATA', response.data.paginationData)
                commit('SET_SEARCH_LOADING', false);
            })
            .catch(function (error) {
                commit('SET_SEARCH_NEW_ARRIVAL_PRODUCTS', []);
                commit('SET_SEARCH_NEW_ARRIVAL_PRODUCTS_PAGINATION_DATA', {})
            })
        },

        setSearchGiftTypeProductsWP({commit}){
            axios.post('/api/getSearchGiftTypeProductsWithDiscountCalculationWP/'+this.state.searchGiftTypeSlug, {token: localStorage.getItem('token')})
            .then(function (response) {
                commit('SET_SEARCH_GIFT_TYPE_PRODUCTS', response.data.data);
                // console.log(response.data.paginationData);
                commit('SET_SEARCH_GIFT_TYPE_PRODUCTS_PAGINATION_DATA', response.data.paginationData)
                commit('SET_SEARCH_LOADING', false);
            })
            .catch(function (error) {
                commit('SET_SEARCH_GIFT_TYPE_PRODUCTS', []);
                commit('SET_SEARCH_GIFT_TYPE_PRODUCTS_PAGINATION_DATA', {})
            })
        },





        setDynamicPageInfo({commit}, payload){
            axios.get('/api/getPageWithSlug/'+payload.pageSlug)
            .then(function (response) {
                commit('SET_F_DYNAMIC_PAGE_INFO', response.data.data);
            })
            .catch(function (error) {
                commit('SET_F_DYNAMIC_PAGE_INFO', {});
            })
        }


    }
}
