<template>


    <div>
        <v-sheet
            class=" ma-2"
            elevation="0"
            color="grey lighten-5"
        >

            <v-content>
                <v-card elevation="2" >
                    <v-card-title primary-title >
                        <v-spacer></v-spacer>
                        Discount Products
                        <v-spacer></v-spacer>
                    </v-card-title>

                    <v-card-text>

                        <v-row>


                            <v-dialog v-model="dialog_crud" max-width="500px"  @keydown.esc="close()"  @keydown.enter="crudConfirm()" persistent>
                                <template v-slot:activator="{ on }">
                                    <v-btn color="primary" dark class="mb-2 ml-5" v-on="on">Add a new {{ formTitle }}</v-btn>
                                </template>
                                <v-card>
                                    <v-card-title>
                                        <v-spacer></v-spacer>
                                        <span class="headline"> <span v-if="!editingId">Add a new </span><span v-if="editingId">Edit </span><strong>{{ formTitle }}</strong></span>
                                        <v-spacer></v-spacer>
                                    </v-card-title>

                                    <v-form @submit.prevent="crudConfirm()">
                                        <v-card-text>
                                            <v-container>
                                                <v-row>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-autocomplete
                                                            label="Product*"
                                                            :items="products"
                                                            item-text="productName"
                                                            item-value="productId"
                                                            dense
                                                            filled
                                                            v-model="discountproduct.productId"
                                                            clearable
                                                        >
                                                        </v-autocomplete>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="discountProductPercent" label="Percent (%)*"
                                                            v-model="discountproduct.discountProductPercent"
                                                            type="number"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="discountProductAmount" label="Amount*"
                                                            v-model="discountproduct.discountProductAmount"
                                                            type="number"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-menu
                                                            v-model="discountProductExpireDateMenu"
                                                            transition="scale-transition"
                                                            offset-y
                                                            max-width="290px"
                                                            min-width="290px"
                                                            :close-on-content-click="false"
                                                        >
                                                            <template v-slot:activator="{ on }">
                                                                <v-text-field
                                                                    label="Expire Date*"
                                                                    append-icon="event"
                                                                    v-model="formattedDiscountProductExpireDate"
                                                                    v-on="on"
                                                                ></v-text-field>
                                                            </template>

                                                            <v-date-picker
                                                            v-model="discountproduct.discountProductExpireDate"
                                                            no-title
                                                            dense
                                                            >
                                                                <v-spacer></v-spacer>
                                                                <v-btn @click="discountProductExpireDateMenu=false" icon>Ok</v-btn>
                                                            </v-date-picker>
                                                        </v-menu>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-textarea
                                                        v-model="discountproduct.discountReason"
                                                        auto-grow  outlined  rows="4"
                                                        counter
                                                        clearable
                                                        pt-2
                                                        label="Discount Reason"

                                                        ></v-textarea>
                                                    </v-col>


                                                </v-row>

                                            </v-container>
                                        </v-card-text>

                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn color="blue darken-1" text @click="close()">Cancel</v-btn>
                                            <v-btn color="blue darken-1" text type="submit">Save</v-btn>
                                        </v-card-actions>
                                    </v-form>
                                </v-card>
                            </v-dialog>


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
                        :items="discountproducts"
                        :search="search"
                        >

                            <template v-slot:[`item.discountProductExpireDate`]="{ item }">
                                    <v-list-item v-if="item.discountProductExpireDate"> {{formatDate(item.discountProductExpireDate)}}</v-list-item>
                            </template>

                            <template v-slot:[`item.product`]="{ item }">
                                    <v-list-item > {{item.productName}}</v-list-item>
                                    <v-list-item v-if="item.qty && item.uom"> {{item.qty+' '+item.uom}}</v-list-item>
                            </template>

                            <template v-slot:[`item.discountProductPercent`]="{ item }">
                                    {{item.discountProductPercent}}<span v-if="item.discountProductPercent>0">%</span>
                            </template>


                            <template v-slot:[`item.action`]="{ item }">

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="editDialog(item.discountProductId)" v-on="on">edit</v-icon>
                                    </template>
                                    <span>Edit Record ?</span>
                                </v-tooltip>

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="deleteConfirm(item.discountProductId)" v-on="on">delete</v-icon>
                                    </template>
                                    <span>Delete Record ?</span>
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

        <zoom_modal
            :zoomDialog="zoomDialog"
            :zoomPath="zoomPath"
            @cancelZoom="cancelZoom()"
        ></zoom_modal>
    </div>


</template>


