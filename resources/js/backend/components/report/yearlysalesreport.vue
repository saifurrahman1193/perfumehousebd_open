<template>
    
    <v-col  cols="12" >
        <v-card elevation="2" >
            <v-card-title primary-title  class="justify-center">
                Yearly sales report
                
            </v-card-title>
            <v-card-text>
                <column-chart :data="data2"></column-chart>
            </v-card-text>
        </v-card>
    </v-col>

</template>


<script>

    export default {
        components:{
        },
        mounted() {
            this.getYearlySalesReport()
        },
        data() {
            return {
                token: localStorage.getItem('token'),
                data:[],
                data2:[],
            }
        },
        methods: {

            getYearlySalesReport(){
                var _this = this;
                axios.get('/api/getYearlySalesReport?token='+this.token)
                .then(function (response) {
                    _this.data=[]
                    _this.data2=[]
                    _this.data = response.data.data;
                    _this.data.forEach(function(row){
                        _this.data2.push([row.year, row.amount])
                    });
                })
                .catch(function (error) {
                })
            },
            
        },
        computed: {
           
        }
    }
</script>
