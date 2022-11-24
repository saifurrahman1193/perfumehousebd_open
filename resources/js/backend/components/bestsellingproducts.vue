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
                        Add a new Best Seling Product
                        <v-spacer></v-spacer>
                    </v-card-title>
                    <v-form @submit.prevent="crudConfirm()">
                        <v-card-text>

                            <v-row >
                                <v-col  >
                                    <v-autocomplete
                                        label="Product"
                                        :items="getProductsForBestsellingProduct"
                                        item-text="productName"
                                        item-value="productId"
                                        dense
                                        filled
                                        v-model="bestsellingproduct.productId"
                                        clearable
                                        @change="getProduct(bestsellingproduct.productId);"
                                    >
                                    </v-autocomplete>
                                </v-col>
                            </v-row>

                            <v-row v-if="bestsellingproduct.productId">
                                <v-col>
                                    <v-card elevation="0" >
                                        <v-list-item three-line>
                                            <v-list-item-content>
                                                <v-list-item-title>Product Details</v-list-item-title>
                                                <v-list-item-subtitle>{{bestsellingproduct.productName}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>{{bestsellingproduct.sku}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>{{bestsellingproduct.qty}} {{bestsellingproduct.uom}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>{{bestsellingproduct.brand}}</v-list-item-subtitle>
                                                <v-list-item-subtitle v-html="bestsellingproduct.shortDescription"></v-list-item-subtitle>
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
                        Best Seling Products
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
                        :items="bestsellingproducts"
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
                                        <v-icon @click="deleteConfirm(item.bestsellingProductId)" v-on="on">delete</v-icon>
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
            this.getBestsellingProducts();
            this.getProducts();
        },
        mixins: [modal_alert_mixin],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'Best Seling Product Id',
                        align: 'start',
                        value: 'bestsellingProductId',
                    },
                    { text: 'Images', value: 'images' },
                    { text: 'Product', value: 'productName' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                bestsellingproducts: [],
                products:[],
                product:{},
                productpictures:[],
                productpictureIds:[],
                selectedpictures: [],

                // add dialog
                formTitle: 'Best Seling Product',
                bestsellingproduct: {
                    productId: ''
                },
                bestsellingproductError: {
                    productId: ''
                },
                productIdRules: [
                    v => !!v || 'Product is required',
                ],
            }
        },
        methods: {

            getBestsellingProducts(){
                var _this = this;
                axios.get('/api/getBestsellingProducts')
                .then(function (response) {
                    _this.bestsellingproducts = response.data.data;
                    _this.setProductIds()
                    _this.getProductPictures()

                })
                .catch(function (error) {
                })
            },
            setProductIds(){
                this.productpictureIds = this.bestsellingproducts.map((item) => {
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
                    _this.productpictures = []
                })
            },

            getThisBestsellingProductsPictures( productId ){
                var _this = this;
                return this.bestsellingproductspictures.filter(function(productpicture){
                    return productpicture.productId == productId
                }).map(function(productpicture) {
                    return productpicture.productPicturePath
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
                    _this.bestsellingproduct = response.data.data;
                    _this.setThisProductPictures(productId)
                })
                .catch(function (error) {
                    _this.bestsellingproduct = {};
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
                this.bestsellingproduct= { productId: '' }
                this.bestsellingproductError= { productId: '' }
                this.selectedpictures=[]
            },

            adding(){
                console.log(this.bestsellingproduct)
                var _this=this
                axios.post('/api/addBestsellingProduct/'+'?token='+this.token, this.bestsellingproduct)
                .then(function (response) {
                    _this.getBestsellingProducts()
                    _this.s_alert = true;
                    _this.clearForm()
                    _this.addingConfirmed=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.bestsellingproductError = { productId: error.response.data.productId };
                })
            },

            deleting(){
                console.log(this.deletingId)
                var _this = this;
                axios.post('/api/deleteBestsellingProduct/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    _this.getBestsellingProducts()

                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },


        },
        computed: {
            getProductsForBestsellingProduct: function(){
                let bestsellingproductsfinal=this.products

                this.bestsellingproducts.forEach(bestsellingproduct => {
                    bestsellingproductsfinal = bestsellingproductsfinal.filter(function(product){
                        return product.productId != bestsellingproduct.productId
                    })
                });
                return bestsellingproductsfinal;
            }
        }
    }
</script>

