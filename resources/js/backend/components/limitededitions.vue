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
                        Add a new Limited Edition
                        <v-spacer></v-spacer>
                    </v-card-title>
                    <v-form @submit.prevent="crudConfirm()">
                        <v-card-text>

                            <v-row >
                                <v-col  >
                                    <v-autocomplete
                                        label="Product"
                                        :items="getProductsForLimitedEdition"
                                        item-text="productName"
                                        item-value="productId"
                                        dense
                                        filled
                                        v-model="limitededition.productId"
                                        clearable
                                        @change="getProduct(limitededition.productId)"
                                    >
                                    </v-autocomplete>
                                </v-col>
                            </v-row>

                            <v-row v-if="limitededition.productId">
                                <v-col>
                                    <v-card elevation="0" >
                                        <v-list-item three-line>
                                            <v-list-item-content>
                                                <v-list-item-title>Product Details</v-list-item-title>
                                                <v-list-item-subtitle>{{limitededition.productName}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>{{limitededition.sku}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>{{limitededition.qty}} {{limitededition.uom}}</v-list-item-subtitle>
                                                <v-list-item-subtitle>{{limitededition.brand}}</v-list-item-subtitle>
                                                <v-list-item-subtitle v-html="limitededition.shortDescription"></v-list-item-subtitle>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-card>
                                </v-col>
                            </v-row>

                            <gallery :images="getThisProductPictures(limitededition.productId)"/>


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
                        Limited Editions
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
                        :items="limitededitions"
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
                                        <v-icon @click="deleteConfirm(item.limitedEditionId)" v-on="on">delete</v-icon>
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
            this.getLimitedEditions();
            this.getProducts();
            this.getProductPictures();

        },
        mixins: [modal_alert_mixin],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'Limited Edition Id',
                        align: 'start',
                        value: 'limitedEditionId',
                    },
                    { text: 'Images', value: 'images' },
                    { text: 'Product', value: 'productName' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                limitededitions: [],
                products:[],
                product:{},
                productpictures:[],

                // add dialog
                formTitle: 'Limited Edition',
                limitededition: {
                    productId: ''
                },
                limitededitionError: {
                    productId: ''
                },
                productIdRules: [
                    v => !!v || 'Product is required',
                ],
            }
        },
        methods: {

            getLimitedEditions(){
                var _this = this;
                axios.get('/api/getLimitedEditions')
                .then(function (response) {
                    _this.limitededitions = response.data.data;
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
                this.limitededition = this.products.filter(function(products){
                    return products.productId == productId
                })[0]
            },
            getProductPictures(){
                var _this = this;
                axios.get('/api/getProductPictures')
                .then(function (response) {
                    _this.productpictures = response.data.data;
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
                this.limitededition= { productId: '' }
                this.limitededitionError= { productId: '' }
            },

            adding(){
                console.log(this.limitededition)
                var _this=this
                axios.post('/api/addLimitedEdition/'+'?token='+this.token, this.limitededition)
                .then(function (response) {
                    _this.getLimitedEditions()
                    _this.s_alert = true;
                    _this.clearForm()
                    _this.addingConfirmed=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.limitededitionError = { productId: error.response.data.productId };
                })
            },

            deleting(){
                console.log(this.deletingId)
                var _this = this;
                axios.post('/api/deleteLimitedEdition/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    _this.getLimitedEditions()

                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },


        },
        computed: {
           getProductsForLimitedEdition: function(){
                let limitededitionsfinal=this.products

                this.limitededitions.forEach(limitededition => {
                    limitededitionsfinal = limitededitionsfinal.filter(function(product){
                        return product.productId != limitededition.productId
                    })
                });
                return limitededitionsfinal;
            }
        }
    }
</script>

