<template>
    <v-app  >

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
                        DMY Sales Report
                        <v-spacer></v-spacer>
                    </v-card-title>

                    <v-card-text>

                        <v-tabs
                            color="deep-purple accent-4"
                            show-arrows
                            >

                            <v-tab @click="tabChage()">All orders & Date Range</v-tab>
                            <v-tab @click="tabChage()">Yearly sales report</v-tab>
                            <v-tab @click="tabChage()">Monthly sales report</v-tab>
                            <v-tab @click="tabChage()">Daily sales report</v-tab>
                            <v-tab-item>
                                    <v-container fluid>
                                        <v-row>
                                            <v-btn color="primary" dark class="mb-2 ml-5" @click="getDmySalesReportData('&filter1=1&all=1')"> All orders</v-btn>
                                        </v-row>

                                        <!-- filter -->
                                        <!-- filter -->
                                        <v-row >
                                            <v-col md="4" sm="6" xs="12">
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

                                            <v-col md="4" sm="6" xs="12" >
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
                                        </v-row>
                                        <!-- filter -->
                                        <!-- filter -->




                                </v-container>
                            </v-tab-item>


                            <v-tab-item>
                                <v-container fluid>
                                    <v-row>
                                        <v-btn color="primary" dark class="mb-2 ml-5" @click="getDmySalesReportData('&filter2=1')">Yearly Sales Report</v-btn>
                                    </v-row>
                                </v-container>
                            </v-tab-item>

                            <v-tab-item>
                                <v-container fluid>
                                    <v-row>
                                        <v-col cols="auto" md="3">
                                            <v-select
                                                label="Year"
                                                :items="filter3years"
                                                dense
                                                v-model="filter3year"
                                                @change="getDmySalesReportData('&filter3=1&year='+filter3year)"
                                            ></v-select>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-tab-item>

                            <v-tab-item>
                                <v-container fluid>
                                    <v-row>
                                        <v-col cols="auto" md="3">
                                            <v-select
                                                label="Year"
                                                :items="filter4years"
                                                dense
                                                v-model="filter4year"
                                                clearable
                                            ></v-select>
                                        </v-col>
                                        <v-col cols="auto" md="3">
                                            <v-autocomplete
                                                label="Month"
                                                :items="filter4months"
                                                item-text="cartMonth"
                                                item-value="cartMonthId"
                                                dense
                                                v-model="filter4monthId"
                                                clearable
                                                @change="getDmySalesReportData('&filter4=1&year='+filter4year+'&monthId='+filter4monthId)"
                                            >
                                            </v-autocomplete>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-tab-item>



                        </v-tabs>


                        <v-row>
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
                        :items="dmysalesreportdata"
                        :search="search"
                        >
                            <template v-slot:[`item.avgOrdersPerDay`]="{ item }">
                                {{numberWithCommas(item.avgOrdersPerDay || 0 , 2)}}
                            </template>

                            <template v-slot:[`item.cartDate`]="{ item }">
                                {{formatDate(item.cartDate)}}
                            </template>

                            <template slot="body.append">
                                <tr class="pink--text">
                                    <th class="title">Totals</th>
                                    <th class="title"></th>
                                    <th class="title"></th>
                                    <th class="title">{{'৳ '+numberWithCommas(sumTotalSales('totalSales'))}}</th>
                                    <th class="title">{{numberWithCommas(sumTotalOrdersPerDay('totalOrdersPerDay'))}}</th>
                                    <th class="title">{{numberWithCommas(sumTotalDays('totalDays'))}}</th>
                                    <th class="title">{{numberWithCommas(sumAvgOrdersPerDay('avgOrdersPerDay'),2)}}</th>
                                </tr>
                            </template>

                        </v-data-table>



                    </v-card-text>
                </v-card>
            </v-content>

        </v-sheet>

        <v-spacer></v-spacer>


    </v-app>
</template>


<script>
    import subheading from '../subheading.vue'

    export default {
        components:{
            subheading,
        },
        mounted() {
            this.getCartYearMonths()
        },
        mixins: [ ],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',

                headers: [
                    { text: 'Year', value: 'cartDateYear' },
                    { text: 'Month', value: 'cartDateMonthName' },
                    { text: 'Date', value: 'cartDate' },
                    { text: 'Total Sales', value: 'totalSales' },
                    { text: 'Total Orders', value: 'totalOrdersPerDay' },
                    { text: 'Total Days', value: 'totalDays' },
                    { text: 'Avg. Orders Per Day', value: 'avgOrdersPerDay' },
                ],

                startDate:'',
                startDateMenu:false,
                endDate:'',
                endDateMenu:false,

                dmysalesreportdata:[],

                yearsmonths:[],
                filter3year:'',
                filter4year:'',
                filter4monthId:'',

            }
        },
        methods: {

            getDmySalesReportData(params=''){
                this.dmysalesreportdata = []
                var _this = this;
                axios.get('/api/getDmySalesReportData'+'?token='+ _this.token+params)
                .then(function (response) {
                    _this.dmysalesreportdata = _this.objectToArray(response.data.data);
                })
                .catch(function (error) {
                })
            },
            sumTotalSales(key) {
                return this.dmysalesreportdata.reduce((a, b) => a + (parseFloat(b[key]) || 0), 0)
            },
            sumTotalOrdersPerDay(key) {
                return this.dmysalesreportdata.reduce((a, b) => a + (parseFloat(b[key]) || 0), 0)
            },
            sumAvgOrdersPerDay(key) {
                return this.dmysalesreportdata.reduce((a, b) => a + (parseFloat(b[key]) || 0), 0)
            },
            sumTotalDays(key) {
                return this.dmysalesreportdata.reduce((a, b) => a + (parseFloat(b[key]) || 0), 0)
            },
            getCartYearMonths(){
                var _this = this;
                axios.get('/api/getCartYearMonths'+'?token='+ _this.token)
                .then(function (response) {
                    _this.yearsmonths = response.data.data;
                })
                .catch(function (error) {
                })
            },
            tabChage(){
                this.dmysalesreportdata = []
                this.startDate=''
                this.endDate=''
                this.filter3year=''
                this.filter4year=''
            }

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

            filter3years: function(){
                var years = [];
                this.yearsmonths.forEach(yearsmonth => {
                    if (!(years.includes(yearsmonth.cartYear)) )
                    {
                        years.push(yearsmonth.cartYear)
                    }
                });
                return years;
            },

            filter4years: function(){
                var years = [];
                this.yearsmonths.forEach(yearsmonth => {
                    if (!(years.includes(yearsmonth.cartYear)) )
                    {
                        years.push(yearsmonth.cartYear)
                    }
                });
                return years;
            },

            filter4months: function(){
                if (this.filter4year>0)
                {
                    return this.yearsmonths.filter(yearsmonth => {
                        return this.filter4year == yearsmonth.cartYear
                    });
                }
                else{
                    return [];
                }
            },

        },
        watch: {
            startDate: function (newValue) {
                if (newValue && this.endDate) {
                    this.getDmySalesReportData('&filter1=1&startDate='+newValue+'&endDate='+this.endDate);
                }
            },
            endDate: function (newValue) {
                if (newValue && this.startDate) {
                    this.getDmySalesReportData('&filter1=1&startDate='+this.startDate+'&endDate='+newValue);
                }
            },
        },


    }
</script>

