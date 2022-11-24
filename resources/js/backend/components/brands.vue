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
                        Brand
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
                                                        <v-text-field name="brand" label="Brand*" v-model="brand.brand"
                                                            :rules="brandRules"
                                                            :error-messages="brandError.brand"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="brandSlug" label="Brand Slug*" v-model="brand.brandSlug"
                                                            :rules="brandSlugRules"
                                                            :error-messages="brandError.brandSlug"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col>
                                                        <v-btn raised @click="onPickFile('logoInput')">Upload Image</v-btn>
                                                        <v-tooltip bottom>
                                                            <template v-slot:activator="{ on }">
                                                                    <v-btn text v-on="on" @click="cancelSingleImage($event, 'brand','logo')" >
                                                                        <v-icon    color="pink">cancel</v-icon>
                                                                    </v-btn>
                                                            </template>
                                                            <span>Close image</span>
                                                        </v-tooltip>
                                                        <input type="file" ref="logoInput"
                                                            accept="image/*"
                                                            @change="onFilePickedFromObj($event, 'brand','logo')" class="d-none">
                                                        <v-img v-if="brand.logo" :src="brand.logo" max-height="190" min-height="190"  aspect-ratio contain class="mt-5" ></v-img>
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
                        :items="brands"
                        :search="search"
                        >

                                <template v-slot:[`item.logo`]="{ item }">
                                    <v-img
                                        :src="item.logo || '/uploads/no_image.png'"
                                        lazy-src="/uploads/loader.gif"
                                        max-height="70"
                                        max-width="70"
                                        aspect-ratio contain
                                        @click="imageZoom(item.logo)"
                                        class="image-hover-cursor-change"
                                    ></v-img>
                                </template>


                            <template v-slot:[`item.action`]="{ item }">



                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="editDialog(item.brandId)" v-on="on">edit</v-icon>
                                    </template>
                                    <span>Edit Record ?</span>
                                </v-tooltip>

                                <v-tooltip top  v-if="item.isDeletable==1">
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="deleteConfirm(item.brandId)" v-on="on">delete</v-icon>
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
            this.getBrands();
        },
        mixins: [modal_alert_mixin, picture_mixin, zoom_mixin],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'Brand Id',
                        align: 'start',
                        // sortable: false,
                        value: 'brandId',
                    },
                    { text: 'Logo', value: 'logo' , sortable: false,},
                    { text: 'Brand', value: 'brand' },
                    { text: 'Slug', value: 'brandSlug' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                brands: [],

                // add dialog
                formTitle: 'Brand',
                brand: {
                    brand: '', brandSlug:'', logo: ''
                },
                brandError: {
                    brand: '', brandSlug:'', logo: ''
                },
                brandRules: [
                    v => !!v || 'Brand name is required',
                ],
                brandSlugRules: [
                    v => !!v || 'Brand slug is required',
                ],
            }
        },
        methods: {

            getBrands(){
                var _this = this;
                axios.get('/api/getBrands')
                .then(function (response) {
                    _this.brands = response.data.data;
                })
                .catch(function (error) {
                })
            },
            clearForm(){
                this.brand= { brand: '' }
                this.brandError= { brand: '' }
            },

            adding(){
                console.log(this.brand)
                var _this=this
                axios.post('/api/addBrand/'+'?token='+this.token, this.brand)
                .then(function (response) {
                    _this.getBrands()
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.clearForm()
                    _this.addingConfirmed=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.brandError = { brand: error.response.data.brand };
                })
            },

            deleting(){
                console.log(this.deletingId)
                var _this = this;
                axios.post('/api/deleteBrand/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    _this.getBrands()

                    _this.s_alert = true;
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },

            setEditDialogFields(id){
                var _this = this;
                axios.get('/api/getBrand/'+id)
                .then(function (response) {
                    _this.brand = response.data.data;
                })
                .catch(function (error) {
                })
            },

            editing(){
                var _this = this;
                axios.post('/api/editBrand?token='+this.token, this.brand)
               .then(function (response) {
                    _this.s_alert = true;

                    _this.dialog_crud = false
                    _this.getBrands()
                    _this.clearForm()
                    _this.editingId=''
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.brandError = { brand: error.response.data.brand };
                })
            },

        },
        watch: {
            'brand.brandSlug': {
                handler(val){
                    this.brand.brandSlug = this.string_to_slug(this.brand.brandSlug)
                },
                deep: true
            }
        }
    }
</script>

