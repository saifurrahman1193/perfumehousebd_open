<template>
    <div>
        <v-card
            class="mx-auto"
            tile
        >
            <v-list rounded dense>
                <v-list-item-group  color="success">

                    <v-list-item   v-if="isDeliveryAddressSet">
                        <v-row>
                            <v-col xs="12" sm="8" md="10" >
                                <strong class="title">{{deliveryaddress.deliveryAddressTitle}}</strong>
                                <br>
                                {{deliveryaddress.deliveryAddress}}
                                <span v-if="deliveryaddress.area" class="font-weight-bold">{{' ('+deliveryaddress.area+')'}}</span>
                            </v-col>
                            <v-col xs="8" sm="4" md="2" class="font-weight-bold my-auto">
                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-btn @click="clearForm()" v-on="on" text class="text-capitalize text-danger" >Change Address</v-btn>
                                    </template>
                                    <span>Edit delivery address?</span>
                                </v-tooltip>
                            </v-col>
                        </v-row>
                    </v-list-item>
                    <div v-if="!isDeliveryAddressSet">
                        <v-list-item v-for="item in deliveryaddresses"  :key="item.deliveryAddressId" @click="setDeliveryAddress(item.deliveryAddressId)"  >
                            <v-row>
                                <v-col cols="10"
                                    @mouseover="snackbar = true"
                                    @mouseleave="snackbar = false"
                                    @click="snackbar = false"
                                    >
                                    <strong class="title">{{item.deliveryAddressTitle}}</strong>
                                    <br>
                                    {{item.deliveryAddress}}
                                    <span v-if="item.area" class="font-weight-bold">{{' ('+item.area+')'}}</span>
                                </v-col>
                                <v-col  cols="1" class="font-weight-bold my-auto">
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-icon @click="editDialog(item.deliveryAddressId)" v-on="on" color="teal lighten-2">edit</v-icon>
                                        </template>
                                        <span>Edit delivery address?</span>
                                    </v-tooltip>
                                    <v-tooltip top>
                                        <template v-slot:activator="{ on }">
                                            <v-icon @click="deleteConfirm(item.deliveryAddressId)" v-on="on" color="red lighten-2">delete</v-icon>
                                        </template>
                                        <span>Delete delivery address?</span>
                                    </v-tooltip>
                                </v-col>
                            </v-row>
                        </v-list-item>

                        <v-list-item >
                            <v-row>
                                <v-col  class="text-center" @click="dialog_crud=true"><v-icon>add</v-icon> Create New Address</v-col>
                            </v-row>
                        </v-list-item>

                    </div>


                </v-list-item-group>
            </v-list>
        </v-card>

        <v-dialog v-model="dialog_crud" max-width="500px"  @keydown.esc="close()"  @keydown.enter="crudConfirm()" persistent >
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
                                    <v-text-field name="deliveryAddressTitle" label="Delivery Address Title*" v-model="deliveryaddress.deliveryAddressTitle"
                                        :rules="deliveryAddressTitleRules"
                                        :error-messages="deliveryaddressError.deliveryAddressTitle"
                                        clearable
                                        placeholder="Ex: Home, Office, School"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" sm="12" md="12">
                                    <v-textarea name="deliveryAddress" label="Delivery Address*"
                                    v-model="deliveryaddress.deliveryAddress"
                                    auto-grow  outlined  rows="3"
                                    :rules="deliveryAddressRules"
                                    :error-messages="deliveryaddressError.deliveryAddress"
                                    counter
                                    clearable
                                    placeholder="Road-5, Block-A, House-05, Flat-A2, lift-2, ..."

                                    ></v-textarea>
                                </v-col>

                                <v-col cols="12" sm="12" md="12">
                                    <v-autocomplete
                                        label="Area*"
                                        :items="areas"
                                        item-text="area"
                                        item-value="areaId"
                                        dense
                                        filled
                                        id="areaId"
                                        name="areaId"
                                        v-model="deliveryaddress.areaId"
                                        clearable
                                        chips
                                        :rules="areaIdRules"
                                        :error-messages="deliveryaddressError.areaId"
                                    ></v-autocomplete>
                                </v-col>

                            </v-row>
                        </v-container>
                    </v-card-text>

                    <v-divider v-if="loading"></v-divider>
                    <p v-if="loading" class="text-danger mt-1 red--text lighten-1 text-center">Loading... Please wait.</p>

                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="close()">Cancel</v-btn>
                        <v-btn color="blue darken-1" text type="submit">Save</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>


        <modal_alert
            :s_alert="s_alert"
            s_msg="Successfully saved!"
            :e_alert="e_alert"
            e_msg="Something went wrong!"
            :w_alert="w_alert"
            w_msg="Do you really want to proceed?"
            :i_alert="i_alert"
            i_msg="Information "
            @cancelAlert="cancelAlert()"
            @cancelAlertAndProceed="cancelAlertAndProceed()"

        ></modal_alert>

        <v-snackbar v-model="snackbar" timeout.number="10000"> Please click to select a delivery address </v-snackbar>


    </div>
</template>


<script>

    import modal_alert from './modals/alert.vue'
    import modal_alert_mixin from './modals/alertMixins.vue'


