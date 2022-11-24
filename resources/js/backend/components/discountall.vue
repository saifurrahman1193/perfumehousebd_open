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
                        Discount All
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
                                                        <v-text-field name="discountAllPercent" label="Percent (%)*"
                                                            v-model="discountall.discountAllPercent"
                                                            type="number"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="discountAllAmount" label="Amount*"
                                                            v-model="discountall.discountAllAmount"
                                                            type="number"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-menu
                                                            v-model="discountAllExpireDateMenu"
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
                                                                    v-model="formattedDiscountAllExpireDate"
                                                                    v-on="on"
                                                                ></v-text-field>
                                                            </template>

                                                            <v-date-picker
                                                            v-model="discountall.discountAllExpireDate"
                                                            no-title
                                                            dense
                                                            >
                                                                <v-spacer></v-spacer>
                                                                <v-btn @click="discountAllExpireDateMenu=false" icon>Ok</v-btn>
                                                            </v-date-picker>
                                                        </v-menu>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-textarea
                                                        v-model="discountall.discountReason"
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
                        :items="discountalls"
                        :search="search"
                        >

                            <template v-slot:[`item.discountAllPercent`]="{ item }">
                                    {{item.discountAllPercent}}<span v-if="item.discountAllPercent>0">%</span>
                            </template>

                            <template v-slot:[`item.discountAllExpireDate`]="{ item }">
                                    <v-list-item v-if="item.discountAllExpireDate"> {{formatDate(item.discountAllExpireDate)}}</v-list-item>
                            </template>



                            <template v-slot:[`item.action`]="{ item }">

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="editDialog(item.discountAllId)" v-on="on">edit</v-icon>
                                    </template>
                                    <span>Edit Record ?</span>
                                </v-tooltip>

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="deleteConfirm(item.discountAllId)" v-on="on">delete</v-icon>
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
            this.getDiscountAlls();
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
                        value: 'discountAllId',
                    },
                    { text: 'Percent(%)', value: 'discountAllPercent' },
                    { text: 'Amount', value: 'discountAllAmount' },
                    { text: 'Expire Date', value: 'discountAllExpireDate' },
                    { text: 'Reason', value: 'discountReason' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                discountalls: [],

                // add dialog
                formTitle: 'Discount All',
                discountall: {
                    discountAllId:'', discountAllPercent: '', discountAllAmount:'', discountAllExpireDate: '', discountReason: ''
                },
                discountallError: {
                    discountAllId:'', discountAllPercent: '', discountAllAmount:'', discountAllExpireDate: '', discountReason: ''
                },
                discountAllExpireDateMenu:false,


            }
        },
        methods: {

            getDiscountAlls(){
                var _this = this;
                axios.get('/api/getDiscountAlls')
                .then(function (response) {
                    _this.discountalls = response.data.data.reverse();
                })
                .catch(function (error) {
                })
            },
            clearForm(){
                this.discountall= {discountAllId:'', discountAllPercent: '', discountAllAmount:'', discountAllExpireDate: '', discountReason: '' }
                this.discountallError= {discountAllId:'', discountAllPercent: '', discountAllAmount:'', discountAllExpireDate: '', discountReason: '' }
            },

            adding(){
                var _this=this
                axios.post('/api/addDiscountAll'+'?token='+this.token, this.discountall)
                .then(function (response) {
                    _this.getDiscountAlls()
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.clearForm()
                    _this.addingConfirmed=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.discountallError = {discountAllPercent: error.response.data.discountAllPercent, discountAllAmount: error.response.data.discountAllAmount, discountAllExpireDate: error.response.data.discountAllExpireDate, };
                })
            },

            deleting(){
                var _this = this;
                axios.post('/api/deleteDiscountAll/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    _this.getDiscountAlls()

                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },

            setEditDialogFields(id){
                var _this = this;
                axios.get('/api/getDiscountAll/'+id)
                .then(function (response) {
                    _this.discountall = response.data.data;
                })
                .catch(function (error) {
                })
            },

            editing(){
                var _this = this;
                axios.post('/api/editDiscountAll?token='+this.token, this.discountall)
               .then(function (response) {
                    _this.s_alert = true;

                    _this.dialog_crud = false
                    _this.getDiscountAlls()
                    _this.clearForm()
                    _this.editingId=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.discountallError = {discountAllPercent: error.response.data.discountAllPercent, discountAllAmount: error.response.data.discountAllAmount, discountAllExpireDate: error.response.data.discountAllExpireDate, };
                })
            },

        },
        computed: {
            formattedDiscountAllExpireDate: {
                get(){
                    return this.formatDate(this.discountall.discountAllExpireDate);
                },
                set(val){
                    this.discountall.discountAllExpireDate = this.formatDate(val);
                }
            },
        },
        watch: {
            'discountall.discountAllPercent': {
                handler: function (after, before) {
                    // console.log('discountall.discountAllPercent before= '+before)
                    // console.log('discountall.discountAllPercent after= '+after)
                    if(after > 0)
                    {
                        this.discountall.discountAllAmount=''
                    }
                },
                deep: true
            },
            'discountall.discountAllAmount': {
                handler: function (after, before) {
                    // console.log('discountall.discountAllAmount before= '+before)
                    // console.log('discountall.discountAllAmount after= '+after)
                    if(after > 0)
                    {
                        this.discountall.discountAllPercent=''
                    }
                },
                deep: true
            }
        }
    }
</script>

