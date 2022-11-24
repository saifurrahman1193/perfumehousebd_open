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
                        Valuable Customers
                        <v-spacer></v-spacer>
                    </v-card-title>

                    <v-card-text>

                        <!-- filter -->
                        <!-- filter -->
                        <v-row>
                            <v-col  md="6" sm="12" xs="12">
                                <v-autocomplete
                                    label="Customer"
                                    :items="customers2"
                                    item-text="name"
                                    item-value="id"
                                    dense
                                    v-model="customerId"
                                    @change="getValuableCustomers('&customerId='+customerId); startDate=''; endDate=''; "
                                    clearable
                                >
                                    <template v-slot:item="data" >
                                            <v-list-item-avatar>
                                                <img :src="data.item.photoPath" v-if="data.item.photoPath">
                                            </v-list-item-avatar>
                                            <v-list-item-content>
                                                <v-list-item-title v-html="data.item.name"></v-list-item-title>
                                                <v-list-item-subtitle>{{ string_to_normalbehaviour((data.item.phone||'') +', ' + (data.item.email||'') +', ' +(data.item.area || '') +', ')}}</v-list-item-subtitle>
                                            </v-list-item-content>
                                    </template>
                                </v-autocomplete>
                            </v-col>


                        </v-row>

                        <v-row v-if="customerId>0 || customerId==-1">
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

                            <v-col md="4" sm="6" xs="12" v-if="startDate">
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
                        :items="valuablecustomersdata"
                        :search="search"
                        >

                            <template v-slot:[`item.photoPath`]="{ item }">
                                <v-img
                                    :src="item.photoPath || '/uploads/no_image.png'"
                                    lazy-src="/uploads/loader.gif"
                                    max-height="70"
                                    max-width="70"
                                    aspect-ratio contain
                                    @click="imageZoom(item.photoPath)"
                                    class="image-hover-cursor-change"
                                ></v-img>
                            </template>



                            <template v-slot:[`item.user`]="{ item }">
                                <v-simple-table  dense >
                                    <template v-slot:default >

                                        <tbody>
                                            <tr>
                                                <th>Name</th>
                                                <td>{{ item.name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ item.email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Phone</th>
                                                <td>{{ item.phone }}</td>
                                            </tr>
                                            <tr>
                                                <th>Area</th>
                                                <td>{{ item.area }}</td>
                                            </tr>
                                            <tr>
                                                <th>Registry Date</th>
                                                <td>{{ datetimeprocessing(item.created_at) }}</td>
                                            </tr>

                                        </tbody>
                                    </template>
                                </v-simple-table>
                            </template>

                            <template v-slot:[`item.totalOrders`]="{ item }">
                                {{numberWithCommas(item.totalOrders || 0 )}}
                            </template>

                            <template v-slot:[`item.totalValue`]="{ item }">
                                {{'৳ '+numberWithCommas(item.totalValue || 0)}}
                            </template>

                            <template slot="body.append">
                                <tr class="pink--text">
                                    <th class="title">Totals</th>
                                    <th class="title"></th>
                                    <th class="title"></th>
                                    <th class="title">{{numberWithCommas(sumTotalOrder('totalOrders'))}}</th>
                                    <th class="title">{{'৳ '+numberWithCommas(sumTotalValue('totalValue'))}}</th>
                                </tr>
                            </template>




                        </v-data-table>

                    </v-card-text>
                </v-card>
            </v-content>

        </v-sheet>

        <v-spacer></v-spacer>

        <zoom_modal
            :zoomDialog="zoomDialog"
            :zoomPath="zoomPath"
            @cancelZoom="cancelZoom()"
        ></zoom_modal>

    </v-app>
</template>


<script>
    import subheading from '../subheading.vue'

    // zoom
    import zoom_modal from './../../../frontend/components/zoom/zoom.vue'
    import zoom_mixin from './../../../frontend/components/zoom/zoomMixins.vue'

    export default {
        components:{
            subheading, zoom_modal
        },
        mounted() {
            this.getValuableCustomers()
            this.getUsers();
        },
        mixins: [ zoom_mixin],
        data() {
            return {
                token: localStorage.getItem('token'),
                search: '',
                headers: [
                    {
                        text: 'User Id',
                        align: 'start',
                        // sortable: false,
                        value: 'id',
                    },
                    { text: 'Picture', value: 'photoPath' },
                    { text: 'User', value: 'user' },
                    { text: 'Total Orders', value: 'totalOrders' },
                    { text: 'Total Value', value: 'totalValue' },
                ],
                valuablecustomersdata: [],
                customers: [],
                customerId:'',
                startDate:'',
                startDateMenu:false,
                endDate:'',
                endDateMenu:false,
            }
        },
        methods: {

            getValuableCustomers(params=''){
                var _this = this;
                axios.get('/api/getValuableCustomers'+'?token='+ _this.token+params)
                .then(function (response) {
                    _this.valuablecustomersdata = _this.objectToArray(response.data.data);
                })
                .catch(function (error) {
                })
            },
            getUsers(){
                var _this = this;
                axios.get('/api/getUsersView'+'?token='+ _this.token)
                .then(function (response) {
                    _this.customers = response.data.data;
                })
                .catch(function (error) {
                })
            },
            sumTotalOrder(key) {
                return this.valuablecustomersdata.reduce((a, b) => a + (parseFloat(b[key]) || 0), 0)
            },
            sumTotalValue(key) {
                return this.valuablecustomersdata.reduce((a, b) => a + (parseFloat(b[key]) || 0), 0)
            }

        },
        computed: {
            customers2: function(){
                var tempArr = []
                tempArr.push({id:'-1', name: 'All Customers', email: '', phone: '', area: '' })
                this.customers.forEach(element => {
                    tempArr.push(element)
                });

                return tempArr
            },
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

        },
        watch: {
            startDate: function (newValue) {
                if (newValue) {
                    this.getValuableCustomers('&customerId='+this.customerId+'&startDate='+newValue);
                    this.endDate='';
                }
            },
            endDate: function (newValue) {
                if (newValue) {
                    this.getValuableCustomers('&customerId='+this.customerId+'&startDate='+this.startDate+'&endDate='+newValue);
                }
            },
        },


    }
</script>

