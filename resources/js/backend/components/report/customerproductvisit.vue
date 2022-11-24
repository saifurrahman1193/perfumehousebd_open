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
                    <v-card-title primary-title  class="justify-center">
                        Customer Product Visit Report
                    </v-card-title>

                    <v-card-text>

                        <v-row>
                            <v-col cols="auto" md="4" sm="6" xs="12">
                                <v-autocomplete
                                    label="Category"
                                    :items="categories"
                                    item-text="category"
                                    item-value="categoryId"
                                    dense
                                    v-model="categoryId"
                                    clearable
                                ></v-autocomplete>
                            </v-col>

                            <v-col  cols="auto" md="4" sm="6" xs="12">
                                <v-autocomplete
                                    label="Customer"
                                    :items="customers"
                                    item-text="customer"
                                    item-value="id"
                                    dense
                                    v-model="customerId"
                                    clearable
                                >
                                    <template v-slot:item="data" >
                                            <v-list-item-avatar>
                                                <img :src="data.item.photoPath" v-if="data.item.photoPath">
                                            </v-list-item-avatar>
                                            <v-list-item-content>
                                                <v-list-item-title v-html="string_to_normalbehaviour((data.item.name)+', ' +(data.item.phone||'') +', ' + (data.item.email||'') +', ' +(data.item.area || '') +', ')"></v-list-item-title>
                                            </v-list-item-content>
                                    </template>
                                </v-autocomplete>
                            </v-col>

                            <v-col  md="4" sm="6" xs="12">
                                <v-autocomplete
                                    label="Products"
                                    :items="products"
                                    item-text="productName"
                                    item-value="productId"
                                    dense
                                    v-model="productId"
                                    clearable
                                ></v-autocomplete>
                            </v-col>

                            <v-col cols="auto" md="4" sm="6" xs="12">
                                <v-select
                                    label="Year"
                                    :items="years"
                                    dense
                                    v-model="year"
                                    @change="year>0 ? startDateEndDateHide() : startDateEndDateShow()"
                                    clearable
                                ></v-select>
                            </v-col>


                            <v-col md="4" sm="6" xs="12" v-if="startDateShow">
                                <v-menu
                                    v-model="startDateMenu"
                                    transition="scale-transition"
                                    offset-y
                                    max-width="290px"
                                    min-width="290px"
                                    :close-on-content-click="false"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            label="Start Date"
                                            append-icon="event"
                                            v-model="formattedStartDate"
                                            v-on="on"
                                            clearable
                                        ></v-text-field>
                                    </template>

                                    <v-date-picker
                                    v-model="startDate"
                                    no-title
                                    dense
                                    >
                                        <v-spacer></v-spacer>
                                        <v-btn @click="startDateMenu=false" icon>Ok</v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-col>

                            <v-col md="4" sm="6" xs="12" v-if="endDateShow">
                                <v-menu
                                    v-model="endDateMenu"
                                    transition="scale-transition"
                                    offset-y
                                    max-width="290px"
                                    min-width="290px"
                                    :close-on-content-click="false"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            label="End Date"
                                            append-icon="event"
                                            v-model="formattedEndDate"
                                            v-on="on"
                                            clearable
                                        ></v-text-field>
                                    </template>

                                    <v-date-picker
                                    v-model="endDate"
                                    no-title
                                    dense
                                    >
                                        <v-spacer></v-spacer>
                                        <v-btn @click="endDateMenu=false" icon>Ok</v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-col>

                            <v-col cols="auto" md="4" sm="6" xs="12">
                                <v-autocomplete
                                    label="Sort By"
                                    :items="sortby"
                                    item-text="sortbyValueText"
                                    item-value="sortbyValue"
                                    dense
                                    v-model="sortbyselected"
                                    clearable
                                ></v-autocomplete>
                            </v-col>

                            <v-row>
                                <v-btn color="primary"  class="mb-2 ml-5 mr-2" :disabled="!isShowable" @click="getCustProductVisitReport()">Show</v-btn>

                                <v-btn
                                    class="mx-0 mr-2"
                                    target="_blank"
                                    :href="'/api/customerproductvisitExcelReportDownload?categoryId='+categoryId+'&customerId='+customerId+'&productId='+productId+'&year='+year+'&startDate='+startDate+'&endDate='+endDate+'&token='+token"
                                    :disabled="!isShowable"
                                >
                                    <v-img src="/uploads/system/excel-50.jpg" max-width="20" class="mr-2"></v-img>
                                    Excel
                                </v-btn>

                            </v-row>

                        </v-row>



                        <loader v-if="isLoading"/>

                        <v-row v-else>

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
                        :items="customerproductvisits"
                        :search="search"
                        v-if="!isLoading"
                        >
                            <template v-slot:[`item.productPicturePath`]="{ item }">
                                <v-img
                                    :src="'/imageResize?url='+item.productPicturePath+(isSMALT? '&sizeX=50&sizeY=50': '&sizeX=80&sizeY=80') || '/uploads/no_image.png'"
                                    max-height="70"
                                    max-width="70"
                                    aspect-ratio contain
                                    @click="imageZoom(item.productPicturePath)"
                                    class="image-hover-cursor-change"
                                ></v-img>
                            </template>

                            <template v-slot:[`item.custFirstProductVisitDate`]="{ item }">
                                {{ YmdToDmy(item.custFirstProductVisitDate) }}
                            </template>

                            <template v-slot:[`item.custLastProductVisitDate`]="{ item }">
                                {{ YmdToDmy(item.custLastProductVisitDate) }}
                            </template>
                        </v-data-table>

                    </v-card-text>

                </v-card>

            </v-content>
        </v-sheet>

        <zoom_modal
            :zoomDialog="zoomDialog"
            :zoomPath="zoomPath"
            @cancelZoom="cancelZoom()"
        ></zoom_modal>


        <v-spacer></v-spacer>

    </v-app>
