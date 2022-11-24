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
                        Suppliers
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
                                                        <v-text-field name="supplier" label="Supplier*" v-model="supplier.supplier"
                                                            :rules="supplierRules"
                                                            :error-messages="supplierError.supplier"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field
                                                            label="Phone"
                                                            name="phone"
                                                            id="phone"
                                                            v-model="supplier.phone"
                                                            type="text"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-textarea name="description" label="Description"
                                                        v-model="supplier.description"
                                                        auto-grow  outlined  rows="2"
                                                        :rules="descriptionRules"
                                                        :error-messages="supplierError.description"
                                                        counter
                                                        clearable

                                                        ></v-textarea>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-textarea name="address" label="Address"
                                                        v-model="supplier.address"
                                                        auto-grow  outlined  rows="2"
                                                        :rules="addressRules"
                                                        :error-messages="supplierError.address"
                                                        counter
                                                        clearable

                                                        ></v-textarea>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field
                                                            label="Email"
                                                            name="email"
                                                            id="email"
                                                            v-model="supplier.email"
                                                            :rules="emailRules"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col>
                                                        <v-btn raised @click="onPickFile('logoPathInput')">Upload Image</v-btn>
                                                        <v-tooltip bottom>
                                                            <template v-slot:activator="{ on }">
                                                                    <v-btn text v-on="on" @click="cancelSingleImage($event, 'supplier','logoPath')" >
                                                                        <v-icon    color="pink">cancel</v-icon>
                                                                    </v-btn>
                                                            </template>
                                                            <span>Close image</span>
                                                        </v-tooltip>
                                                        <input type="file" ref="logoPathInput"
                                                            accept="image/*"
                                                            @change="onFilePickedFromObj($event, 'supplier','logoPath')" class="d-none">
                                                        <v-img v-if="supplier.logoPath" :src="supplier.logoPath" max-height="190" min-height="190"  aspect-ratio contain class="mt-5" ></v-img>
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
                        :items="suppliers"
                        :search="search"
                        >

                            <template v-slot:[`item.logoPath`]="{ item }">
                                    <v-img
                                        :src="item.logoPath || '/uploads/no_image.png'"
                                        lazy-src="/uploads/loader.gif"
                                        max-height="70"
                                        max-width="70"
                                        aspect-ratio contain
                                        @click="imageZoom(item.logoPath)"
                                        class="image-hover-cursor-change"
                                    ></v-img>
                            </template>


                            <template v-slot:[`item.action`]="{ item }">

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="editDialog(item.supplierId)" v-on="on">edit</v-icon>
                                    </template>
                                    <span>Edit Record?</span>
                                </v-tooltip>

                                <v-tooltip top  v-if="item.isDeletable==1">
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="deleteConfirm(item.supplierId)" v-on="on">delete</v-icon>
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
    import picture_mixin from './../../mixins/picture.vue'

    // zoom
    import zoom_modal from './../../frontend/components/zoom/zoom.vue'
    import zoom_mixin from './../../frontend/components/zoom/zoomMixins.vue'

    export default {
        components:{
            subheading, modal_alert, zoom_modal
        },
        mounted() {
            this.getSuppliers();
        },
        mixins: [modal_alert_mixin, picture_mixin, zoom_mixin],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'Supplier Id',
                        align: 'start',
                        // sortable: false,
                        value: 'supplierId',
                    },
                    { text: 'Logo', value: 'logoPath' , sortable: false,},
                    { text: 'Supplier', value: 'supplier' },
                    { text: 'Phone', value: 'phone' },
                    { text: 'Email', value: 'email' },
                    { text: 'Description', value: 'description' },
                    { text: 'Address', value: 'address' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                categories: [],
                suppliers: [],
                uoms: [],

                // add dialog
                formTitle: 'Supplier',
                supplier: {
                    supplier: '',  description: '',address: '',  phone: '', email: '', logoPath: ''
                },
                supplierError: {
                     supplier: '',  description: '',address: '',  phone: '', email: '', logoPath: ''
                },
                supplierRules: [
                    v => !!v || 'Supplier is required',
                ],
                descriptionRules: [
                    v => v.length <= 500 || 'Max 500 characters',
                ],
                addressRules: [
                    v => v.length <= 500 || 'Max 500 characters',
                ],
                emailRules: [
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
            }
        },
        methods: {

            getSuppliers(){
                var _this = this;
                axios.get('/api/getSuppliers')
                .then(function (response) {
                    _this.suppliers = response.data.data;
                })
                .catch(function (error) {
                })
            },
            clearForm(){
                this.supplier= { supplier: '',  description: '',address: '',  phone: '', email: '', logoPath: '' }
                this.supplierError= { supplier: '',  description: '',address: '',  phone: '', email: '', logoPath: '' }
            },

            adding(){
                console.log(this.supplier)
                var _this=this
                axios.post('/api/addSupplier/'+'?token='+this.token, this.supplier)
                .then(function (response) {
                    _this.getSuppliers()
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.clearForm()
                    _this.addingConfirmed=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.supplierError = { supplier: error.response.data.supplier };
                })
            },

            deleting(){
                console.log(this.deletingId)
                var _this = this;
                axios.post('/api/deleteSupplier/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    _this.getSuppliers()

                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },

            setEditDialogFields(id){
                var _this = this;
                axios.get('/api/getSupplier/'+id)
                .then(function (response) {
                    _this.supplier = response.data.data;
                })
                .catch(function (error) {
                })
            },

            editing(){
                var _this = this;
                axios.post('/api/editSupplier?token='+this.token, this.supplier)
               .then(function (response) {
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.getSuppliers()
                    _this.clearForm()
                    _this.editingId=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.supplierError = { supplier: error.response.data.supplier };
                })
            },


        },
        computed: {

        }
    }
</script>