<script>
    import subheading from './subheading.vue'
    import modal_alert from './../../frontend/components/modals/alert.vue'
    import modal_alert_mixin from './../../frontend/components/modals/alertMixins.vue'

    // zoom
    import zoom_modal from './../../frontend/components/zoom/zoom.vue'
    import zoom_mixin from './../../frontend/components/zoom/zoomMixins.vue'

    // pic
    import picture_mixin from './../../mixins/picture.vue'

    export default {
        components:{
            subheading, modal_alert, zoom_modal
        },
        mounted() {
            this.getDiscountProducts();
            this.getProducts();
        },
        mixins: [modal_alert_mixin, picture_mixin, zoom_mixin],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'Id',
                        align: 'start',
                        // sortable: false,
                        value: 'discountProductId',
                    },
                    { text: 'Product', value: 'product' },
                    { text: 'Percent(%)', value: 'discountProductPercent' },
                    { text: 'Amount', value: 'discountProductAmount' },
                    { text: 'Expire Date', value: 'discountProductExpireDate' },
                    { text: 'Reason', value: 'discountReason' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                discountproducts: [],

                // add dialog
                formTitle: 'Discount Product',
                discountproduct: {
                    discountProductId:'', productId:'',  discountProductPercent: '', discountProductAmount:'', discountProductExpireDate: '', discountReason: ''
                },
                discountproductError: {
                    discountProductId:'', productId:'',  discountProductPercent: '', discountProductAmount:'', discountProductExpireDate: '', discountReason: ''
                },
                discountProductExpireDateMenu:false,
                products:[],



            }
        },
        methods: {

            getProducts(){
                var _this = this;
                axios.get('/api/getProducts')
                .then(function (response) {
                    _this.products = response.data.data;
                })
                .catch(function (error) {
                })
            },

            getDiscountProducts(){
                var _this = this;
                axios.get('/api/getDiscountProducts')
                .then(function (response) {
                    _this.discountproducts = response.data.data.reverse();
                })
                .catch(function (error) {
                })
            },
            clearForm(){
                this.discountproduct= {discountProductId:'', productId:'',  discountProductPercent: '', discountProductAmount:'', discountProductExpireDate: '', discountReason: '' }
                this.discountproductError= {discountProductId:'', productId:'',  discountProductPercent: '', discountProductAmount:'', discountProductExpireDate: '', discountReason: '' }
            },

            adding(){
                var _this=this
                axios.post('/api/addDiscountProduct'+'?token='+this.token, this.discountproduct)
                .then(function (response) {
                    _this.getDiscountProducts()
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.clearForm()
                    _this.addingConfirmed=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.discountproductError = {productId: error.response.data.productId, discountProductPercent: error.response.data.discountProductPercent, discountProductAmount: error.response.data.discountProductAmount, discountProductExpireDate: error.response.data.discountProductExpireDate, };
                })
            },

            deleting(){
                var _this = this;
                axios.post('/api/deleteDiscountProduct/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    _this.getDiscountProducts()

                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },

            setEditDialogFields(id){
                var _this = this;
                axios.get('/api/getDiscountProduct/'+id)
                .then(function (response) {
                    _this.discountproduct = response.data.data;
                })
                .catch(function (error) {
                })
            },

            editing(){
                var _this = this;
                axios.post('/api/editDiscountProduct?token='+this.token, this.discountproduct)
               .then(function (response) {
                    _this.s_alert = true;

                    _this.dialog_crud = false
                    _this.getDiscountProducts()
                    _this.clearForm()
                    _this.editingId=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.discountproductError = {discountProductPercent: error.response.data.discountProductPercent, discountProductAmount: error.response.data.discountProductAmount, discountProductExpireDate: error.response.data.discountProductExpireDate, };
                })
            },

        },
        computed: {
            formattedDiscountProductExpireDate: {
                get(){
                    return this.formatDate(this.discountproduct.discountProductExpireDate);
                },
                set(val){
                    this.discountproduct.discountProductExpireDate = this.formatDate(val);
                }
            },
        },
        watch: {
            'discountproduct.discountProductPercent': {
                handler: function (after, before) {
                    console.log('discountproduct.discountProductPercent before= '+before)
                    console.log('discountproduct.discountProductPercent after= '+after)
                    if(after > 0)
                    {
                        this.discountproduct.discountProductAmount=''
                    }
                },
                deep: true
            },
            'discountproduct.discountProductAmount': {
                handler: function (after, before) {
                    console.log('discountproduct.discountProductAmount before= '+before)
                    console.log('discountproduct.discountProductAmount after= '+after)
                    if(after > 0)
                    {
                        this.discountproduct.discountProductPercent=''
                    }
                },
                deep: true
            }
        }
    }
</script>

