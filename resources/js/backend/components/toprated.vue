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
                        Add a new Top Rated Product
                        <v-spacer></v-spacer>
                    </v-card-title>
                    <v-form @submit.prevent="crudConfirm()">
                        <v-card-text>

                            <v-row >
                                <v-col  >
                                    <v-autocomplete
                                        label="Product"
                                        :items="getProductsForTopratedProduct"
                                        item-text="productName"
                                        item-value="productId"
                                        dense
                                        filled
                                        v-model="topratedproduct.productId"
                                        clearable
                                        @change="getProduct(topratedproduct.productId)"
                                    >
                                    </v-autocomplete>
                                </v-col>
                            </v-row>

                            <v-row v-if="topratedproduct.productId">
                                <v-col>
                                    <v-card elevation="0" >
                                        <v-list-item three-line>
                                            <v-list-item-content>
                                                <v-list-item-title>Product Details</v-list-item-title>
                                                <v-list-item-subtitle>{{topratedproduct.productName}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>{{topratedproduct.sku}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>{{topratedproduct.qty}} {{topratedproduct.uom}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>{{topratedproduct.brand}}</v-list-item-subtitle>
                                                <v-list-item-subtitle v-html="topratedproduct.shortDescription"></v-list-item-subtitle>
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
                        Top Rated Products
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
                        :items="topratedproducts"
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
                                        <v-icon @click="deleteConfirm(item.topratedProductId)" v-on="on">delete</v-icon>
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
            this.getTopratedProducts();
            this.getProducts();
        },
        mixins: [modal_alert_mixin],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'Top Rated Product Id',
                        align: 'start',
                        value: 'topratedProductId',
                    },
                    { text: 'Images', value: 'images' },
                    { text: 'Product', value: 'productName' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                topratedproducts: [],
                products:[],
                product:{},
                productpictures:[],
                productpictureIds:[],
                selectedpictures: [],

                // add dialog
                formTitle: 'Top Rated Product',
                topratedproduct: {
                    productId: ''
                },
                topratedproductError: {
                    productId: ''
                },
                productIdRules: [
                    v => !!v || 'Product is required',
                ],
            }
        },
        methods: {

            getTopratedProducts(){
                var _this = this;
                axios.get('/api/getTopratedProducts')
                .then(function (response) {
                    _this.topratedproducts = response.data.data;
                    _this.setProductIds()
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
                var _this = this;
                axios.get('/api/getProductAllData/'+productId)
                .then(function (response) {
                    _this.topratedproduct = response.data.data;
                    _this.setThisProductPictures(productId)
                })
                .catch(function (error) {
                    _this.topratedproduct = {};
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

            setProductIds(){
                this.productpictureIds = this.topratedproducts.map((item) => {
                    return item.productId
                })
            },


            getProductPictures(){
                var _this = this;
                axios.post('/api/getProductPicturesWithProductIds', {'productIds' : this.productpictureIds})
                .then(function (response) {
                    _this.productpictures = _this.objectToArray(response.data.data);
                })
                .catch(function (error) {
                })
            },

            getThisProductPictures( productId ){
                var _this = this;
                return this.productpictures.filter(function(productpicture){
                    return productpicture.productId == productId
                }).map(function(productpicture) {
                    return productpicture.productPicturePath
                })
            },
            clearForm(){
                this.topratedproduct= { productId: '' }
                this.topratedproductError= { productId: '' }
                this.selectedpictures = []
            },

            adding(){
                console.log(this.topratedproduct)
                var _this=this
                axios.post('/api/addTopratedProduct/'+'?token='+this.token, this.topratedproduct)
                .then(function (response) {
                    _this.getTopratedProducts()
                    _this.s_alert = true;
                    _this.clearForm()
                    _this.addingConfirmed=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.topratedproductError = { productId: error.response.data.productId };
                })
            },

            deleting(){
                console.log(this.deletingId)
                var _this = this;
                axios.post('/api/deleteTopratedProduct/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    _this.getTopratedProducts()

                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },


        },
        computed: {
           getProductsForTopratedProduct: function(){
                let topratedproductsfinal=this.products

                this.topratedproducts.forEach(topratedproduct => {
                    topratedproductsfinal = topratedproductsfinal.filter(function(product){
                        return product.productId != topratedproduct.productId
                    })
                });
                return topratedproductsfinal;
            }
        }
    }
</script>

