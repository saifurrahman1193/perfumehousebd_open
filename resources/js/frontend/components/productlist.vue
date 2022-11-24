<template>
    <v-app>
        <subheading_f></subheading_f>

        <v-sheet
            :class="isSMALT?'mt-6':'mt-12'"
            elevation="0"
            max-width="100%"
             color="grey lighten-5"
         >


            <v-row :class="isSMALT?'mt-0':'mt-12'">

                <v-col cols="auto"  md="1" sm="1" xs="2" v-if="!categoriesTreeDrawer && !is1460AGT" >
                    <v-list   color="#F4433600"  style="position:fixed; z-index:10 !important;">
                        <v-list-item
                        @click="categoriesTreeDrawer=!categoriesTreeDrawer"
                        style="transform: rotate(-90deg); margin-left: -25px; background-color:orange; color:white !important; "
                        >
                            Categories
                        </v-list-item>
                    </v-list>
                </v-col>

                <v-col cols="auto"  xl="2" lg="2" md="3" sm="3" xs="12" v-if="categoriesTreeDrawer && !is1460AGT"  style="position:fixed; z-index:10 !important;">
                    <v-navigation-drawer
                        clipped
                        v-model="categoriesTreeDrawer"
                    >
                        <v-treeview
                            :items="categoriesTree"
                            item-key="categoryId"
                            shaped
                            open-on-click
                            dense
                            activatable
                            color="#C9151B"
                        >
                            <template slot="label" slot-scope="{ item }">
                                <a  @click="setSearchCategoryProducts(item.categorySlug); topFunction()"  class="black--text tree-node"  >{{item.category}}</a>
                            </template>
                        </v-treeview>
                    </v-navigation-drawer>
                </v-col>

                <v-col class=" col-md-2 "  v-if="is1460AGT">

                    <v-navigation-drawer
                        app
                        clipped
                        style="margin-top: 65px;"
                        v-if="is1460AGT"
                        >
                       <v-treeview
                            :items="categoriesTree"
                            item-key="categoryId"
                            shaped
                            open-on-click
                            dense
                            activatable
                            color="#C9151B"

                        >
                            <template slot="label" slot-scope="{ item }">
                                <a  @click="setSearchCategoryProducts(item.categorySlug); topFunction()"  class="black--text tree-node"  >{{item.category}}</a>
                            </template>
                        </v-treeview>


                    </v-navigation-drawer>
                </v-col>


















                <!-- <v-col cols="auto"  xl="2" lg="2" md="3" sm="3" xs="4" >
                    <v-treeview
                        :items="categoriesTree"
                        item-key="categoryId"
                        shaped
                        open-on-click
                        dense
                        activatable
                        color="#C9151B"
                    >
                        <template slot="label" slot-scope="{ item }">
                            <a  @click="setSearchCategoryProducts(item.category)"  class="black--text tree-node"  >{{item.category}}</a>
                        </template>
                    </v-treeview>
                </v-col> -->

                <!-- <v-divider vertical ></v-divider> -->
                <v-toolbar-title class="subtitle text-center mx-auto" v-if="noproductfound && !isLoading"> No product found!</v-toolbar-title>
                <v-toolbar-title class="subtitle text-center mx-auto" v-if="searchforproduct"> Search for products (e.g. neil polis, lipstick, face powder)</v-toolbar-title>
                <v-toolbar-title class="subtitle text-center mx-auto" v-if="getSearchLoading || isLoading">
                    <v-spacer />
                    <v-progress-circular
                            indeterminate
                            color="#C9151B"
                            size="70"
                            class="mx-auto"
                            >
                    </v-progress-circular>
                    <v-spacer />
                </v-toolbar-title>


                <v-col  cols="auto" :class="is1460AGT ?'col-md-10':'col-md-12'">

                    <!-- <v-row v-if="getSearchLoading" justify="center" align="center">
                        <v-progress-circular
                            indeterminate
                            color="#C9151B"
                            size="70"
                            class="mx-auto"
                            >
                        </v-progress-circular>
                    </v-row> -->


                    <v-row class="px-0 mx-0"
                    v-if="!getSearchLoading"
                    align="center"
                    justify="center"
                    >
                        <v-col
                            v-for="product in calculateProducts || [] "
                            :key="product.productId"
                            cols="auto"
                            :width="isSMALT? 153: 250"
                            class="mx-0 px-0"
                            :style="isSMALT?'margin-right:4px !important;':'margin-right:10px !important;'"
                        >
                            <v-card
                                color="grey lighten-4"
                                :width="isSMALT? 153: 250"
                                >
                                <productcarddetails_f :product="product" ></productcarddetails_f>
                            </v-card>
                        </v-col>
                    </v-row>



                    <!-- <v-row justify="center" v-if="!getSearchLoading && categoryslugPaginationData.current_page && $route.query.categorySlug"> -->
                        <!-- <v-col >
                            <v-container class="max-width">
                                <v-list elevation="0"  color="grey lighten-5">
                                    <v-list-item v-for="i in categoryslugPaginationData.last_page" :key="i" class="d-inline-block pa-0 mx-1 " >
                                        <v-btn small class="pa-0 card-text"
                                        :class="categoryslugPaginationData.current_page==i? 'orange white--text ':''"
                                        v-text="i"
                                        @click="setSearchCategoryProducts(categoryslugPaginationData.categorySlug+'?page='+i)"
                                        ></v-btn>
                                    </v-list-item>
                                </v-list>
                            </v-container>
                        </v-col> -->


                        <!-- https://bezkoder.com/vuetify-pagination-server-side/ -->
                    <!-- </v-row> -->

                    <v-pagination
                        v-if="!getSearchLoading && categoryslugPaginationData.current_page && $route.query.categorySlug"
                        v-model="categoryslugPaginationData.current_page"
                        class="my-4 "
                        :length="categoryslugPaginationData.last_page"

                        total-visible="8"
                        @input="setSearchCategoryProducts(categoryslugPaginationData.categorySlug+'?page='+categoryslugPaginationData.current_page)"
                        circle     color="#C9151B"
                    ></v-pagination>


                    <v-pagination
                        v-if="!getSearchLoading && brandslugPaginationData.current_page && $route.query.brandSlug"
                        v-model="brandslugPaginationData.current_page"
                        class="my-4 "
                        :length="brandslugPaginationData.last_page"

                        total-visible="8"
                        @input="setSearchBrandProducts(brandslugPaginationData.brandSlug+'?page='+brandslugPaginationData.current_page)"
                        circle     color="#C9151B"
                    ></v-pagination>


                    <v-pagination
                        v-if="!getSearchLoading && limitededitionslugPaginationData.current_page && $route.query.limitededitions"
                        v-model="limitededitionslugPaginationData.current_page"
                        class="my-4 "
                        :length="limitededitionslugPaginationData.last_page"

                        total-visible="8"
                        @input="setSearchLimitedEditionProducts(limitededitionslugPaginationData.limitedEditionSlug+'?page='+limitededitionslugPaginationData.current_page)"
                        circle     color="#C9151B"
                    ></v-pagination>


                    <v-pagination
                        v-if="!getSearchLoading && newarrivalslugPaginationData.current_page && $route.query.newarrivals"
                        v-model="newarrivalslugPaginationData.current_page"
                        class="my-4 "
                        :length="newarrivalslugPaginationData.last_page"

                        total-visible="8"
                        @input="setSearchNewArrivalProducts(newarrivalslugPaginationData.newArrivalSlug+'?page='+newarrivalslugPaginationData.current_page)"
                        circle     color="#C9151B"
                    ></v-pagination>


                    <v-pagination
                        v-if="!getSearchLoading && gifttypeslugPaginationData.current_page && $route.query.giftTypeSlug"
                        v-model="gifttypeslugPaginationData.current_page"
                        class="my-4 "
                        :length="gifttypeslugPaginationData.last_page"

                        total-visible="8"
                        @input="setSearchGiftTypeProducts(gifttypeslugPaginationData.giftTypeSlug+'?page='+gifttypeslugPaginationData.current_page)"
                        circle     color="#C9151B"
                    ></v-pagination>


                    <v-pagination
                        v-if="!getSearchLoading && searchProductsPaginationData.current_page && $route.query.searchInput"
                        v-model="searchProductsPaginationData.current_page"
                        class="my-4 "
                        :length="searchProductsPaginationData.last_page"

                        total-visible="8"
                        @input="search(searchProductsPaginationData.searchInput+'?page='+searchProductsPaginationData.current_page)"
                        circle     color="#C9151B"
                    ></v-pagination>




                </v-col>

                <!-- <v-col  cols="auto col-md-10" >
                </v-col> -->



            </v-row>

        </v-sheet>

        <!-- <v-spacer></v-spacer>
        <latestviewsproducts_f></latestviewsproducts_f>
        <latestbestsellingtopratedproducts_f ></latestbestsellingtopratedproducts_f>
        <footer_f></footer_f> -->

    </v-app>
