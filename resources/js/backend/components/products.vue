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
                        Products
                        <v-spacer></v-spacer>
                    </v-card-title>

                    <v-card-text>

                        <v-row>


                            <v-dialog v-model="dialog_crud" max-width="600px"  @keydown.esc="close()"  @keydown.enter="crudConfirm()" persistent>
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
                                                        <v-text-field name="productName" label="Product*" v-model="product.productName"
                                                            :rules="productNameRules"
                                                            :error-messages="productError.productName"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <!-- <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="sku" label="SKU*" v-model="product.sku"
                                                            :rules="skuRules"
                                                            :error-messages="productError.sku"
                                                        ></v-text-field>
                                                    </v-col> -->


                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-card>
                                                            <v-card-title primary-title>
                                                                Description
                                                            </v-card-title>
                                                            <v-card-text>
                                                                <tiptap-vuetify
                                                                    v-model="product.description"
                                                                    :extensions="extensions"
                                                                    placeholder="Description"
                                                                ></tiptap-vuetify>
                                                                <v-textarea
                                                                v-model="product.description"
                                                                auto-grow  outlined  rows="2"
                                                                :rules="descriptionRules"
                                                                :error-messages="productError.description"
                                                                counter
                                                                clearable
                                                                pt-2

                                                                ></v-textarea>
                                                            </v-card-text>
                                                        </v-card>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-card>
                                                            <v-card-title primary-title>
                                                                Short description
                                                            </v-card-title>
                                                            <v-card-text>
                                                                <tiptap-vuetify
                                                                    v-model="product.shortDescription"
                                                                    :extensions="extensions"
                                                                    placeholder="Short description"
                                                                ></tiptap-vuetify>

                                                                <v-textarea
                                                                v-model="product.shortDescription"
                                                                auto-grow  outlined  rows="2"
                                                                :rules="shortDescriptionRules"
                                                                :error-messages="productError.shortDescription"
                                                                counter
                                                                clearable
                                                                pt-2

                                                                ></v-textarea>
                                                            </v-card-text>


                                                        </v-card>
                                                    </v-col>


                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-autocomplete
                                                            label="Category"
                                                            :items="categories"
                                                            item-text="category"
                                                            item-value="categoryId"
                                                            dense
                                                            filled
                                                            id="categoryIds"
                                                            name="categoryIds"
                                                            v-model="product.categoryIds"
                                                            multiple
                                                            chips
                                                            clearable
                                                        ></v-autocomplete>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-text-field name="qty" label="Qty" v-model="product.qty"
                                                            type="number"
                                                            :rules="qtyRules"
                                                            :error-messages="productError.qty"
                                                        ></v-text-field>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-autocomplete
                                                            label="Uom"
                                                            :items="uoms"
                                                            item-text="uom"
                                                            item-value="uomId"
                                                            dense
                                                            filled
                                                            id="uomId"
                                                            name="uomId"
                                                            v-model="product.uomId"
                                                            chips
                                                            clearable
                                                        ></v-autocomplete>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-autocomplete
                                                            label="Brand"
                                                            :items="brands"
                                                            item-text="brand"
                                                            item-value="brandId"
                                                            dense
                                                            filled
                                                            id="brandId"
                                                            name="brandId"
                                                            v-model="product.brandId"
                                                            chips
                                                            clearable
                                                        ></v-autocomplete>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-autocomplete
                                                            label="Country of origin"
                                                            :items="countries"
                                                            item-text="country"
                                                            item-value="countryId"
                                                            dense
                                                            filled
                                                            id="countryId"
                                                            name="countryId"
                                                            v-model="product.countryId"
                                                            chips
                                                            clearable
                                                        ></v-autocomplete>
                                                    </v-col>


                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-autocomplete
                                                            label="Formulation"
                                                            :items="formulations"
                                                            item-text="formulation"
                                                            item-value="formulationId"
                                                            dense
                                                            filled
                                                            id="formulationId"
                                                            name="formulationId"
                                                            v-model="product.formulationId"
                                                            chips
                                                            clearable
                                                        ></v-autocomplete>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-autocomplete
                                                            label="Product Type"
                                                            :items="producttypes"
                                                            item-text="productType"
                                                            item-value="productTypeId"
                                                            dense
                                                            filled
                                                            id="productTypeId"
                                                            name="productTypeId"
                                                            v-model="product.productTypeId"
                                                            chips
                                                            clearable
                                                        ></v-autocomplete>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-autocomplete
                                                            label="Gender"
                                                            :items="genders"
                                                            item-text="gender"
                                                            item-value="genderId"
                                                            dense
                                                            filled
                                                            id="genderId"
                                                            name="genderId"
                                                            v-model="product.genderId"
                                                            chips
                                                            clearable
                                                        ></v-autocomplete>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-autocomplete
                                                            label="Skin Type"
                                                            :items="skintypes"
                                                            item-text="skinType"
                                                            item-value="skinTypeId"
                                                            dense
                                                            filled
                                                            id="skinTypeId"
                                                            name="skinTypeId"
                                                            v-model="product.skinTypeId"
                                                            chips
                                                            clearable
                                                        ></v-autocomplete>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-autocomplete
                                                            label="Application Area"
                                                            :items="applicationareas"
                                                            item-text="applicationArea"
                                                            item-value="applicationAreaId"
                                                            dense
                                                            filled
                                                            id="applicationAreaId"
                                                            name="applicationAreaId"
                                                            v-model="product.applicationAreaId"
                                                            chips
                                                            clearable
                                                        ></v-autocomplete>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-autocomplete
                                                            label="Gift Types"
                                                            :items="gifttypes"
                                                            item-text="giftType"
                                                            item-value="giftTypeId"
                                                            dense
                                                            filled
                                                            id="giftTypeIds"
                                                            name="giftTypeIds"
                                                            v-model="product.giftTypeIds"
                                                            multiple
                                                            chips
                                                        ></v-autocomplete>
                                                    </v-col>

                                                    <v-col cols="12" sm="12" md="12">
                                                        <v-textarea name="searchTags" label="Search Tags"
                                                        v-model="product.searchTags"
                                                        auto-grow  outlined  rows="2"
                                                        :rules="searchTagsRules"
                                                        :error-messages="productError.searchTags"
                                                        counter
                                                        clearable

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
                                class="mb-2"
                            ></v-text-field>
                        </v-row>

                        <v-data-table
                        :headers="headers"
                        :items="products"
                        :search="search"
                        class="gray-table"
                        >

                            <template v-slot:[`item.picPath`]="{ item }" >
                                <addpictures @pictureUploadPost="pictureUploadPost" :id="item.productId" ></addpictures>

                                <v-card
                                    class="mx-auto"
                                    max-width="200"
                                    v-if=" getThisProductPictures(item.productId).length>0"
                                >
                                    <v-list>
                                        <v-list-item-group  multiple color="danger">
                                            <v-list-item
                                            v-for="(productpicture, i) in getThisProductPictures(item.productId)"
                                            :key="i"
                                            >
                                            <v-list-item-icon>
                                                <v-list dense light class="pa-0" >
                                                    <v-list-item class="pa-0">
                                                        <v-tooltip bottom>
                                                            <template v-slot:activator="{ on }">
                                                                <v-icon color="red" v-on="on"
                                                                @click="deleteProductPicture(productpicture.productPictureId)"
                                                                >delete</v-icon>
                                                            </template>
                                                            <span>Delete picture?</span>
                                                        </v-tooltip>
                                                    </v-list-item>
                                                    <v-list-item class="pa-0">
                                                        <v-tooltip bottom>
                                                            <template v-slot:activator="{ on }">
                                                                <v-icon color="blue" v-on="on" v-if="productpicture.isDefault==1"
                                                                @click="setProductPictureDefault(productpicture.productPictureId)"
                                                                >check_circle</v-icon>

                                                                <v-icon color="grey lighten-1" v-on="on"
                                                                @click="setProductPictureDefault(productpicture.productPictureId)"
                                                                v-else
                                                                >check_circle_outline</v-icon>
                                                            </template>
                                                            <span v-if="productpicture.isDefault==1">This picture is default for product {{item.productName}}</span>
                                                            <span v-else>Make it default?</span>
                                                        </v-tooltip>
                                                    </v-list-item>
                                                </v-list>

                                            </v-list-item-icon>

                                            <v-list-item-content>
                                                <v-img :src="productpicture.productPicturePath" max-height="190" max-width="190"  aspect-ratio contain
                                                @click="imageZoom(productpicture.productPicturePath)"
                                                style="cursor: zoom-in;"
                                                ></v-img>
                                            </v-list-item-content>
                                            </v-list-item>
                                        </v-list-item-group>
                                    </v-list>
                                </v-card>
                            </template>


                            <template v-slot:[`item.categories`]="{ item }">
                                <v-list dense>
                                    <v-list-item-title class="pl-1"
                                        v-for="(productwithcategory, index) in getProductWithCategories(item.productId)"  :key="index">
                                        {{productwithcategory.category}}
                                    </v-list-item-title>
                                </v-list>
                            </template>


                            <template v-slot:[`item.giftTypes`]="{ item }">
                                <v-list dense>
                                    <v-list-item-title class="pl-1"
                                        v-for="(gifttype, index) in getGiftTypeOfSingleProduct(item.productId)"
                                        :key="index"
                                        >{{gifttype.giftType}}
                                    </v-list-item-title>
                                </v-list>
                            </template>



                            <template v-slot:[`item.shortDescription`]="{ item }">
                                <readmoreless :text="item.shortDescription" ></readmoreless>
                            </template>
                            <template v-slot:[`item.description`]="{ item }">
                                <readmoreless :text="item.description" ></readmoreless>
                            </template>



                            <template v-slot:[`item.batch`]="{ item }">
                                <v-simple-table  dense v-if="getBatchProductsFromProductId(item.productId).length>0">
                                    <template v-slot:default >
                                        <thead >
                                            <tr>
                                                <th class="text-left">Batch Product Id</th>
                                                <th class="text-left">Supplier</th>
                                                <th class="text-left">Availability</th>
                                                <th class="text-left">Purchase Qty</th>
                                                <th class="text-left">Purchase Price</th>
                                                <th class="text-left">Selling Price</th>
                                                <th class="text-left">Manufacture Date</th>
                                                <th class="text-left">Expire Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row, index) in getBatchProductsFromProductId(item.productId)" :key="index">
                                                <td>{{ row.batchProductId }}</td>
                                                <td>{{ row.Supplier }}</td>
                                                <td>
                                                    <v-chip  class="ma-2" color="red" text-color="white" v-if="row.isStockOut>0" x-small>Out of stock</v-chip>
                                                    <v-chip  class="ma-2" color="green" text-color="white" v-else x-small>Available</v-chip>
                                                </td>
                                                <td>{{ row.qty }}</td>
                                                <td>{{ row.purchasePrice }}</td>
                                                <td>{{ row.sellingPrice }}</td>
                                                <td>{{ row.manufactureDate }}</td>
                                                <td>{{ row.expireDate }}</td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </template>


                            <template v-slot:[`item.generalinfo`]="{ item }">
                                <v-simple-table  dense >
                                    <template v-slot:default >

                                        <tbody>
                                            <tr>
                                                <th>Qty</th>
                                                <td>{{ item.qty }}</td>
                                            </tr>
                                            <tr>
                                                <th>UOM</th>
                                                <td>{{ item.uom }}</td>
                                            </tr>
                                            <tr>
                                                <th>Gender</th>
                                                <td>{{ item.gender }}</td>
                                            </tr>
                                            <tr>
                                                <th>Product Type</th>
                                                <td>{{ item.productType }}</td>
                                            </tr>
                                            <tr>
                                                <th>Formulation</th>
                                                <td>{{ item.formulation }}</td>
                                            </tr>
                                            <tr>
                                                <th>Skin Type</th>
                                                <td>{{ item.skinType }}</td>
                                            </tr>
                                            <tr>
                                                <th>Country</th>
                                                <td>{{ item.country }}</td>
                                            </tr>
                                            <tr>
                                                <th>Application Area</th>
                                                <td>{{ item.applicationArea }}</td>
                                            </tr>
                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </template>


                            <template v-slot:[`item.isfeaturedproduct`]="{ item }">
                                <isfeaturedproduct :isFeaturedProduct="item.isFeaturedProduct" :productId="item.productId" @getProducts="getProducts()"></isfeaturedproduct>
                            </template>


                            <template v-slot:[`item.action`]="{ item }">

                                <v-tooltip top>
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="editDialog(item.productId)" v-on="on">edit</v-icon>
                                    </template>
                                    <span>Edit Record?</span>
                                </v-tooltip>

                                <v-tooltip top >
                                    <template v-slot:activator="{ on }">
                                        <v-icon @click="deleteConfirm(item.productId)" v-on="on">delete</v-icon>
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
    import addpictures from './addpictures/addpictures.vue'
    import isfeaturedproduct from './isfeaturedproduct.vue'

    import readmoreless from './../../components/readmoreless.vue'


    import modal_alert from './../../frontend/components/modals/alert.vue'
    import modal_alert_mixin from './../../frontend/components/modals/alertMixins.vue'
    import picture_mixin from './../../mixins/picture.vue'

    // zoom
    import zoom_modal from './../../frontend/components/zoom/zoom.vue'
    import zoom_mixin from './../../frontend/components/zoom/zoomMixins.vue'

    // tiptap-vuetify
    import titptap_vuetify_editor_mixin from './../../mixins/titptap_vuetify_editor.vue'

    export default {
        components:{
            subheading,addpictures,  modal_alert, zoom_modal ,readmoreless, isfeaturedproduct
        },
        mounted() {
            this.getProducts();
            this.getCategories();
            this.getUoms();
            this.getBatchProducts();
            this.getBrands();
            this.getCountries();
            this.getFormulations();
            this.getProductTypes();
            this.getGenders();
            this.getSkinTypes();
            this.getApplicationAreas();
            this.getProductPictures();
            this.getProductsWithCategories()
            this.getGiftTypes();
            this.getGiftProducts();

        },
        mixins: [modal_alert_mixin, picture_mixin, zoom_mixin, titptap_vuetify_editor_mixin],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'Product Id',
                        align: 'start',
                        // sortable: false,
                        value: 'productId',
                    },
                    { text: 'Pic', value: 'picPath' , sortable: false,},
                    { text: 'Product', value: 'productName' , width:200},
                    { text: 'Is Featured Product?', value: 'isfeaturedproduct' , width:200},
                    // { text: 'SKU', value: 'sku' },

                    { text: 'Categories', value: 'categories' , width:150},
                    { text: 'Gift Types', value: 'giftTypes' , width:150},


                    { text: 'General Informations', value: 'generalinfo' , width:300},


                    { text: 'Short Description', value: 'shortDescription', width:300 },
                    { text: 'Description', value: 'description', width:300 },
                    { text: 'Batch Information', value: 'batch', sortable: false },
                    { text: 'Search Tags', value: 'searchTags', sortable: false, width:200 },
                    { text: 'Actions', value: 'action', sortable: false, width:90 },
                ],
                categories: [],
                products: [],
                productpictures:[],

                productBatches: [],
                uoms: [],
                brands:[],
                countries:[],
                formulations:[],
                producttypes:[],
                genders:[],
                skintypes:[],
                applicationareas:[],

                giftproducts:[],


                // add dialog
                formTitle: 'Product',
                product: {
                    productName: '',
                    shortDescription: '',
                    description: '',

                    categoryIds: '',

                    uomId: '',
                    qty: '',
                    brandId:'',
                    productTypeId:'',
                    countryId:'',
                    applicationAreaId:'',
                    skinTypeId:'',
                    formulationId:'',
                    genderId:'',
                    searchTags:'',
                    giftTypeIds:''
                },
                productError: {
                    productName: '',
                    shortDescription: '',
                    description: '',
                    uomId: '',
                    qty: '',
                    brandId:'',
                    productTypeId:'',
                    countryId:'',
                    applicationAreaId:'',
                    skinTypeId:'',
                    formulationId:'',
                    genderId:'',
                    searchTags:'',
                    giftTypeIds:''

                },
                products_with_categories:[],

                gifttypes: [],


                productNameRules: [
                    v => !!v || 'Product is required',
                    v => {
                        var matched = false;
                        this.products.forEach(product => {
                            if(product.productName==v)
                            {
                                matched = true;
                            }
                        });

                        if (!matched) {
                            return true;
                        }

                        return 'Duplicate product name already exist! still want to add?'
                    },
                ],
                // skuRules: [
                //     v => !!v || 'SKU is required',
                // ],
                descriptionRules: [
                    v => v.length <= 30000 || 'Max 30000 characters',
                ],
                shortDescriptionRules: [
                    v => v.length <= 1000 || 'Max 1000 characters',
                ],
                qtyRules:[
                    v  => {
                        if (!v.trim()) return true;
                        if (!isNaN(parseFloat(v)) && v >= 0 ) return true;
                        return 'Number has to be greater than 0';
                    },
                ],
                searchTagsRules: [
                    v => v.length <= 1000 || 'Max 1000 characters',
                ],

            }
        },
        methods: {

            getBatchProducts(){
                var _this = this;
                axios.get('/api/getBatchProducts')
                .then(function (response) {
                    _this.productBatches = response.data.data;
                })
                .catch(function (error) {
                })
            },
            getCategories(){
                var _this = this;
                axios.get('/api/getCategories')
                .then(function (response) {
                    _this.categories = response.data.data;
                })
                .catch(function (error) {
                })
            },
            getUoms(){
                var _this = this;
                axios.get('/api/getUoms')
                .then(function (response) {
                    _this.uoms = response.data.data;
                })
                .catch(function (error) {
                })
            },
            getBrands(){
                var _this = this;
                axios.get('/api/getBrands')
                .then(function (response) {
                    _this.brands = response.data.data;
                })
                .catch(function (error) {
                })
            },
            getCountries(){
                var _this = this;
                axios.get('/api/getCountries')
                .then(function (response) {
                    _this.countries = response.data.data;
                })
                .catch(function (error) {
                })
            },

            getFormulations(){
                var _this = this;
                axios.get('/api/getFormulations')
                .then(function (response) {
                    _this.formulations = response.data.data;
                })
                .catch(function (error) {
                })
            },

            getProductTypes(){
                var _this = this;
                axios.get('/api/getProductTypes')
                .then(function (response) {
                    _this.producttypes = response.data.data;
                })
                .catch(function (error) {
                })
            },

            getApplicationAreas(){
                var _this = this;
                axios.get('/api/getApplicationAreas')
                .then(function (response) {
                    _this.applicationareas = response.data.data;
                })
                .catch(function (error) {
                })
            },

            getGenders(){
                var _this = this;
                axios.get('/api/getGenders')
                .then(function (response) {
                    _this.genders = response.data.data;
                })
                .catch(function (error) {
                })
            },

            getSkinTypes(){
                var _this = this;
                axios.get('/api/getSkinTypes')
                .then(function (response) {
                    _this.skintypes = response.data.data;
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
            getProductPictures(){
                var _this = this;
                axios.get('/api/getProductPictures')
                .then(function (response) {
                    _this.productpictures = response.data.data;
                })
                .catch(function (error) {
                })
            },
            clearForm(){
                this.product= { productName: '',  description: '',  shortDescription: '', uomId: '', qty: ''}
                this.productError= { productName: '',  description: '',  shortDescription: '', uomId: '', qty: ''}
            },

            adding(){
                console.log(this.product)
                var _this=this
                axios.post('/api/addProduct/'+'?token='+this.token, this.product)
                .then(function (response) {
                    _this.getProducts()
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.clearForm()
                    _this.addingConfirmed=''

                    _this.getProductsWithCategories()
                    _this.getGiftProducts()

                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.productError = { productName: error.response.data.productName };
                })
            },

            deleting(){
                console.log(this.deletingId)
                var _this = this;
                axios.post('/api/deleteProduct/'+this.deletingId+'?token='+this.token)
                .then(function (response) {
                    _this.getProducts()

                    _this.s_alert = true;
                    _this.getGiftProducts()
                })
                .catch(function (error) {
                    _this.e_alert = true;
                })
                this.deletingId=''
            },

            setEditDialogFields(id){
                var _this = this;
                axios.get('/api/getProduct/'+id)
                .then(function (response) {
                    _this.product = response.data.data;
                    _this.product.categoryIds = _this.getProductCategories(id)

                    _this.product.giftTypeIds = _this.getGiftTypeOfSingleProduct(id)
                })
                .catch(function (error) {
                })
            },

            editing(){
                var _this = this;
                axios.post('/api/editProduct?token='+this.token, this.product)
               .then(function (response) {
                    _this.s_alert = true;
                    _this.dialog_crud = false
                    _this.getProducts()
                    _this.clearForm()
                    _this.editingId=''

                    _this.getProductsWithCategories()
                    _this.getGiftProducts()
                })
                .catch(function (error) {
                    _this.e_alert = true;
                    _this.productError = { productName: error.response.data.productName };
                })
            },
            getBatchProductsFromProductId(productId){
                return this.productBatches.filter(function(productbatch){
                    return productbatch.productId == productId
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

            getThisProductPictures( productId ){
                return this.productpictures.filter(function(productpicture){
                    return productpicture.productId == productId
                })

            },

            deleteProductPicture(productPictureId){
                if (confirm('Do you really want to proceed?')) {
                    var _this = this;
                    axios.post('/api/deleteProductPicture/'+productPictureId+'?token='+this.token)
                    .then(function (response) {
                         _this.getProducts()
                        _this.getProductPictures()
                        _this.s_alert = true;
                    })
                    .catch(function (error) {
                        _this.e_alert = true;
                    })
                }
            },

            setProductPictureDefault(productPictureId){

                var _this = this;
                axios.post('/api/setProductPictureDefault/'+productPictureId+'?token='+this.token)
                .then(function (response) {
                    _this.getProductPictures()
                })
                .catch(function (error) {
                })

            },

            getProductsWithCategories(){
                var _this = this;
                axios.get('/api/getProductCategories')
                .then(function (response) {
                    _this.products_with_categories = response.data.data;
                })
                .catch(function (error) {
                })
            },

            getProductCategories(productId){
                return this.products_with_categories.filter(function(productcategory){
                    return productcategory.productId == productId
                })
                .map(function(productcategory) {
                    return productcategory.categoryId;
                });
            },

            getProductWithCategories(productId){
                return this.products_with_categories.filter(function(productcategory){
                    return productcategory.productId == productId
                });
            },

            getGiftTypes(){
                var _this = this;
                axios.get('/api/getGiftTypes')
                .then(function (response) {
                    _this.gifttypes = response.data.data;
                })
                .catch(function (error) {
                })
            },

            getGiftProducts(){
                var _this = this;
                axios.get('/api/getGiftProducts')
                .then(function (response) {
                    _this.giftproducts = response.data.data;
                })
                .catch(function (error) {
                })
            },

            getGiftTypeOfSingleProduct(productId){
                return this.giftproducts.filter(function(giftproduct){
                    return giftproduct.productId == productId
                });
            },

        },


    }
</script>

