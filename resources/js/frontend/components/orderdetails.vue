<template>

    <div>
        <v-card
        elevation="2"
        class=" pa-2"
        v-if="cart.cartId"
        >
            <v-list-item dense class="my-0 py-0">
                <v-spacer></v-spacer>
                <h5 class="my-0 py-0">Delivery Address</h5>
                <v-spacer></v-spacer>

            </v-list-item>
            <v-list-item dense class="my-0 py-0">
                <!-- <v-spacer></v-spacer> -->
                {{cart.shippingAddress}} {{' ('+cart.area+')'}}
                <!-- <v-spacer></v-spacer> -->
            </v-list-item>

            <v-simple-table   >
                <template v-slot:default >
                    <thead >
                        <tr>
                            <th colspan="2" class="text-left title">Product</th>
                            <th class="text-left title">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cartdetail in cartdetails" :key="cartdetail.cartDetailId">
                            <td><v-img
                                    :aspect-ratio="16/9"
                                    :src="'/imageResize?url='+(cartdetail.productPicturePath || '/uploads/no_image.png')+'&sizeX=100&sizeY=100' "
                                    contain
                                    max-width="100"
                                    min-width="75"
                                    @click="imageZoom(cartdetail.productPicturePath || '/uploads/no_image.png')"
                                    class="image-hover-cursor-change"

                                >
                                    <template v-slot:placeholder>
                                        <v-row
                                        class="fill-height ma-0"
                                        align="center"
                                        justify="center"
                                        >
                                            <v-progress-circular indeterminate color="#C9151B"></v-progress-circular>
                                        </v-row>
                                    </template>

                                </v-img></td>
                            <td>
                                {{ cartdetail.productName }}
                                <p>
                                    {{string_to_normalbehaviour((cartdetail.uom || '')+'. '+(cartdetail.productQty || '   ') + 'Qty. '+cartdetail.qty)}}
                                </p>

                            </td>
                            <td>৳ {{ cartdetail.subTotalWithDiscount }}</td>
                        </tr>
                        <tr></tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-right " >Delivery Charge</th>
                            <th >
                                ৳ {{cart.deliveryCharge}} &nbsp;
                                <button  v-if="deliveryChange && cart.isDeliveryComplete!=1  && cart.isCancelled!=1" text class="blue--text" @click="deliveryChanging()">Edit</button>
                            </th>
                        </tr>

                        <tr>
                            <th colspan="2" class="text-right title" >Total</th>
                            <th class="title">৳ {{cart.cartProductsTotalSum}}</th>
                        </tr>
                        <tr v-if="cart.customerDeliveryComment && isBackend">
                            <th>Delivery Instruction</th>
                            <td>
                                    {{cart.customerDeliveryComment}}
                            </td>
                        </tr>

                    </tfoot>
                </template>
            </v-simple-table>


        </v-card>

        <zoom_modal
                :zoomDialog="zoomDialog"
                :zoomPath="zoomPath"
                @cancelZoom="cancelZoom()"
        ></zoom_modal>

        <v-dialog v-model="deliveryChargeChangeModal" max-width="500px"  @keydown.esc="close()"  @keydown.enter="crudConfirm()" persistent>
            <v-card>
                <v-card-title>
                    <v-spacer></v-spacer>
                    <span class="headline">Edit <strong>Delivery Amount</strong></span>
                    <v-spacer></v-spacer>
                </v-card-title>

                <v-form @submit.prevent="crudConfirm()">
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" sm="12" md="12">
                                    <v-text-field name="deliveryCharge" label="Delivery Charge" v-model="deliveryCharge" prefix="৳"
                                         type="number"
                                    ></v-text-field>

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

    </div>

</template>


<script>
// zoom
    import zoom_modal from './zoom/zoom.vue'
    import zoom_mixin from './zoom/zoomMixins.vue'
    import modal_alert from './modals/alert.vue'
    import modal_alert_mixin from './modals/alertMixins.vue'

    export default {
        props: ["cart", "cartdetails", "deliveryChange"],
        components: {
            zoom_modal, modal_alert
        },
        mixins: [zoom_mixin, modal_alert_mixin],
        data() {
            return {
                token: localStorage.getItem('token'),
                deliveryChargeChangeModal:false,
                oldDeliveryCharge:this.cart.deliveryCharge,
                deliveryCharge:this.cart.deliveryCharge,
            }
        },
        methods: {
            deliveryChanging(){
                this.deliveryChargeChangeModal = true
            },
            close(){
                this.deliveryChargeChangeModal = false
            },
            crudConfirm(){
                var _this=this
                // this.json_data()

                axios.post('/api/cartUpdate?token='+this.token, this.json_data())
                .then(function (response) {
                    _this.$emit('reloadData')
                    _this.deliveryChargeChangeModal = false
                })
                .catch(function (error) {
                })
            },
            json_data(){

                var deliveryChangeDiff = 0
                var cartProductsTotalSum = 0

                if (this.oldDeliveryCharge>this.deliveryCharge) {
                    deliveryChangeDiff = this.oldDeliveryCharge-this.deliveryCharge
                    cartProductsTotalSum = this.cart.cartProductsTotalSum - deliveryChangeDiff
                    // console.log('> oldDeliveryCharge='+this.oldDeliveryCharge+' changedDeliveryCharge='+this.deliveryCharge+' deliveryChangeDiff = '+deliveryChangeDiff+' cartProductsTotalSum='+cartProductsTotalSum)
                } else {
                    deliveryChangeDiff = this.deliveryCharge-this.oldDeliveryCharge
                    cartProductsTotalSum = this.cart.cartProductsTotalSum + deliveryChangeDiff
                    // console.log('< oldDeliveryCharge='+this.oldDeliveryCharge+' changedDeliveryCharge='+this.deliveryCharge+' deliveryChangeDiff = '+deliveryChangeDiff+' cartProductsTotalSum='+cartProductsTotalSum)
                }

                this.oldDeliveryCharge = this.deliveryCharge

                return {
                    'cartId':this.cart.cartId,
                    'deliveryCharge': this.deliveryCharge,
                    'cartProductsTotalSum' : cartProductsTotalSum
                }
            }
        },
        computed: {

        },
    }
</script>



