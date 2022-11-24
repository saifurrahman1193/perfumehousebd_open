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
                        Discount Products Customers
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
                                                            label="Customer*"
                                                            :items="users"
                                                            item-text="name"
                                                            item-value="id"
                                                            dense
                                                            filled
                                                            v-model="discountproductcustomer.customerId"
                                                            clearable
                                                        >
                                                        </v-autocomplete>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-autocomplete
                                                            label="Product*"
                                                            :items="products"
                                                            item-text="productName"
                                                            item-value="productId"
                                                            dense
                                                            filled
                                                            v-model="discountproductcustomer.productId"
                                                            clearable
                                                        >
                                                        </v-autocomplete>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="discountProductCustomerPercent" label="Percent (%)*"
                                                            v-model="discountproductcustomer.discountProductCustomerPercent"
                                                            type="number"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="discountProductCustomerAmount" label="Amount*"
                                                            v-model="discountproductcustomer.discountProductCustomerAmount"
                                                            type="number"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-menu
                                                            v-model="discountProductCustomerExpireDateMenu"
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
                                                                    v-model="formattedDiscountProductCustomerExpireDate"
                                                                    v-on="on"
                                                                ></v-text-field>
                                                            </template>

                                                            <v-date-picker
                                                            v-model="discountproductcustomer.discountProductCustomerExpireDate"
                                                            no-title
                                                            dense
                                                            >
                                                                <v-spacer></v-spacer>
                                                                <v-btn @click="discountProductCustomerExpireDateMenu=false" icon>Ok</v-btn>
                                                            </v-date-picker>
                                                        </v-menu>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-textarea
                                                        v-model="discountproductcustomer.discountReason"
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
                        :items="discountproductcustomers"
                        :search="search"
                        >

                            <template v-slot:[`item.customer`]="{ item }">
                                    <v-list-item > {{item.customerName}}</v-list-item>
                                    <v-list-item > {{item.customerEmail}}</v-list-item>
                            </template>

                            <template v-slot:[`item.discountProductCustomerPercent`]="{ item }">
                                    {{item.discountProductCustomerPercent}}<span v-if="item.discountProductCustomerPercent>0">%</span>
                            </template>

                            <template v-slot:[`item.discountProductCustomerExpireDate`]="{ item }">
                                    <v-list-item v-if="item.discountProductCustomerExpireDate"> {{formatDate(item.discountProductCustomerExpireDate)}}</v-list-item>
                            </template>

                            <template v-slot:[`item.product`]="{ item }">
                                    <v-list-item > {{item.productName}}</v-list-item>
                                    <v-list-item v-if="item.qty && item.uom"> {{item.qty+' '+item.uom}}</v-list-item>
                            </template>


                            <template v-slot:[`item.action`]="{ item }">

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="editDialog(item.discountProductCustomerId)" v-on="on">edit</v-icon>
                                    </template>
                                    <span>Edit Record ?</span>
                                </v-tooltip>

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="deleteConfirm(item.discountProductCustomerId)" v-on="on">delete</v-icon>
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
            this.getDiscountProductCustomers();
            this.getProducts();
            this.getUsers();

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
                        value: 'discountProductCustomerId',
                    },
                    { text: 'Customer', value: 'customer' },
                    { text: 'Product', value: 'product' },
                    { text: 'Percent(%)', value: 'discountProductCustomerPercent' },
                    { text: 'Amount', value: 'discountProductCustomerAmount' },
                    { text: 'Expire Date', value: 'discountProductCustomerExpireDate' },
                    { text: 'Reason', value: 'discountReason' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                discountproductcustomers: [],

                // add dialog
                formTitle: 'Discount Product Customer',
                discountproductcustomer: {
                    discountProductCustomerId:'', productId:'',customerId:'',  discountProductCustomerPercent: '', discountProductCustomerAmount:'', discountProductCustomerExpireDate: '', discountReason: ''
                },
                discountproductcustomerError: {
                    discountProductCustomerId:'', productId:'',customerId:'',  discountProductCustomerPercent: '', discountProductCustomerAmount:'', discountProductCustomerExpireDate: '', discountReason: ''
                },
                discountProductCustomerExpireDateMenu:false,
                products:[],
                users:[],

            }
        },
        methods: {

            getUsers(){
                var _this = this;
                axios.get('/api/getUsers'+'?token='+ _this.token)
                .then(function (response) {
                    _this.users = response.data.data;
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

            getDiscountProductCustomers(){
                var _this = this;
                axios.get('/api/getDiscountProductCustomers')
                .then(function (response) {
                    _this.discountproductcustomers = response.data.data.reverse();
                })
                .catch(function (error) {
                })
            },
            clearForm(){
                this.discountproductcustomer= {discountProductCustomerId:'', productId:'',customerId:'',  discountProductCustomerPercent: '', discountProductCustomerAmount:'', discountProductCustomerExpireDate: '', discountReason: '' }
                this.discountproductcustomerError= {discountProductCustomerId:'', productId:'',customerId:'',  discountProductCustomerPercent: '', discountProductCustomerAmount:'', discountProductCustomerExpireDate: '', discountReason: '' }
            },

            adding(){
                var _this=this
                axios.post('/api/addDiscountProductCustomer'+'?token='+this.token, this.discountproductcustomer)
                .then(function (response) {
                    _this.getDiscountProductCustomers()
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.clearForm()
                    _this.addingConfirmed=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.discountproductcustomerError = {productId: error.response.data.productId, discountProductCustomerPercent: error.response.data.discountProductCustomerPercent, discountProductCustomerAmount: error.response.data.discountProductCustomerAmount, discountProductCustomerExpireDate: error.response.data.discountProductCustomerExpireDate, };
                })
            },

            deleting(){
                var _this = this;
                axios.post('/api/deleteDiscountProductCustomer/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    _this.getDiscountProductCustomers()

                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },

            setEditDialogFields(id){
                var _this = this;
                axios.get('/api/getDiscountProductCustomer/'+id)
                .then(function (response) {
                    _this.discountproductcustomer = response.data.data;
                })
                .catch(function (error) {
                })
            },

            editing(){
                var _this = this;
                axios.post('/api/editDiscountProductCustomer?token='+this.token, this.discountproductcustomer)
               .then(function (response) {
                    _this.s_alert = true;

                    _this.dialog_crud = false
                    _this.getDiscountProductCustomers()
                    _this.clearForm()
                    _this.editingId=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.discountproductcustomerError = {discountProductCustomerPercent: error.response.data.discountProductCustomerPercent, discountProductCustomerAmount: error.response.data.discountProductCustomerAmount, discountProductCustomerExpireDate: error.response.data.discountProductCustomerExpireDate, };
                })
            },

        },
        computed: {
            formattedDiscountProductCustomerExpireDate: {
                get(){
                    return this.formatDate(this.discountproductcustomer.discountProductCustomerExpireDate);
                },
                set(val){
                    this.discountproductcustomer.discountProductCustomerExpireDate = this.formatDate(val);
                }
            },
        },
        watch: {
            'discountproductcustomer.discountProductCustomerPercent': {
                handler: function (after, before) {
                    console.log('discountproductcustomer.discountProductCustomerPercent before= '+before)
                    console.log('discountproductcustomer.discountProductCustomerPercent after= '+after)
                    if(after > 0)
                    {
                        this.discountproductcustomer.discountProductCustomerAmount=''
                    }
                },
                deep: true
            },
            'discountproductcustomer.discountProductCustomerAmount': {
                handler: function (after, before) {
                    console.log('discountproductcustomer.discountProductCustomerAmount before= '+before)
                    console.log('discountproductcustomer.discountProductCustomerAmount after= '+after)
                    if(after > 0)
                    {
                        this.discountproductcustomer.discountProductCustomerPercent=''
                    }
                },
                deep: true
            }
        }
    }
</script>

