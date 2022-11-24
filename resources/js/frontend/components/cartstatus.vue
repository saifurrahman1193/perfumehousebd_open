<template>
    <v-list>

        <v-list-item v-if="cart.isCancelled==1 && isFrontend || isBackend ">
            <v-chip
                 @click="isBackend?cancelCart():''"
                 :color="cart.isCancelled==1 ? '#C9151B' : 'red lighten-1'" dark
                 :outlined="cart.isCancelled!=1"
            >
                <v-icon small>remove_shopping_cart</v-icon> &nbsp;
                {{ cart.isCancelled==1 ? 'Order cancelled' : 'Cancel Order' }}

            </v-chip>
        </v-list-item>

        <v-list-item v-if=" (cart.isDeliveryComplete!=1 || cart.isPaymentComplete!=1)  && cart.isCancelled!=1   && isFrontend || isBackend ">
            <v-chip color="#C9151B" dark ><v-icon small>shopping_basket</v-icon> &nbsp; Order Processing</v-chip>
        </v-list-item>

        <v-list-item v-if="(cart.isDeliveryComplete!=1 || cart.isPaymentComplete!=1) && cart.isCancelled!=1  && isFrontend || isBackend ">
            <v-chip  @click="isBackend?cartIsPaymentCompleteUpdate():''" :outlined="cart.isPaymentComplete!=1 " :color="cart.isPaymentComplete==1 ? 'success' :'red lighten-1'">
                ৳ Payment {{cart.isPaymentComplete==1?'Complete':'Incomplete'}}
            </v-chip>
        </v-list-item>

        <v-list-item v-if=" cart.isCancelled!=1 && (cart.isDeliveryComplete!=1 || cart.isPaymentComplete!=1)  && isFrontend || isBackend ">
            <v-chip :color="cart.isShipping==1 ? 'success' :'red lighten-1'" dark :outlined="cart.isShipping!=1"
            @click="isBackend?cartIsShippingUpdate():''"
            >
                <v-icon small>local_shipping</v-icon> &nbsp;
                 {{cart.isShipping==1 ? 'Shipping' : 'Not Shipping'}}
            </v-chip>
        </v-list-item>

        <v-list-item v-if="cart.isCancelled!=1 && cart.isShipping==1 && cart.isDeliveryComplete!=1 && cart.isPaymentComplete!=1 && isFrontend || isBackend ">
            <v-chip :color="cart.isDeliveryComplete==1 && cart.isShipping==1 ? 'success' :'red lighten-1'"  dark :outlined="cart.isDeliveryComplete!=1 || cart.isShipping!=1"
            @click="isBackend?cartIsDeliveryCompleteUpdate():''"
            >
                <v-icon small>local_shipping</v-icon> &nbsp;
                {{cart.isDeliveryComplete==1 && cart.isShipping==1? 'Delivered' : 'Not Delivered'}}
            </v-chip>
        </v-list-item>

        <v-list-item v-if="cart.isDeliveryComplete==1 && cart.isPaymentComplete==1 && cart.isShipping==1 && cart.isCancelled!=1">
            <v-chip color="success" dark  >
                <v-icon small>shopping_basket</v-icon> &nbsp;
                Complete
            </v-chip>
        </v-list-item>





        <v-list-item v-if="cart.isCancelled!=1">
            <v-chip
                v-if="cart.points"
                :color="cart.isPointValid==1 ? 'success' : 'red lighten-1'" dark
                :outlined="cart.isPointValid!=1"
                 @click="isBackend?cartIsPointValidUpdate():''"
            >
                <v-icon small>monetization_on</v-icon> &nbsp;
                {{cart.isPointValid==1 ? cart.points+' Points Achieved' : cart.points+' Points Invalid'}}

            </v-chip>
        </v-list-item>

    </v-list>


</template>

<script>
export default {
    data() {
        return {
            token:localStorage.getItem('token'),
        }
    },
    props: [ "cart"],
    methods: {

        cancelCart(){
            var _this=this
            axios.post('/api/cartUpdate?token='+this.token,
                {
                    'cartId':this.cart.cartId,
                    'isCancelled': (this.cart.isCancelled==1?0:1),
                }
            )
            .then(function (response) {
                _this.$emit('reloadData')
            })
            .catch(function (error) {
            })
        },

        cartIsShippingUpdate(){
            var _this=this
            axios.post('/api/cartUpdate?token='+this.token,
                {
                    'cartId':this.cart.cartId,
                    'isShipping': (this.cart.isShipping==1?0:1),
                }
            )
            .then(function (response) {
                _this.$emit('reloadData')
            })
            .catch(function (error) {
            })
        },
        cartIsPaymentCompleteUpdate(){
            var _this=this
            axios.post('/api/cartUpdate?token='+this.token,
                {
                    'cartId':this.cart.cartId,
                    'isPaymentComplete': (this.cart.isPaymentComplete==1?0:1),
                }
            )
            .then(function (response) {
                _this.$emit('reloadData')
            })
            .catch(function (error) {
            })
        },
        cartIsDeliveryCompleteUpdate(){
            var _this=this
            axios.post('/api/cartUpdate?token='+this.token,
                {
                    'cartId':this.cart.cartId,
                    'isDeliveryComplete': (this.cart.isDeliveryComplete==1?0:1),
                }
            )
            .then(function (response) {
                _this.$emit('reloadData')
            })
            .catch(function (error) {
            })
        },


        cartIsPointValidUpdate(){
            var _this=this
            axios.post('/api/cartUpdate?token='+this.token,
                {
                    'cartId':this.cart.cartId,
                    'isPointValid': (this.cart.isPointValid==1?0:1),
                }
            )
            .then(function (response) {
                _this.$emit('reloadData')
            })
            .catch(function (error) {
            })
        },


    },
}
</script>
