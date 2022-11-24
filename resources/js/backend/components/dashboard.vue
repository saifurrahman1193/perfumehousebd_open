<template>
    <v-app >

        <subheading></subheading>

        <v-sheet
                class=" ma-2"
                elevation="0"
                 color="grey lighten-5"

            >
            <v-content>

                <template>
                        <v-card>
                            <v-tabs
                            color="deep-purple accent-4"
                            show-arrows
                            >
                                <v-tab>Today Sales</v-tab>
                                <v-tab>{{getCurrentMonthNameLong()}} Sales</v-tab>
                                <v-tab>{{getCurrentYear()}} Sales</v-tab>
                                <v-tab>Overall Sales</v-tab>

                                <v-tab-item>
                                    <v-container fluid>
                                        <v-row>
                                            <v-col  cols="auto" >
                                                <v-card elevation="2" class="text-center mx-auto py-0" width="250">
                                                    <v-card-subtitle class="">
                                                        Today Orders
                                                        <p class="pt-1 h3 blue--text">{{ numberWithCommas(Math.round(salesReport.todayTotalOrders || 0))}}</p>
                                                    </v-card-subtitle>
                                                </v-card>
                                            </v-col>
                                            <v-col  cols="auto" >
                                                <v-card elevation="2" class="text-center mx-auto py-0" width="250">
                                                    <v-card-subtitle class="">
                                                        Today Sale
                                                        <p class="pt-1 h3 blue--text">{{ numberWithCommas(Math.round(salesReport.todaySales || 0))}} <span class="h6">BDT</span></p>
                                                    </v-card-subtitle>
                                                </v-card>
                                            </v-col>


                                        </v-row>
                                    </v-container>
                                </v-tab-item>

                                <v-tab-item>
                                    <v-container fluid>
                                        <v-row>
                                            <v-col  cols="auto" >
                                                <v-card elevation="2" class="text-center mx-auto py-0" width="250">
                                                    <v-card-subtitle class="">
                                                        Total Orders of <strong >{{getCurrentMonthNameLong()}}</strong>
                                                        <p class="pt-1 h3 blue--text">{{numberWithCommas(Math.round(salesReport.totalCMOrders || 0))}}</p>
                                                    </v-card-subtitle>
                                                </v-card>
                                            </v-col>

                                            <v-col  cols="auto" >
                                                <v-card elevation="2" class="text-center mx-auto py-0" width="250">
                                                    <v-card-subtitle class="">
                                                        Net Sales of <strong >{{getCurrentMonthNameLong()}}</strong>
                                                        <p class="pt-1 h3 blue--text">{{ numberWithCommas(Math.round(salesReport.totalCMSales || 0))}} <span class="h6">BDT</span></p>
                                                    </v-card-subtitle>
                                                </v-card>
                                            </v-col>

                                            <v-col  cols="auto" >
                                                <v-card elevation="2" class="text-center mx-auto py-0" width="250">
                                                    <v-card-subtitle class="">
                                                        Avg. Orders of <strong >{{getCurrentMonthNameLong()}}</strong>
                                                        <p class="pt-1 h3 blue--text">
                                                            {{
                                                                isNaN(
                                                                    ( getTodayDayNumberOfCurrentMonth() < (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) ) ?

                                                                    numberWithCommas(Math.round((salesReport.totalCMOrders || 0) / getTodayDayNumberOfCurrentMonth())) :

                                                                    numberWithCommas((salesReport.totalCMOrders || 0) / (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) , 1)
                                                                ) ? 0:
                                                                ( getTodayDayNumberOfCurrentMonth() < (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) ) ?

                                                                    numberWithCommas(Math.round((salesReport.totalCMOrders || 0) / getTodayDayNumberOfCurrentMonth())) :

                                                                    numberWithCommas((salesReport.totalCMOrders || 0) / (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) , 1)
                                                            }}
                                                        </p>
                                                    </v-card-subtitle>
                                                </v-card>
                                            </v-col>

                                            <v-col  cols="auto" >
                                                <v-card elevation="2" class="text-center mx-auto py-0" width="250">
                                                    <v-card-subtitle class="">
                                                        Avg. Sales of <strong >{{getCurrentMonthNameLong()}}</strong>
                                                        <p class="pt-1 h3 blue--text">
                                                            {{
                                                                isNaN(
                                                                    ( getTodayDayNumberOfCurrentMonth() < (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) ) ?

                                                                    numberWithCommas(Math.round((salesReport.totalCMSales || 0)/getTodayDayNumberOfCurrentMonth())) :

                                                                    numberWithCommas(Math.round((salesReport.totalCMSales || 0)/ (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) ))

                                                                )? 0 :
                                                                    ( getTodayDayNumberOfCurrentMonth() < (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) ) ?

                                                                    numberWithCommas(Math.round((salesReport.totalCMSales || 0)/getTodayDayNumberOfCurrentMonth())) :

                                                                    numberWithCommas(Math.round((salesReport.totalCMSales || 0)/ (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) ))
                                                            }}
                                                            <span class="h6">BDT</span></p>
                                                    </v-card-subtitle>
                                                </v-card>
                                            </v-col>

                                        </v-row>
                                    </v-container>
                                </v-tab-item>

                                <v-tab-item>
                                    <v-container fluid>
                                        <v-row>
                                            <v-col  cols="auto" >
                                                <v-card elevation="2" class="text-center mx-auto py-0" width="250">
                                                    <v-card-subtitle class="">
                                                        Total Orders of <strong >{{getCurrentYear()}}</strong>
                                                        <p class="pt-1 h3 blue--text">{{ numberWithCommas(Math.round(salesReport.totalCYOrders) || 0)}}</p>
                                                    </v-card-subtitle>
                                                </v-card>
                                            </v-col>
                                            <v-col  cols="auto" >
                                                <v-card elevation="2" class="text-center mx-auto py-0" width="250">
                                                    <v-card-subtitle class="">
                                                        Total Sales of <strong >{{getCurrentYear()}}</strong>
                                                        <p class="pt-1 h3 blue--text">{{ numberWithCommas(Math.round(salesReport.totalCYSales) || 0)}} <span class="h6">BDT</span></p>
                                                    </v-card-subtitle>
                                                </v-card>
                                            </v-col>
                                            <v-col  cols="auto" >
                                                <v-card elevation="2" class="text-center mx-auto py-0" width="250">
                                                    <v-card-subtitle class="">
                                                        Avg. Orders of <strong >{{getCurrentYear()}}</strong>
                                                        <p class="pt-1 h3 blue--text">
                                                            {{
                                                                isNaN(( getTodayDayNumberOfCurrentYear() < (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) ) ?

                                                                numberWithCommas(Math.round((salesReport.totalCYOrders || 0)/getTodayDayNumberOfCurrentYear())) :

                                                                numberWithCommas(Math.round((salesReport.totalCYOrders || 0)/ (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) ))) ? 0 :

                                                                ( getTodayDayNumberOfCurrentYear() < (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) ) ?

                                                                numberWithCommas(Math.round((salesReport.totalCYOrders || 0)/getTodayDayNumberOfCurrentYear())) :

                                                                numberWithCommas(Math.round((salesReport.totalCYOrders || 0)/ (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) ))
                                                            }}
                                                        </p>
                                                    </v-card-subtitle>
                                                </v-card>
                                            </v-col>
                                            <v-col  cols="auto" >
                                                <v-card elevation="2" class="text-center mx-auto py-0" width="250">
                                                    <v-card-subtitle class="">
                                                        Avg. Sales of <strong >{{getCurrentYear()}}</strong>
                                                        <p class="pt-1 h3 blue--text">
                                                            {{
                                                                isNaN(
                                                                    ( getTodayDayNumberOfCurrentYear() < (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) ) ?

                                                                numberWithCommas(Math.round((salesReport.totalCYSales || 0)/getTodayDayNumberOfCurrentYear())) :

                                                                numberWithCommas(Math.round((salesReport.totalCYSales || 0)/ (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) ))
                                                                ) ? 0 :
                                                                ( getTodayDayNumberOfCurrentYear() < (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) ) ?

                                                                numberWithCommas(Math.round((salesReport.totalCYSales || 0)/getTodayDayNumberOfCurrentYear())) :

                                                                numberWithCommas(Math.round((salesReport.totalCYSales || 0)/ (getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())) ))
                                                            }}
                                                            <span class="h6">BDT</span></p>
                                                    </v-card-subtitle>
                                                </v-card>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-tab-item>

                                <v-tab-item>
                                    <v-container fluid>
                                        <v-row>
                                            <v-col  cols="auto" >
                                                <v-card elevation="2" class="text-center mx-auto py-0" width="250">
                                                    <v-card-subtitle class="">
                                                        Total <strong >Orders</strong>
                                                        <p class="pt-1 h3 blue--text">{{ numberWithCommas(Math.round(salesReport.totalOrders || 0))}}</p>
                                                    </v-card-subtitle>
                                                </v-card>
                                            </v-col>
                                            <v-col  cols="auto" >
                                                <v-card elevation="2" class="text-center mx-auto py-0" width="250">
                                                    <v-card-subtitle class="">
                                                        Total <strong >Sales</strong>
                                                        <p class="pt-1 h3 blue--text">{{ numberWithCommas(Math.round(salesReport.totalSales || 0))}} <span class="h6">BDT</span></p>
                                                    </v-card-subtitle>
                                                </v-card>
                                            </v-col>

                                            <v-col  cols="auto" >
                                                <v-card elevation="2" class="text-center mx-auto py-0" width="250">
                                                    <v-card-subtitle class="">
                                                        Total <strong >Avg. Orders</strong>
                                                        <p class="pt-1 h3 blue--text">{{
                                                            isNaN(numberWithCommas(
                                                                ((salesReport.totalOrders || 0)
                                                                /
                                                                getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd()))
                                                             )) ? 0 :
                                                             numberWithCommas(
                                                                ((salesReport.totalOrders || 0)
                                                                /
                                                                getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd()))
                                                             )
                                                            }}</p>
                                                    </v-card-subtitle>
                                                </v-card>
                                            </v-col>

                                            <v-col  cols="auto" >
                                                <v-card elevation="2" class="text-center mx-auto py-0" width="250">
                                                    <v-card-subtitle class="">
                                                        Total <strong >Avg. Sales</strong>
                                                        <p class="pt-1 h3 blue--text">{{
                                                            isNaN(numberWithCommas(Math.round((salesReport.totalSales || 0)/getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())))) ? 0 :
                                                            numberWithCommas(Math.round((salesReport.totalSales || 0)/getNumberOfDaysFrom2YmdDates(salesReport.firstCartDate, getTodayDateYmd())))
                                                            }} <span class="h6">BDT</span></p>
                                                    </v-card-subtitle>
                                                </v-card>
                                            </v-col>



                                        </v-row>
                                    </v-container>
                                </v-tab-item>

                            </v-tabs>
                        </v-card>
                    </template>
            </v-content>

        </v-sheet>

        <v-spacer></v-spacer>

    </v-app>
</template>


<script>

    import subheading from './subheading.vue'


    export default {
        components:{
            subheading
        },
        mounted() {
            this.getSalesDashboardReport()
        },
        data() {
            return {
                token: localStorage.getItem('token'),
                salesReport:{}
            }
        },
        methods: {

            getSalesDashboardReport(){
                var _this = this;
                axios.get('/api/getSalesDashboardReport?token='+this.token)
                .then(function (response) {
                    _this.salesReport = (response.data.data)[0];
                })
                .catch(function (error) {
                })
            },

        },

    }
</script>
