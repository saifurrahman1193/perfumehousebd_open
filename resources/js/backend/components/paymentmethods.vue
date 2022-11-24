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
                        Payment Methods
                        <v-spacer></v-spacer>
                    </v-card-title>

                    <v-card-text>

                        <v-row>


                            <v-dialog v-model="dialog_crud" max-width="500px"  @keydown.esc="close()"  @keydown.enter="crudConfirm()" persistent>
                                <!-- <template v-slot:activator="{ on }">
                                    <v-btn color="primary" dark class="mb-2 ml-5" v-on="on">Add a new {{ formTitle }}</v-btn>
                                </template> -->
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
                                                        <v-text-field name="paymentMethod" label="Payment Method*" v-model="paymentmethod.paymentMethod"
                                                            :rules="paymentMethodRules"
                                                            :error-messages="paymentmethodError.paymentMethod"
                                                            readonly
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="accountNumber" label="Account Number" v-model="paymentmethod.accountNumber"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="option" label="Option" v-model="paymentmethod.option"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="accountType" label="Account Type" v-model="paymentmethod.accountType"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-textarea
                                                        v-model="paymentmethod.instruction"
                                                        auto-grow  outlined  rows="3"
                                                        counter
                                                        clearable
                                                        pt-2
                                                        label="Instruction"

                                                        ></v-textarea>
                                                    </v-col>



                                                    <v-col cols="12" sm="12" md="6">
                                                        <v-switch v-model="paymentmethod.isVisible" inset :label="paymentmethod.isVisible==1 ? 'Visible' : 'Hidden'" false-value="0" true-value="1" color="green"></v-switch>
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
                        :items="paymentmethods"
                        :search="search"
                        >

                            <template v-slot:[`item.qrCode`]="{ item }">
                                <addpictures @pictureUploadPost="pictureUploadPost" :id="item.paymentMethodId" v-if="(item.qrCode||'').length==0" ></addpictures>
                                <v-card
                                    class="mx-auto"
                                    max-width="200"
                                    v-if="(item.qrCode||'').length>0"
                                >
                                    <v-list>
                                        <v-list-item>
                                            <v-list-item-icon>
                                                <v-tooltip bottom>
                                                    <template v-slot:activator="{ on }">
                                                        <v-icon color="red" v-on="on"
                                                        @click="deleteProductPicture(item.paymentMethodId)"
                                                        >delete</v-icon>
                                                    </template>
                                                    <span>Delete picture?</span>
                                                </v-tooltip>
                                            </v-list-item-icon>

                                            <v-list-item-content>
                                                <v-img :src="item.qrCode" max-height="190" max-width="190"  aspect-ratio contain
                                                @click="imageZoom(item.qrCode)"
                                                style="cursor: zoom-in;"
                                                ></v-img>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list>
                                </v-card>
                            </template>

                            <template v-slot:[`item.accountinfo`]="{ item }">

                                <v-list class="pa-0">
                                    <v-list-item-group>
                                        <v-list-item dense>
                                            <v-row>
                                                <v-col>Account Number</v-col>
                                                <v-col>{{item.accountNumber}}</v-col>
                                            </v-row>
                                        </v-list-item>
                                        <v-list-item dense>
                                            <v-row>
                                                <v-col>Account Type</v-col>
                                                <v-col>{{item.accountType}}</v-col>
                                            </v-row>
                                        </v-list-item>
                                        <v-list-item dense>
                                            <v-row>
                                                <v-col>Option</v-col>
                                                <v-col>{{item.option}}</v-col>
                                            </v-row>
                                        </v-list-item>
                                        <v-list-item dense>
                                            <v-row>
                                                <v-col>Instruction</v-col>
                                                <v-col>{{item.instruction}}</v-col>
                                            </v-row>
                                        </v-list-item>

                                        <v-list-item dense>
                                            <v-row>
                                                <v-col>Visibility</v-col>
                                                <v-col :style="'color:'+(item.isVisible==1 ? 'Green' : 'Red')">{{item.isVisible==1 ? "Visible" : "Hidden"}}</v-col>
                                            </v-row>
                                        </v-list-item>

                                    </v-list-item-group>
                                </v-list>

                            </template>


                            <template v-slot:[`item.action`]="{ item }">

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="editDialog(item.paymentMethodId)" v-on="on">edit</v-icon>
                                    </template>
                                    <span>Edit Record ?</span>
                                </v-tooltip>

                                <!-- <v-tooltip top v-if="item.isDeletable==1">
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="deleteConfirm(item.paymentMethodId)" v-on="on">delete</v-icon>
                                    </template>
                                    <span>Delete Record ?</span>
                                </v-tooltip> -->

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


        <v-spacer></v-spacer>

    </v-app>
