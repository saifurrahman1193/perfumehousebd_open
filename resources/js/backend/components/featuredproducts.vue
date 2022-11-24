<template>
    <v-app  id="inspire">

        <subheading></subheading>

        <v-sheet
            class=" ma-2"
            elevation="0"
            color="grey lighten-5"
        >
            <v-content>
                <v-card elevation="2" >
                    <v-card-title primary-title >
                        <v-spacer></v-spacer>
                        Add a new Featured Product
                        <v-spacer></v-spacer>
                    </v-card-title>
                    <v-form @submit.prevent="crudConfirm()">
                        <v-card-text>

                            <v-row >
                                <v-col  >
                                    <v-autocomplete
                                        label="Product"
                                        :items="getProductsForFeaturedProduct"
                                        item-text="productName"
                                        item-value="productId"
                                        dense
                                        filled
                                        v-model="featuredproduct.productId"
                                        clearable
                                        @change="featuredproduct.productId ? getProduct(featuredproduct.productId)  : '' "
                                    >
                                    </v-autocomplete>
                                </v-col>
                            </v-row>

                            <v-row v-if="featuredproduct.productId">
                                <v-col>
                                    <v-card elevation="0" >
                                        <v-list-item three-line>
                                            <v-list-item-content>
                                                <v-list-item-title>Product Details</v-list-item-title>
                                                <v-list-item-subtitle>{{featuredproduct.productName}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>{{featuredproduct.sku}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>{{featuredproduct.qty}} {{featuredproduct.uom}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>{{featuredproduct.brand}}</v-list-item-subtitle>
                                                <v-list-item-subtitle v-html="featuredproduct.shortDescription"></v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-card>
                                </v-col>
                            </v-row>

                            <gallery :images="selectedpictures" ></gallery>


                        </v-card-text>
                        <v-card-actions >
                            <v-spacer></v-spacer>
                            <v-btn color="error" text  @click="close()">Clear</v-btn>
                            <v-btn color="success"  type="submit">Save</v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-form>
                </v-card>
            </v-content>


            <v-content>

                <v-card elevation="2" >
                    <v-card-title primary-title >
                        <v-spacer></v-spacer>
                        Featured Products
                        <v-spacer></v-spacer>
                    </v-card-title>

                    <v-card-text>

                        <v-row>



                            <v-spacer></v-spacer>
                            <v-text-field
                                v-model="search"
                                append-icon="mdi-magnify"
                                label="Search"
                                single-line
                                hide-details
                            ></v-text-field>
                        </v-row>

                        <v-data-table
                        :headers="headers"
                        :items="featuredproducts"
                        :search="search"
                        >
                            <template v-slot:[`item.productName`]="{ item }">
                                <v-row v-if="item.productId">
                                    <v-col>
                                        <v-card elevation="0" >
                                            <v-list-item three-line>
                                                <v-list-item-content>
                                                    <v-list-item-subtitle>{{item.productName}}</v-list-item-subtitle>
                                                    <v-list-item-subtitle>{{item.sku}}</v-list-item-subtitle>
                                                    <v-list-item-subtitle>{{item.qty}} {{item.uom}}</v-list-item-subtitle>
                                                    <v-list-item-subtitle>{{item.brand}}</v-list-item-subtitle>
                                                    <v-list-item-subtitle v-html="item.shortDescription"></v-list-item-subtitle>
                                                </v-list-item-content>
                                            </v-list-item>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </template>

                            <template v-slot:[`item.images`]="{ item }">
                                <gallery :images="getThisProductPictures(item.productId)" axis="y"/>
                            </template>



                            <template v-slot:[`item.action`]="{ item }">

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="deleteConfirm(item.featuredProductId)" v-on="on">delete</v-icon>
                                    </template>
                                    <span>Delete Record?</span>
                                </v-tooltip>

                            </template>

                        </v-data-table>

                    </v-card-text>
                </v-card>
            </v-content>

        </v-sheet>

        <modal_alert
            :s_alert="s_alert"
            s_msg="Successfully saved !"
            :e_alert="e_alert"
            e_msg="Something went wrong !"
            :w_alert="w_alert"
            w_msg="Do you really want to proceed ?"
            :i_alert="i_alert"
            i_msg="Information "
            @cancelAlert="cancelAlert()"
            @cancelAlertAndProceed="cancelAlertAndProceed()"

        ></modal_alert>


        <v-spacer></v-spacer>

    </v-app>
</template>


<script>
    import subheading from './subheading.vue'
    import modal_alert from './../../frontend/components/modals/alert.vue'
    import modal_alert_mixin from './../../frontend/components/modals/alertMixins.vue'

    import gallery from './gallery/gallery.vue'


    export default {
        components:{
            subheading, modal_alert,  gallery
        },
        mounted() {
            this.getFeaturedProducts();
            this.getProducts();

        },
        mixins: [modal_alert_mixin],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'Featured Product Id',
                        align: 'start',
                        value: 'featuredProductId',
                    },
                    { text: 'Images', value: 'images' },
                    { text: 'Product', value: 'productName' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                featuredproducts: [],
                products:[],
                product:{},
                productpictures:[],
                featuredproductIds: [],
                selectedpictures: [],

                // add dialog
                formTitle: 'Featured Product',
                featuredproduct: {
                    productId: ''
                },
                featuredproductError: {
                    productId: ''
                },
                productIdRules: [
                    v => !!v || 'Product is required',
                ],
            }
        },
        methods: {

            getFeaturedProducts(){
                var _this = this;
                axios.get('/api/getFeaturedProducts')
                .then(function (response) {
                    _this.featuredproducts = response.data.data;
                    _this.setFeaturedProductIds()
                    _this.getProductPictures()
                })
                .catch(function (error) {
                })
            },
            getProducts(){
                var _this = this;
                axios.get('/api/getProducts')
                .then(function (response) {
                    _this.products = response.data.data;
                })
                .catch(function (error) {
                })
            },

            getProduct(productId){
                // this.getThisProductPictures(productId)

                var _this = this;
                axios.get('/api/getProductAllData/'+productId)
                .then(function (response) {
                    _this.featuredproduct = response.data.data;
                    _this.setThisProductPictures(productId)
                })
                .catch(function (error) {
                    _this.featuredproduct = {};
                })

            },


            setFeaturedProductIds(){
                this.featuredproductIds = this.featuredproducts.map((item) => {
                    return item.productId
                })
            },

            getProductPictures(){
                var _this = this;
                axios.post('/api/getProductPicturesWithProductIds', {'productIds' : this.featuredproductIds})
                .then(function (response) {
                    _this.productpictures = _this.objectToArray(response.data.data);
                })
                .catch(function (error) {
                })
            },

            getThisProductPictures( productId ){
                console.log('called')
                console.log(productId)
                var _this = this;
                return this.productpictures.filter(function(productpicture){
                    return productpicture.productId == productId
                }).map(function(productpicture) {
                    return productpicture.productPicturePath
                })
            },
            setThisProductPictures( productId ){

                var _this = this;
                axios.get('/api/getSingleProductPictures/'+productId)
                .then(function (response) {
                    _this.selectedpictures = _this.objectToArray(response.data.data);
                    _this.selectedpictures = _this.selectedpictures.filter(function(productpicture){
                                                return productpicture.productId == productId
                                            }).map(function(productpicture) {
                                                return productpicture.productPicturePath
                                            })
                })
                .catch(function (error) {
                    _this.selectedpictures = []
                })

            },

            clearForm(){
                this.featuredproduct= { productId: '' }
                this.featuredproductError= { productId: '' }
                this.selectedpictures = []
            },

            adding(){
                console.log(this.featuredproduct)
                var _this=this
                axios.post('/api/addFeaturedProduct/'+'?token='+this.token, this.featuredproduct)
                .then(function (response) {
                    _this.getFeaturedProducts()
                    _this.s_alert = true;
                    _this.clearForm()
                    _this.addingConfirmed=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.featuredproductError = { productId: error.response.data.productId };
                })
            },

            deleting(){
                console.log(this.deletingId)
                var _this = this;
                axios.post('/api/deleteFeaturedProduct/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    _this.getFeaturedProducts()

                    _this.s_alert = true;
                    _this.clearForm()

                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },


        },
        computed: {
           getProductsForFeaturedProduct: function(){
                let featuredproductsfinal=this.products

                this.featuredproducts.forEach(featuredproduct => {
                    featuredproductsfinal = featuredproductsfinal.filter(function(product){
                        return product.productId != featuredproduct.productId
                    })
                });
                return featuredproductsfinal;
            }
        }
    }
</script>

