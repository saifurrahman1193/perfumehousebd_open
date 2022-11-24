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
                        Services
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
                                                    <v-text-field name="service" label="Service" v-model="service.service"
                                                        :rules="serviceRules"
                                                        :error-messages="serviceError.service"
                                                    ></v-text-field>
                                                </v-col>

                                                <v-col cols="12" sm="12" md="12">
                                                    <v-textarea name="description" label="Description" v-model="service.description" auto-grow  outlined  rows="2"></v-textarea>
                                                </v-col>

                                                <v-col>
                                                    <v-btn raised @click="onPickFile('picPathInput')">Upload Image</v-btn>
                                                    <input type="file" ref="picPathInput"
                                                        accept="image/*"
                                                        @change="onFilePickedFromObj($event, 'service','picPath')" class="d-none">

                                                    <v-img v-if="service.picPath" :src="service.picPath" max-height="190" min-height="190" aspect-ratio contain class="mt-5" ></v-img>
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
                        :items="services"
                        :search="search"
                        >
                            <template v-slot:item.picPath="{ item }">
                                    <v-img
                                        :src="item.picPath || '/uploads/no_image.png'"
                                        lazy-src="/uploads/loader.gif"
                                        max-height="70"
                                        max-width="70"
                                        aspect-ratio contain
                                        @click="imageZoom(item.picPath)"
                                        class="image-hover-cursor-change"
                                    ></v-img>
                            </template>

                            <template v-slot:item.action="{ item }">

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="editDialog(item.serviceId)" v-on="on">edit</v-icon>
                                    </template>
                                    <span>Edit Record ?</span>
                                </v-tooltip>

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="deleteConfirm(item.serviceId)" v-on="on">delete</v-icon>
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
            this.getServices();
        },
        mixins: [modal_alert_mixin, picture_mixin, zoom_mixin],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'Service Id',
                        align: 'start',
                        // sortable: false,
                        value: 'serviceId',
                    },
                    { text: 'Pic', value: 'picPath' , sortable: false,},
                    { text: 'Service', value: 'service' },
                    { text: 'Description', value: 'description' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                services: [],

                // add dialog
                formTitle: 'Service',
                service: {
                    service: '',
                    description: '',
                    picPath: '',
                },
                serviceError: {
                    service: '',
                    description: '',
                    picPath: '',
                },
                serviceRules: [
                    v => !!v || 'Service is required',
                ],

            }
        },
        methods: {

            getServices(){
                var _this = this;
                axios.get('/api/getServices')
                .then(function (response) {
                    _this.services = response.data.data;
                })
                .catch(function (error) {
                })
            },
            clearForm(){
                this.service= { service: '',  description: '', picPath:'' }
                this.serviceError= { service: '' }
            },

            adding(){
                console.log(this.service)
                var _this=this
                axios.post('/api/addService/'+'?token='+this.token, this.service)
                .then(function (response) {
                    _this.getServices()
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.clearForm()
                    _this.addingConfirmed=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.serviceError = { service: error.response.data.service };
                })
            },

            deleting(){
                console.log(this.deletingId)
                var _this = this;
                axios.post('/api/deleteService/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    _this.getServices()

                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },

            setEditDialogFields(id){
                var _this = this;
                axios.get('/api/getService/'+id)
                .then(function (response) {
                    _this.service = response.data.data;
                })
                .catch(function (error) {
                })
            },

            editing(){
                var _this = this;
                axios.post('/api/editService?token='+this.token, this.service)
               .then(function (response) {
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.getServices()
                    _this.clearForm()
                    _this.editingId=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.serviceError = { service: error.response.data.service };
                })
            },


        },
        computed: {

        }
    }
</script>