</template>


<script>
    import { mapGetters } from 'vuex'

    import subheading_f from './subheading.vue'
    import productcarddetails_f from './productcarddetails.vue'
    import latestviewsproducts_f from './latestviewsproducts.vue'
    import latestbestsellingtopratedproducts_f from './latestbestsellingtopratedproducts.vue'
    import footer_f from './footer.vue'
    import algorithms from './../../mixins/algorithms.vue'
    import responsive from '../../mixins/responsive.vue'



    export default {
        mounted () {
            this.topFunction()
            this.getCategories();

            if (this.$route.query.categorySlug) {
                this.setSearchCategoryProducts(this.$route.query.categorySlug)
            }

            if (this.$route.query.brandSlug) {
                this.setSearchBrandProducts(this.$route.query.brandSlug)
            }

            if (this.$route.query.limitededitions) {
                this.setSearchLimitedEditionProducts(this.$route.query.limitededitions)
            }

            if (this.$route.query.newarrivals) {
                this.setSearchNewArrivalProducts(this.$route.query.newarrivals)
            }

            if (this.$route.query.giftTypeSlug) {
                this.setSearchGiftTypeProducts(this.$route.query.giftTypeSlug)
            }

            // if (this.$route.query.brandSlug) {
            //     this.isLoading=true
            //     this.getBrandProducts(this.$route.query.brandSlug)
            // }

            if(this.$route.query.newarrivals || this.$route.query.limitededitions || this.$route.query.giftTypeSlug || this.$route.query.categorySlug)
            {
                this.isLoading=true
            }

            // this.getProducts();
        },
        components:{
           subheading_f, productcarddetails_f, latestviewsproducts_f, footer_f, latestbestsellingtopratedproducts_f
        },
        data () {
            return {
                token: localStorage.getItem('token'),
                products:[],
                categories:[],
                categoriesTreeDrawer:false,
                isLoading:false,

                categoryslugPaginationData: {},
                brandslugPaginationData: {},
                limitededitionslugPaginationData: {},
                newarrivalslugPaginationData: {},
                gifttypeslugPaginationData: {},

                searchProductsPaginationData: {},


            }
        },
        mixins: [algorithms, responsive],
        methods: {
            getCategories(){
                var _this = this;
                axios.get('/api/getCategories')
                .then(function (response) {
                    _this.categories = response.data.data;
                })
                .catch(function (error) {
                })
            },
            // getProducts(){
            //     var _this = this;
            //     axios.post('/api/getProductsWithDiscountCalculation', {token: this.token})
            //     .then(function (response) {
            //         _this.products = response.data.data;
            //     })
            //     .catch(function (error) {
            //     })
            // },


            getSpecificGiftTypeProducts(giftTypeSlug){

                var arr = [];

                arr =  this.products.filter(function (product) {
                    // console.log('product==================== = ')
                    // console.log( product)
                    let productArray=(product.giftTypes || '').split('::::');
                    // console.log('productArray = '+ productArray)
                    let condition = false;

                    productArray.forEach(element => {
                        if (giftTypeSlug == element) {
                            condition = true;
                        }
                    });
                    return condition;
                });

                arr = this.arrayOfObjectSortDesc(arr,  'sellingPrice')

                return arr;
            },

            getFragranceProducts(fragrance){

                return this.products.filter(function (product) {
                    return (product.gender || "").toLowerCase() == (fragrance || "").toLowerCase();
                });
            },
            getCategoryProducts(category){
                return this.products.filter(function (product) {
                    // console.log('(product.categories || "").toLowerCase().search(category) ='+(product.categories || "").toLowerCase().search(category) )
                    return (product.categories || "").toLowerCase().search((category || "").toLowerCase())>=0 ;
                });
            },

            getNewArrivalsProducts(newarrivals){
                return this.products.filter(function (product) {
                    return (product.isNewArrival || "") == (newarrivals || "");
                });
            },
            // getLimitedEditionsProducts(limitededitions){
            //     return this.products.filter(function (product) {
            //         return (product.isLimitedEdition || "") == (limitededitions || "");
            //     });
            // },

            getTree(arr) {
                // return arr

                var tree = [],
                    mappedArr = {},
                    arrElem,
                    mappedElem;

                // First map the nodes of the array to an object -> create a hash table.
                for(var i = 0, len = arr.length; i < len; i++) {
                    arrElem = arr[i];
                    mappedArr[arrElem.categoryId] = arrElem;
                    mappedArr[arrElem.categoryId]['children'] = [];
                }


                for (var categoryId in mappedArr) {
                    if (mappedArr.hasOwnProperty(categoryId)) {
                    mappedElem = mappedArr[categoryId];
                    // If the element is not at the root level, add it to its parent array of children.
                    if (mappedElem.parentCategoryId) {
                        mappedArr[mappedElem['parentCategoryId']]['children'].push(mappedElem);
                    }
                    // If the element is at the root level, add it to first level elements array.
                    else {
                        tree.push(mappedElem);
                    }
                    }
                }
                return tree;
            },
            // setSearchCategoryProducts(category){
            //     this.gotorouterview('/productlist?category='+category)

            //     this.$store.commit('SET_SEARCH_CATEGORY', category)
            //     this.$store.dispatch('setSearchLoading')
            //     this.$store.dispatch('setSearchCategoryProducts')
            // },

            search(searchInput){
                this.topFunction()
                this.gotorouterview('/productlist?searchInput='+searchInput)

                this.$store.commit('SET_SEARCH_INPUT', searchInput)
                this.$store.dispatch('setSearchLoading')
                this.$store.dispatch('setSearchProductsWP')
            },

            setSearchCategoryProducts(categorySlug){
                console.log(categorySlug)
                this.topFunction()
                this.gotorouterview('/productlist?categorySlug='+categorySlug)

                this.$store.commit('SET_SEARCH_CATEGORY_SLUG', categorySlug)
                this.$store.dispatch('setSearchLoading')
                this.$store.dispatch('setSearchCategoryProductsWP')
            },

            setSearchBrandProducts(brandSlug){
                this.topFunction()
                this.gotorouterview('/productlist?brandSlug='+brandSlug)

                this.$store.commit('SET_SEARCH_BRAND_SLUG', brandSlug)
                this.$store.dispatch('setSearchLoading')
                this.$store.dispatch('setSearchBrandProductsWP')
            },

            setSearchLimitedEditionProducts(limitedEditionSlug){
                this.topFunction()
                this.gotorouterview('/productlist?limitededitions='+limitedEditionSlug)

                this.$store.commit('SET_SEARCH_LIMITED_EDITION_SLUG', limitedEditionSlug)
                this.$store.dispatch('setSearchLoading')
                this.$store.dispatch('setSearchLimitedEditionProductsWP')
            },

            setSearchNewArrivalProducts(newArrivalSlug){
                this.gotorouterview('/productlist?newarrivals='+newArrivalSlug)

                this.$store.commit('SET_SEARCH_NEW_ARRIVALS_SLUG', newArrivalSlug)
                this.$store.dispatch('setSearchLoading')
                this.$store.dispatch('setSearchNewArrivalProductsWP')
            },

            setSearchGiftTypeProducts(giftTypeSlug){
                this.gotorouterview('/productlist?giftTypeSlug='+giftTypeSlug)

                this.$store.commit('SET_SEARCH_GIFT_TYPE_SLUG', giftTypeSlug)
                this.$store.dispatch('setSearchLoading')
                this.$store.dispatch('setSearchGiftTypeProductsWP')
            },

            getBrandProducts(brandSlug){


                // ===========================this is being replaced=================
                var arr = []
                arr =  this.products.filter(function (product) {
                    return (product.brandSlug || "").toLowerCase() == (brandSlug || "").toLowerCase();
                });
                arr = this.arrayOfObjectSortDesc(arr,  'sellingPrice')
                return arr;
                // ===========================this is being replaced=================


                // var arr = []
                // this.products.forEach(product => {
                //     if((product.brandSlug || "").toLowerCase() == (brandSlug || "").toLowerCase())
                //     {
                //         arr.push(product)
                //     }
                // });
                // // this.$store.dispatch('setSearchLoadingFalse')


                // return arr;



            },

        },
        computed: {
            ...mapGetters([
                'getSearchProducts', 'getSearchProductsPaginationData', 'getSearchCategoryProducts', 'getSearchCategoryProductsPaginationData','getSearchLoading', 'getSearchBrandProducts', 'getSearchBrandProductsPaginationData', 'getSearchLimitedEditionProducts', 'getSearchLimitedEditionProductsPaginationData', 'getSearchNewArrivalProducts', 'getSearchNewArrivalProductsPaginationData', 'getSearchGiftTypeProducts', 'getSearchGiftTypeProductsPaginationData'
            ]),
            calculateProducts: function () {
                    var calculatedProducts = [];
                    if(this.$route.query.searchInput )
                    {
                        // calculatedProducts = this.getSearchProducts;

                        calculatedProducts = this.getSearchProducts;
                        this.searchProductsPaginationData = this.getSearchProductsPaginationData;

                        calculatedProducts = this.objectToArray(calculatedProducts)
                        calculatedProducts = this.arrayOfObjectSortDesc(calculatedProducts,  'sellingPrice')

                        this.isLoading=false
                    }

                    else if(this.$route.query.categorySlug )
                    {
                        calculatedProducts = this.getSearchCategoryProducts;
                        this.categoryslugPaginationData = this.getSearchCategoryProductsPaginationData;

                        calculatedProducts = this.objectToArray(calculatedProducts)
                        calculatedProducts = this.arrayOfObjectSortDesc(calculatedProducts,  'sellingPrice')

                        this.isLoading=false
                    }

                    else if(this.$route.query.brandSlug )
                    {
                        // calculatedProducts = this.getBrandProducts(this.$route.query.brandSlug);
                        // this.isLoading=false

                        calculatedProducts = this.getSearchBrandProducts;
                        this.brandslugPaginationData = this.getSearchBrandProductsPaginationData;

                        calculatedProducts = this.objectToArray(calculatedProducts)
                        calculatedProducts = this.arrayOfObjectSortDesc(calculatedProducts,  'sellingPrice')

                        this.isLoading=false

                    }

                    else if(this.$route.query.limitededitions )
                    {
                        calculatedProducts = this.getSearchLimitedEditionProducts;
                        this.limitededitionslugPaginationData = this.getSearchLimitedEditionProductsPaginationData;

                        calculatedProducts = this.objectToArray(calculatedProducts)
                        calculatedProducts = this.arrayOfObjectSortDesc(calculatedProducts,  'sellingPrice')

                        this.isLoading=false
                    }

                    else if(this.$route.query.newarrivals )
                    {
                        calculatedProducts = this.getSearchNewArrivalProducts;
                        this.newarrivalslugPaginationData = this.getSearchNewArrivalProductsPaginationData;

                        calculatedProducts = this.objectToArray(calculatedProducts)
                        calculatedProducts = this.arrayOfObjectSortDesc(calculatedProducts,  'sellingPrice')

                        this.isLoading=false
                    }

                    else if(this.$route.query.giftTypeSlug){
                        calculatedProducts = this.getSearchGiftTypeProducts;
                        this.gifttypeslugPaginationData = this.getSearchGiftTypeProductsPaginationData;

                        calculatedProducts = this.objectToArray(calculatedProducts)
                        calculatedProducts = this.arrayOfObjectSortDesc(calculatedProducts,  'sellingPrice')

                        this.isLoading=false
                    }

                    // else if(this.$route.query.giftTypeSlug){
                    //     calculatedProducts = this.getSpecificGiftTypeProducts(this.$route.query.giftTypeSlug)
                    //     this.isLoading=false
                    // }

                    else if(this.$route.query.fragrance )
                    {
                        calculatedProducts = this.getFragranceProducts(this.$route.query.fragrance);
                    }
                    else if(this.$route.query.makup )
                    {
                        calculatedProducts = this.getCategoryProducts(this.$route.query.makup);
                    }
                    else if(this.$route.query.toolsandbrush )
                    {
                        calculatedProducts = this.getCategoryProducts(this.$route.query.toolsandbrush);
                    }
                    else if(this.$route.query.skincare )
                    {
                        calculatedProducts = this.getCategoryProducts(this.$route.query.skincare);
                    }
                    // else if(this.$route.query.newarrivals )
                    // {
                    //     calculatedProducts = this.getNewArrivalsProducts(this.$route.query.newarrivals);
                    //     this.isLoading=false
                    // }



                    return this.objectToArray(_.shuffle(calculatedProducts));

            },

            categoriesTree: function () {

                let categorieswithchild=this.categories

                categorieswithchild.forEach(function (category) {
                    // category.id = category.categoryId
                    // category.name = category.category
                    category.children=[]
                });

                return this.getTree(categorieswithchild)
            },
            noproductfound:function () {
                if (!this.getSearchLoading  &&  !((this.calculateProducts || []).length>0) && !(this.$route.query.searchInput=='') ) {
                    return true;
                }
                else{
                    return false
                }
            },
            searchforproduct:function () {
                if (this.getSearchLoading==false && this.$route.query.searchInput=='' && !(this.calculateProducts || []).length ) {
                    return true;
                }
                else{
                    return false
                }
            },

        },

        metaInfo: {
            titleTemplate: '%s | Products'
        }



    }
</script>

<style scoped>
    .tree-node:hover{
        text-decoration : underline !important;
    }
</style>

