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
                        {{ product.batchProductId ? 'Update' : 'Add a new' }}  Batch
                        <v-spacer></v-spacer>
                    </v-card-title>
                    <v-form @submit.prevent="crudConfirm()">
                        <v-card-text>

                            <v-row >
                                <v-col  >
                                    <v-autocomplete
                                        label="Product*"
                                        :items="products"
                                        item-text="productName"
                                        item-value="productId"
                                        dense
                                        filled
                                        v-model="product.productId"
                                        clearable
                                        @change=" product.productId ? getProduct(product.productId)  : product={}; productpictures=[]  "

                                    >
                                    </v-autocomplete>
                                </v-col>
                            </v-row>

                            <v-row v-if="product.productId">
                                <v-col>
                                    <v-card elevation="0" >
                                        <v-list-item three-line>
                                            <v-list-item-content>
                                                <v-list-item-title>Product Details</v-list-item-title>
                                                <v-list-item-subtitle>{{product.productName||''}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>{{product.sku||''}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>{{product.qty||''}} {{product.uom||''}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>{{product.brand||''}}</v-list-item-subtitle>
                                                <v-list-item-subtitle v-html="product.shortDescription||''"></v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-card>
                                </v-col>
                            </v-row>

                            <gallery :images="productpictures"/>



                            <v-row>
                                <v-col cols="12" sm="12" md="6">
                                    <v-text-field name="purchasePrice" label="Purchase Price*" v-model="product.purchasePrice"
                                        type="number"
                                        :rules="purchasePriceRules"
                                        :error-messages="productError.purchasePrice"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" sm="12" md="6">
                                    <v-text-field name="sellingPrice" label="Selling Price*" v-model="product.sellingPrice"
                                        type="number"
                                        :rules="sellingPriceRules"
                                        :error-messages="productError.sellingPrice"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" sm="12" md="6">
                                    <v-text-field name="purchaseQty" label="Quantity*" v-model="product.purchaseQty"
                                        type="number"
                                        :rules="purchaseQtyRules"
                                        :error-messages="productError.purchaseQty"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" sm="12" md="6">
                                    <v-switch v-model="product.isStockOut" inset :label="product.isStockOut==0 ? 'In-Stock' : 'Out of Stock'" false-value="0" true-value="1" color="red"></v-switch>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" sm="12" md="6">
                                    <v-menu
                                        v-model="manufactureDateMenu"
                                        transition="scale-transition"
                                        offset-y
                                        max-width="290px"
                                        min-width="290px"
                                        :close-on-content-click="false"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                label="Manufacture Date"
                                                append-icon="event"
                                                v-model="formattedManufactureDate"
                                                v-on="on"
                                            ></v-text-field>
                                        </template>

                                        <v-date-picker
                                        v-model="product.manufactureDate"
                                        no-title
                                        dense
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn @click="manufactureDateMenu=false" icon>Ok</v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>

                                <v-col cols="12" sm="12" md="6">
                                    <v-menu
                                        v-model="expireDateMenu"
                                        transition="scale-transition"
                                        offset-y
                                        max-width="290px"
                                        min-width="290px"
                                        :close-on-content-click="false"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                label="Expire Date"
                                                append-icon="event"
                                                v-model="formattedExpireDate"
                                                v-on="on"
                                            ></v-text-field>
                                        </template>

                                        <v-date-picker
                                        v-model="product.expireDate"
                                        no-title
                                        dense
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn @click="expireDateMenu=false" icon>Ok</v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>
                            </v-row>

                            <v-row>
                                <v-col cols="12" sm="12" md="6">
                                    <v-menu
                                        v-model="batchDateMenu"
                                        transition="scale-transition"
                                        offset-y
                                        max-width="290px"
                                        min-width="290px"
                                        :close-on-content-click="false"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                label="Batch Date"
                                                append-icon="event"
                                                v-model="formattedBatchDate"
                                                v-on="on"
                                            ></v-text-field>
                                        </template>

                                        <v-date-picker
                                        v-model="product.batchDate"
                                        no-title
                                        dense
                                        >
                                            <v-spacer></v-spacer>
                                            <v-btn @click="batchDateMenu=false" icon>Ok</v-btn>
                                        </v-date-picker>
                                    </v-menu>
                                </v-col>

                                <v-col cols="12" sm="12" md="6">
                                    <v-text-field name="contact" label="Contact" v-model="product.contact" ></v-text-field>
                                </v-col>

                            </v-row>

                            <v-row>
                                <v-col cols="12" sm="12" md="6">
                                    <v-text-field name="representative" label="Representative" v-model="product.representative" ></v-text-field>
                                </v-col>

                                <v-col  >
                                    <v-autocomplete
                                        label="Supplier"
                                        :items="suppliers"
                                        item-text="supplier"
                                        item-value="supplierId"
                                        dense
                                        filled
                                        v-model="product.supplierId"
                                        clearable
                                    >
                                    </v-autocomplete>
                                </v-col>

                            </v-row>


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
                        Product Batches
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
                        :items="batchproducts"
                        :search="search"
                        >
                            <template v-slot:[`item.productName`]="{ item }">
                                <v-row v-if="item.productId">
                                    <v-col>
                                        <v-card elevation="0" >
                                            <v-list >
                                                <v-list-item >{{item.productName}}</v-list-item>
                                                <v-list-item v-if="item.sku">{{item.sku}}</v-list-item>
                                                <v-list-item v-if="item.qty">{{item.qty}} {{item.uom}}</v-list-item>
                                                <v-list-item v-if="item.brand">{{item.brand}}</v-list-item>
                                                <v-list-item ><readmoreless :text="item.shortDescription" ></readmoreless> </v-list-item>
                                            </v-list>
                                        </v-card>
                                    </v-col>
                                </v-row>
                            </template>

                            <template v-slot:[`item.images`]="{ item }">
                                <gallery :images="getThisBatchProductPictures(item.productId)"  axis="y"/>
                            </template>

                            <template v-slot:[`item.isStockOut`]="{ item }">
                                <!-- {{item.isStockOut == 1 ? 'Out of Stock' : 'In-Stock'}} -->
                                <v-chip  class="ma-2" color="red" text-color="white" v-if="item.isStockOut == 1" x-small>Out of stock</v-chip>
                                <v-chip  class="ma-2" color="green" text-color="white" v-else x-small>Available</v-chip>
                            </template>

                            <template v-slot:[`item.prices`]="{ item }">
                                <v-list dense >
                                    <v-list-item ><strong>Purchase Price:</strong>&nbsp; ৳  {{item.purchasePrice}}</v-list-item>
                                    <v-list-item ><strong>Selling Price:</strong>&nbsp; ৳ {{item.sellingPrice}}</v-list-item>
                                </v-list>
                            </template>

                            <template v-slot:[`item.dates`]="{ item }">
                                <v-list dense v-if="item.manufactureDate || item.expireDate ||  item.batchDate">
                                    <v-list-item v-if="item.manufactureDate"><strong>MFG:</strong> &nbsp; {{formatDate(item.manufactureDate)}}</v-list-item>
                                    <v-list-item v-if="item.expireDate"><strong>EXP:</strong> &nbsp; {{formatDate(item.expireDate)}}</v-list-item>
                                    <v-list-item v-if="item.batchDate"><strong>Batch:</strong> &nbsp; {{formatDate(item.batchDate)}}</v-list-item>
                                </v-list>
                            </template>



                            <template v-slot:[`item.action`]="{ item }">

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="editRecord(item.batchProductId); getThisProductPictures(item.productId)" v-on="on">edit</v-icon>
                                    </template>
                                    <span>Edit Record?</span>
                                </v-tooltip>

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="deleteConfirm(item.batchProductId)" v-on="on">delete</v-icon>
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
    import readmoreless from './../../components/readmoreless.vue'


    export default {
        components:{
            subheading, modal_alert,  gallery, readmoreless
        },
        mounted() {
            this.getProducts();
            this.getBatchProducts();
            this.getSuppliers();

        },
        mixins: [modal_alert_mixin],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'Batch Product Id',
                        align: 'start',
                        value: 'batchProductId',
                    },
                    { text: 'Images', value: 'images' },
                    { text: 'Product', value: 'productName' , width:250},
                    { text: 'Prices', value: 'prices' , width:220},
                    // { text: 'Purchase Price', value: 'purchasePrice' },
                    // { text: 'Selling Price', value: 'sellingPrice' },
                    { text: 'Purchase Qty', value: 'purchaseQty' },
                    { text: 'Stock Status', value: 'isStockOut' },
                    { text: 'Dates', value: 'dates' , width:200},
                    { text: 'Supplier', value: 'supplier' },
                    { text: 'Representative', value: 'representative' },
                    { text: 'Contact', value: 'contact' },
                    // { text: 'MFG', value: 'manufactureDate' },
                    // { text: 'EXP', value: 'expireDate' },
                    // { text: 'Batch Date', value: 'batchDate' },
                    { text: 'Actions', value: 'action', sortable: false , width:90},
                ],
                products: [],
                product:{},
                productpictures:[],
                batchproducts: [],
                batchproduct: { batchProductId:'', productId:'', purchasePrice:'', sellingPrice:'' , purchaseQty: '', isStockOut: 0, manufactureDate:null, expireDate:'', batchDate:'', contact:'', representative:'', supplierId:'' },
                productError: {batchProductId:'',  productId:'', purchasePrice:'', sellingPrice:'' , purchaseQty: '', isStockOut: 0, manufactureDate:null, expireDate:'', batchDate:'', contact:'', representative:'', supplierId:'' },

                // add dialog
                formTitle: 'Batch',
                product: {
                    productId: ''
                },
                productError: {
                    productId: ''
                },
                productIdRules: [
                    v => !!v || 'Product is required',
                ],
                purchasePriceRules: [
                    v => !!v || 'Purchase price is required',
                ],
                sellingPriceRules: [
                    v => !!v || 'Selling price is required',
                ],
                purchaseQtyRules: [
                    v => !!v || 'Qty is required',
                ],
                manufactureDateMenu:false,
                expireDateMenu:false,
                batchDateMenu:false,
                suppliers:[],

                batchProductIds:[],
                batchProductPictures:[],
            }
        },
        methods: {

            clearBatchProduct(){
                this.batchproduct = {  purchasePrice:'', sellingPrice:'' , purchaseQty: '', isStockOut: 0, manufactureDate:null, expireDate:'', batchDate:'', contact:'', representative:'', supplierId:'' }
            },
            getSuppliers(){
                var _this = this;
                axios.get('/api/getSuppliers')
                .then(function (response) {
                    _this.suppliers = response.data.data;
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
                this.getThisProductPictures(productId)

                var _this = this;
                axios.get('/api/getProductAllData/'+productId)
                .then(function (response) {
                    _this.product = response.data.data;
                    _this.product.batchProductId=''
                    _this.product.purchasePrice=''
                    _this.product.sellingPrice=''
                    _this.product.purchaseQty=''
                    _this.product.isStockOut=''
                })
                .catch(function (error) {
                    _this.product = {};
                })

            },




            getBatchProducts(){
                var _this = this;
                axios.get('/api/getBatchProducts')
                .then(function (response) {
                    _this.batchproducts = response.data.data;
                    _this.setBatchProductIds()
                    _this.getBatchProductPictures()
                })
                .catch(function (error) {
                })
            },
            setBatchProductIds(){
                this.batchProductIds = this.batchproducts.map((batchproduct) => {
                    return batchproduct.productId
                })
            },


            getBatchProductPictures(){
                var _this = this;
                axios.post('/api/getProductPicturesWithProductIds', {'productIds' : this.batchProductIds})
                .then(function (response) {
                    _this.batchProductPictures = _this.objectToArray(response.data.data);
                })
                .catch(function (error) {
                })
            },

            getBatchProduct(batchProductId){
                var _this = this;
                axios.get('/api/getBatchProduct/'+batchProductId)
                .then(function (response) {
                    _this.product = response.data.data;
                })
                .catch(function (error) {
                    _this.product = {};
                })
            },

            getThisBatchProductPictures( productId ){
                var _this = this;
                return this.batchProductPictures.filter(function(productpicture){
                    return productpicture.productId == productId
                }).map(function(productpicture) {
                    return productpicture.productPicturePath
                })
            },

            getThisProductPictures( productId ){

                var _this = this;
                axios.get('/api/getSingleProductPictures/'+productId)
                .then(function (response) {
                    _this.productpictures = _this.objectToArray(response.data.data);
                    _this.productpictures = _this.productpictures.filter(function(productpicture){
                                                return productpicture.productId == productId
                                            }).map(function(productpicture) {
                                                return productpicture.productPicturePath
                                            })

                })
                .catch(function (error) {
                })

            },



            clearForm(){
                this.product= { batchProductId:'', productId: '', purchasePrice:'', sellingPrice:'' , purchaseQty: '', isStockOut: 0, manufactureDate:null, expireDate:'', batchDate:'', contact:'', representative:'', supplierId:'' }
                this.productError=  { batchProductId:'', productId: '', purchasePrice:'', sellingPrice:'' , purchaseQty: '', isStockOut: 0, manufactureDate:null, expireDate:'', batchDate:'', contact:'', representative:'', supplierId:'' }

                this.product={};
                this.productpictures=[]
            },

            adding(){
                console.log(this.product)
                var _this=this
                axios.post('/api/addBatchProduct/'+'?token='+this.token, this.product)
                .then(function (response) {
                    _this.getBatchProducts()
                    _this.s_alert = true;
                    _this.clearForm()
                    _this.addingConfirmed=''


                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.productError = { productId: error.response.data.productId };
                })
            },

            editRecord(batchProductId){
                this.clearForm()
                console.log('batchProductId = '+ batchProductId)
                this.getBatchProduct(batchProductId)
                this.goToTop()
            },

            deleting(){
                console.log(this.deletingId)
                var _this = this;
                axios.post('/api/deleteBatchProduct/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    // _this.getproducts()
                    _this.getBatchProducts()

                    _this.s_alert = true;
                    _this.clearForm()
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },

            goToTop(){
                this.$vuetify.goTo(0)
            },

        },
        computed: {
            formattedManufactureDate: {
                get(){
                    return this.formatDate(this.product.manufactureDate);
                },
                set(val){
                    this.product.manufactureDate = this.formatDate(val);
                }
            },
            formattedExpireDate: {
                get(){
                    return this.formatDate(this.product.expireDate);
                },
                set(val){
                    this.product.expireDate = this.formatDate(val);
                }
            },
            formattedBatchDate: {
                get(){
                    return this.formatDate(this.product.batchDate);
                },
                set(val){
                    this.product.batchDate = this.formatDate(val);
                }
            },
        },

    }
</script>