</template>


<script>
    import subheading from './subheading.vue'
    import modal_alert from './../../frontend/components/modals/alert.vue'
    import modal_alert_mixin from './../../frontend/components/modals/alertMixins.vue'

    // pic
    import picture_mixin from './../../mixins/picture.vue'

    // zoom
    import zoom_modal from './../../frontend/components/zoom/zoom.vue'
    import zoom_mixin from './../../frontend/components/zoom/zoomMixins.vue'

    import addpictures from './addpictures/addpictures.vue'


    export default {
        components:{
            subheading, modal_alert, zoom_modal, addpictures
        },
        mounted() {
            this.getPaymentMethods();
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
                        value: 'paymentMethodId',
                    },
                    { text: 'QR Code', value: 'qrCode' , sortable: false,},
                    { text: 'Payment method', value: 'paymentMethod' },
                    { text: 'Account Info', value: 'accountinfo' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                paymentmethods: [],

                // add dialog
                formTitle: 'Payment method',
                paymentmethod: {
                    paymentMethod: '', accountNumber:'', accountType:'', option:'', instruction:'', isVisible: 0
                },
                paymentmethodError: {
                    paymentMethod: '', accountNumber:'', accountType:'', option:'', instruction:'', isVisible: 0
                },
                paymentMethodRules: [
                    v => !!v || 'Payment method is required',
                ],
            }
        },
        methods: {

            getPaymentMethods(){
                var _this = this;
                axios.get('/api/getPaymentMethods')
                .then(function (response) {
                    _this.paymentmethods = response.data.data;
                })
                .catch(function (error) {
                })
            },
            clearForm(){
                this.paymentmethod= { paymentMethod: '', accountNumber:'', accountType:'', option:'', instruction:'', isVisible: 0 }
                this.paymentmethodError= { paymentMethod: '', accountNumber:'', accountType:'', option:'', instruction:'', isVisible: 0 }
            },

            adding(){
                var _this=this
                axios.post('/api/addPaymentMethod'+'?token='+this.token, this.paymentmethod)
                .then(function (response) {
                    _this.getPaymentMethods()
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.clearForm()
                    _this.addingConfirmed=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.paymentmethodError = { paymentmethod: error.response.data.paymentMethod };
                })
            },

            deleting(){
                var _this = this;
                axios.post('/api/deletePaymentMethod/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    _this.getPaymentMethods()

                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },

            setEditDialogFields(id){
                var _this = this;
                axios.get('/api/getPaymentMethod/'+id)
                .then(function (response) {
                    _this.paymentmethod = response.data.data;
                })
                .catch(function (error) {
                })
            },

            editing(){
                var _this = this;
                axios.post('/api/editPaymentMethod?token='+this.token, this.paymentmethod)
               .then(function (response) {
                    _this.s_alert = true;

                    _this.dialog_crud = false
                    _this.getPaymentMethods()
                    _this.clearForm()
                    _this.editingId=''

                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.paymentmethodError = { paymentmethod: error.response.data.paymentmethod };
                })
            },
            pictureUploadPost( filePath, productId){

                var _this=this
                axios.post('/api/addProductPicture/'+'?token='+this.token, {"productPicturePath":filePath, "productId": productId})
                .then(function (response) {
                    _this.getProducts()
                    _this.getProductPictures()
                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
            },


        },


    }
</script>