export default {
    components:{
            modal_alert
        },
    mounted() {
        this.getCurrentUserDeliveryAddresses();
        this.getAreas();
        this.loading=false;

    },
    mixins: [modal_alert_mixin],
    data() {
        return {
            token:localStorage.getItem('token'),
            dialog_crud:false,
            deliveryaddresses:[],
            // add dialog
            formTitle: 'Delivery Address',
            deliveryaddress: {
                deliveryAddressId: localStorage.getItem('deliveryAddressId') || '',
                deliveryAddressTitle: '',
                deliveryAddress: '',
                areaId: '',
            },
            areas:[],
            deliveryaddressError: {
                deliveryAddressTitle: '',
                deliveryAddress: '',
                areaId: '',
            },
            deliveryAddressTitleRules: [
                v => !!v || 'Title is required',
            ],
            deliveryAddressRules: [
                v => !!v || 'Address is required',
            ],
            areaIdRules: [
                v => !!v || 'Area is required',
            ],
            snackbar: false,
            loading:true,
        }
    },

    methods: {
        getCurrentUserDeliveryAddresses(){
            var _this = this;
            axios.get('/api/getCurrentUserDeliveryAddresses?token='+this.token)
            .then(function (response) {
                _this.deliveryaddresses = response.data.data;
            })
            .catch(function (error) {
            })
        },
        getAreas(){
            var _this = this;
            axios.get('/api/getAreas')
            .then(function (response) {
                _this.areas = response.data.data;
            })
            .catch(function (error) {
            })
        },
        clearForm(){
            this.deliveryaddress= {
                deliveryAddressTitle: '',
                deliveryAddress: '',
                areaId: '',
            }
            this.deliveryaddressError= {
                deliveryAddressTitle: '',
                deliveryAddress: '',
                areaId: '',
            }

            localStorage.setItem('deliveryAddressId', '')
            this.$store.commit('SET_DELIVERYADDRESS', this.deliveryaddress)

        },
        adding(){
            this.loading=true;

            console.log('called ')
            var _this=this
            axios.post('/api/addCurrentUserDeliveryAddress?token='+this.token, this.deliveryaddress)
            .then(function (response) {
                _this.getCurrentUserDeliveryAddresses()
                // _this.s_alert = true;
                _this.dialog_crud = false
                _this.clearForm()
                _this.addingConfirmed=''

                _this.loading=false;
            })
            .catch(function (error) {
                _this.e_alert = true;
                _this.deliveryaddressError = {
                    deliveryAddressTitle: error.response.data.deliveryAddressTitle,
                    deliveryAddress: error.response.data.deliveryAddress,
                    areaId: error.response.data.areaId,
                 };

                _this.loading=false;
            })
        },

        setEditDialogFields(id){
            var _this = this;
            axios.get('/api/getDeliveryAddress/'+id+'?token='+this.token)
            .then(function (response) {
                _this.deliveryaddress = response.data.data;
            })
            .catch(function (error) {
            })
        },

        editing(){
            var _this = this;
            axios.post('/api/editDeliveryAddress?token='+this.token, this.deliveryaddress)
            .then(function (response) {
                // _this.s_alert = true;

                _this.dialog_crud = false
                _this.getCurrentUserDeliveryAddresses()
                _this.clearForm()
                _this.editingId=''

            })
            .catch(function (error) {
                _this.e_alert = true;
                _this.deliveryaddressError = {
                    deliveryAddressTitle: error.response.data.deliveryAddressTitle,
                    deliveryAddress: error.response.data.deliveryAddress,
                    areaId: error.response.data.areaId,
                 };
            })
        },

        deleting(){
            console.log(this.deletingId)
            var _this = this;
            axios.post('/api/deleteDeliveryAddress/'+this.deletingId+'?token='+this.token)
            .then(function (response) {
                _this.getCurrentUserDeliveryAddresses()

                // _this.s_alert = true;
                _this.clearForm()
            })
            .catch(function (error) {
                _this.e_alert = true;
            })
            _this.deletingId=''
        },

        setDeliveryAddress(deliveryAddressId){
            console.log('deliveryAddressId = '+ deliveryAddressId)
            console.log(this.deliveryaddresses)
            var _this = this;
            this.deliveryaddresses.forEach(element => {
                console.log(element)

                if (element.deliveryAddressId == deliveryAddressId) {
                    _this.deliveryaddress= {
                        deliveryAddressId: element.deliveryAddressId,
                        deliveryAddressTitle: element.deliveryAddressTitle,
                        deliveryAddress: element.deliveryAddress,
                        areaId: element.areaId,
                        area: element.area,
                    }

                    console.log(_this.deliveryaddress)
                }
            });
        },

    },
    computed: {
        isDeliveryAddressSet : function() {
            if (this.deliveryaddress.deliveryAddressId)
            {
                localStorage.setItem('deliveryAddressId', this.deliveryaddress.deliveryAddressId)
                if (!this.editingId) {
                    this.setDeliveryAddress(this.deliveryaddress.deliveryAddressId)
                }
                this.$store.commit('SET_DELIVERYADDRESS', this.deliveryaddress)
                return true;
            }
            else {
                return false;
            }
        },
        deliveeryaddressChange(){
            console.log(this.deliveryaddress.deliveryAddressTitle)
            return this.deliveryaddress.deliveryAddressTitle
        }

    },

}
</script>