</template>


<script>
    import subheading from '../subheading.vue'

    // zoom
    import zoom_modal from './../../../frontend/components/zoom/zoom.vue'
    import zoom_mixin from './../../../frontend/components/zoom/zoomMixins.vue'

    import algorithms_mixin from './../../../mixins/algorithms.vue'
    import responsive from '../../../mixins/responsive.vue'

    import loader from '../../../frontend/components/loader/loader.vue'

    export default {
        components:{
            subheading, zoom_modal, loader
        },
        mounted() {
            this.getCustProductVisitReportCategories()
            this.getUsers()
            this.getProducts()
            this.getPorductVisitsYears()
        },
        data() {
            return {
                token: localStorage.getItem('token'),
                categories: [],
                categoryId: '',
                customers: [],
                customerId: '',
                products:[],
                productId:'',
                years: [],
                year: '',
                startDate:'',
                startDateMenu:false,
                endDate:'',
                endDateMenu:false,
                startDateShow:true,
                endDateShow:true,

                sortby: [
                    {sortbyValueText: 'Date Asc', sortbyValue: 'date_asc'},
                    {sortbyValueText: 'Date Desc', sortbyValue: 'date_desc'},
                    {sortbyValueText: 'All Cust Pro Hit Count Asc', sortbyValue: 'all_cust_prod_hit_count_asc'},
                    {sortbyValueText: 'All Cust Pro Hit Count Desc', sortbyValue: 'all_cust_prod_hit_count_desc'},
                ],
                sortbyselected: '',

                customerproductvisits: [],
                search: '',
                headers: [
                    {
                        text: 'Customer Id',
                        align: 'start',
                        // sortable: false,
                        value: 'customerId',
                    },
                    { text: 'Category', value: 'category' },
                    { text: 'Customer Name', value: 'name' },
                    { text: 'Customer Phone', value: 'phone' },
                    { text: 'Product Name', value: 'productName' },
                    { text: 'Product Image', value: 'productPicturePath' },
                    { text: 'Customer product hit count', value: 'cust_prod_hit_count' },
                    { text: 'Total product hit count', value: 'all_cust_prod_hit_count' },
                    { text: 'First Visit Date', value: 'custFirstProductVisitDate' },
                    { text: 'Last Visit Date', value: 'custLastProductVisitDate' },
                ],
                isLoading: false,

            }
        },
        mixins: [zoom_mixin, algorithms_mixin, responsive],

        methods: {

            getCustProductVisitReportCategories(){
                var _this = this;
                axios.get('/api/getCustProductVisitReportCategories?token='+this.token)
                .then(function (response) {
                    _this.categories = response.data.data;
                })
                .catch(function (error) {
                })
            },

            getUsers(){
                var _this = this;
                axios.get('/api/getUsersView?token='+this.token)
                .then(function (response) {
                    _this.customers = response.data.data;
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

            getPorductVisitsYears(){
                var _this = this;
                axios.get('/api/getPorductVisitsYears?token='+this.token)
                .then(function (response) {
                    _this.years = response.data.data;
                })
                .catch(function (error) {
                })
            },

            startDateEndDateHide(){
                this.startDate=''
                this.endDate=''
                this.startDateShow=false
                this.endDateShow=false
            },
            startDateEndDateShow(){
                this.startDate=''
                this.endDate=''
                this.startDateShow=true
                this.endDateShow=true
            },


            getCustProductVisitReport(){
                this.isLoading=true
                var _this = this;
                axios.post('/api/getCustProductVisitReport?token='+this.token, {
                    'categoryId': this.categoryId,
                    'customerId': this.customerId,
                    'productId': this.productId,
                    'year': this.year,
                    'startDate': this.startDate,
                    'endDate': this.endDate,
                    'sortBy': this.sortbyselected,
                })
                .then(function (response) {
                    _this.isLoading=false
                    _this.customerproductvisits = _this.objectToArray(response.data.data);

                    if (_this.sortbyselected) {
                        if (_this.sortbyselected=='date_asc') {
                            _this.customerproductvisits = _this.arrayOfObjectSort(_this.customerproductvisits,'visitSortNumber')
                        }
                        if (_this.sortbyselected=='date_desc') {
                            _this.customerproductvisits = _this.arrayOfObjectSortDesc(_this.customerproductvisits,'visitSortNumber')
                        }

                        if (_this.sortbyselected=='all_cust_prod_hit_count_asc') {
                            _this.customerproductvisits = _this.arrayOfObjectSort(_this.customerproductvisits,'all_cust_prod_hit_count')
                        }

                        if (_this.sortbyselected=='all_cust_prod_hit_count_desc') {
                            _this.customerproductvisits = _this.arrayOfObjectSortDesc(_this.customerproductvisits,'all_cust_prod_hit_count')
                        }
                    }
                })
                .catch(function (error) {
                    _this.isLoading=false
                    _this.customerproductvisits = [];
                })
            },


        },
        computed: {

            formattedStartDate: {
                get(){
                    return this.formatDate(this.startDate);
                },
                set(val){
                    this.startDate = this.formatDate(val);
                }
            },
            formattedEndDate: {
                get(){
                    return this.formatDate(this.endDate);
                },
                set(val){
                    this.endDate = this.formatDate(val);
                }
            },
            isShowable: function(){
                var condition = false
                if (this.categoryId || this.customerId || this.productId || this.year || this.startDate || this.endDate)
                {
                    condition = true
                }
                return condition
            },

        },
        watch: {
                startDate: function (newValue) {
                    if (newValue) {
                        this.endDate='';
                    }
                },
        },
    }
</script>

