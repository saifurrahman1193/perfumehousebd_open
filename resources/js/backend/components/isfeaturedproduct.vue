<template>

    <div>
        <v-switch  v-model="isfeaturedproduct"  inset :label="isFeaturedProduct==1 ? 'Featured Product' : 'Not Featured Product'" false-value="0" true-value="1" color="green"></v-switch>
    </div>

</template>


<script>



    export default {
        components:{
        },
        mounted() {


        },
        data() {
            return {
                token: localStorage.getItem('token'),
            }
        },
        methods: {
            addFeaturedProduct(){
                var _this=this
                axios.post('/api/addFeaturedProduct?token='+this.token, {productId: this.productId})
                .then(function (response) {
                })
                .catch(function (error) {
                })
            },
            deleteFeaturedProductWithProductId(){
                var _this=this
                axios.post('/api/deleteFeaturedProductWithProductId/'+this.productId+'?token='+this.token)
                .then(function (response) {
                })
                .catch(function (error) {
                })
            },

        },
        props: ["isFeaturedProduct", "productId"],
        computed: {
            isfeaturedproduct: {
                get(){
                    return this.isFeaturedProduct;
                },
                set(val){
                    if(val==1){
                        this.addFeaturedProduct()
                    }
                    else{
                        this.deleteFeaturedProductWithProductId()
                    }
                    this.$emit("getProducts")
                }
            },
        },


    }
</script>

