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
                        Manual Cart
                        <v-spacer></v-spacer>
                    </v-card-title>
                </v-card>

                <v-card elevation="2" class="mt-2">
                    <v-card-title>
                        <v-toolbar-title class="headline"  :class="isSMALT?'title':'display-1'"><v-icon>person</v-icon> Customer</v-toolbar-title>
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" sm="12" md="6">
                                <v-autocomplete
                                    label="Customer"
                                    :items="users"
                                    item-text="name"
                                    item-value="id"
                                    dense
                                    filled
                                    id="userId"
                                    name="userId"
                                    v-model="cart.userId"
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
                            <v-col cols="12" sm="12" md="6">
                                <customerinfo :user="user"></customerinfo>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>


                <v-card elevation="2" class="mt-2" v-if="cart.userId">
                    <v-card-title>
                        <v-toolbar-title class="headline"  :class="isSMALT?'title':'display-1'"><v-icon>shopping_basket</v-icon> Cart Items</v-toolbar-title>
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" sm="12" md="6">
                                <v-autocomplete
                                        label="Product"
                                        :items="products"
                                        item-text="productName"
                                        item-value="productId"
                                        dense
                                        filled
                                        clearable
                                        v-model="productId"
                                        @change="setProduct(productId)"
                                    >
                                </v-autocomplete>

                                <v-btn color="success" :disabled="!productId || !(product.inStockQty>0)"
                                @click="addToCart(productId); clearProductItem()"
                                >Add to cart item</v-btn>
                            </v-col>
                            <v-col cols="12" sm="12" md="6">

                                <v-simple-table  dense v-if="productId">
                                    <template v-slot:default >

                                        <tbody>
                                            <tr>
                                                <th>Product Name</th>
                                                <td>{{ product.productName }}</td>
                                            </tr>
                                            <tr>
                                                <th>Qty </th>
                                                <td>{{product.qty}} {{product.uom}}</td>
                                            </tr>
                                            <tr>
                                                <th>Brand</th>
                                                <td>{{ product.brand }}</td>
                                            </tr>
                                            <tr :class="product.inStockQty>0?'':'red--text'">
                                                <th>Stock Qty</th>
                                                <td >{{ product.inStockQty }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <gallery :images="getThisProductPictures(product.productId)" > </gallery>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </template>
                                </v-simple-table>



                            </v-col>
                        </v-row>

                        <v-divider></v-divider>
                        <v-simple-table>
                            <template v-slot:default  >
                                <thead >
                                    <tr>
                                        <th class="text-left title" :class="isXS?'subtitle-2':''" >Product</th>
                                        <th class="text-left title" :class="isXS?'subtitle-2':''">Price</th>
                                        <th class="text-left title" :class="isXS?'subtitle-2':''">Discount</th>
                                        <th class="text-left title" :class="isXS?'subtitle-2':''" style="padding-left: 35px; width:200px !important; text-align:center !important;" >Qty</th>
                                        <th class="text-left title" :class="isXS?'subtitle-2':''">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in cartProducts" :key="product.productId">
                                        <td>
                                            <v-list color="transparent">
                                                <v-list-item >
                                                    <v-list-item-icon>
                                                        <v-img
                                                            :aspect-ratio="16/9"
                                                            :src="product.productPicturePath || '/uploads/no_image.png'"
                                                            contain
                                                            :width="isSMALT? 50: 80"
                                                            @click="imageZoom(product.productPicturePath)"
                                                            style="cursor: zoom-in;"
                                                        >
                                                            <template v-slot:placeholder>
                                                                <v-row
                                                                class="fill-height ma-0"
                                                                align="center"
                                                                justify="center"
                                                                >
                                                                    <v-progress-circular indeterminate color="orange"></v-progress-circular>
                                                                </v-row>
                                                            </template>

                                                        </v-img>
                                                    </v-list-item-icon>
                                                    <v-list-item-content>
                                                        <v-list-item-title v-text="product.productName"></v-list-item-title>
                                                    </v-list-item-content>
                                                </v-list-item>
                                            </v-list>
                                        </td>
                                        <td>৳ {{ product.sellingPrice || 0 }}</td>
                                        <td>৳ {{ product.subTotalTotalDiscountAmount || 0 }}</td>
                                        <td  style=" text-align:center;">
                                            <v-btn  text  small @click="subtractProductQtyFromCart(product.productId)"><v-icon>remove</v-icon></v-btn>
                                            {{ product.sellingQty }}
                                            <v-btn text  small @click="addQtyToCart(product.productId)"><v-icon>add</v-icon></v-btn>
                                        </td>
                                        <td>৳ {{ product.subTotalWithDiscount || 0}}</td>
                                    </tr>
                                    <tr></tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th :colspan="isXS?'3':'4'" class="text-right " >Delivery Charge</th>
                                        <th :colspan="isXS?'2':''" >৳ {{deliveryCharge}}</th>
                                    </tr>

                                    <tr>
                                        <th :colspan="isXS?'3':'4'" class="text-right title"  :class="isXS?'subtitle-1':''">Total</th>
                                        <th  :colspan="isXS?'2':''"  :class="isXS?'':'title'">৳ {{cartProductsTotalSum || 0}}</th>
                                    </tr>

                                </tfoot>
                            </template>
                        </v-simple-table>

                    </v-card-text>
                </v-card>

                <v-card elevation="2" class="mt-2" v-if="cart.userId">
                    <v-card-text>
                        <v-spacer></v-spacer>
                        <v-toolbar-title class="headline"   :class="isSMALT?'title mt-5':'display-1'" ><v-icon>place</v-icon>
                        Delivery Address
                        <span class="subtitle-1 red--text" v-if="!deliveryaddress.deliveryAddressId" style="white-space:normal;">(Select a delivery address below)</span>
                        </v-toolbar-title>
                        <v-divider></v-divider>

                        <deliverydetails_f :userId="cart.userId" v-if="cart.userId" @setDeliveryAddress="setDeliveryAddress"></deliverydetails_f>
                    </v-card-text>
                </v-card>


                <v-row class="ma-5"  v-if="cartProducts.length">
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red lighten-1"
                        class="ma-2 white--text text-capitalize"
                        large
                        @click="confirmOrder()"
                        :loading="progress_mode"
                        :disabled="progress_mode"
                        >
                        Confirm Order
                        <v-icon >chevron_right</v-icon>
                    </v-btn>
                </v-row>



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

    import gallery from './gallery/gallery.vue'


    import deliverydetails_f from './deliverydetails.vue'
    import customerinfo from './customerinfo.vue'

    import responsive from '../../mixins/responsive.vue'

    export default {
        components:{
            subheading, modal_alert, zoom_modal, gallery, deliverydetails_f, customerinfo
        },
        mounted() {
            this.getUsers();
            this.getProducts();
            this.getProductPictures()
            this.getDeliverySetting()

        },
        mixins: [modal_alert_mixin , zoom_mixin, responsive],
        data() {
            return {
                token: localStorage.getItem('token'),
                users:[],
                user:{},
                products:[],
                product:{},
                productId:'',
                productpictures:[],
                cart:{},
                cartItemsTemp:[],
                totalQty: 0,
                cartProductsSubTotalSum: 0,
                outsideDahakeDeliveryPrice:0,
                deliverySettingPrice:0,
                cartProductsTotalSum:0,
                totalDiscountAmount: 0,
                deliveryaddress:{},
                progress_mode:false,
                cartId:''


            }
        },
        methods: {
            getUsers(){
                var _this = this;
                axios.get('/api/getUsersView'+'?token='+ _this.token)
                .then(function (response) {
                    _this.users = response.data.data;
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
            setProduct(productId){
                this.product=this.products.filter(function(product){
                    return product.productId==productId ;
                })[0];
            },
            getThisProductPictures( productId ){
                var _this = this;
                return this.productpictures.filter(function(productpicture){
                    return productpicture.productId == productId
                }).map(function(productpicture) {
                    return productpicture.productPicturePath
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
            getDiscountCalculationforManualCart(customerId){
                var _this = this;
                axios.post('/api/getDiscountCalculationforManualCart/'+customerId, {token: this.token, products: this.products})
                .then(function (response) {
                    _this.products=response.data.data
                })
                .catch(function (error) {
                })
            },
            clearProductItem(){
                this.product={}
                this.productId=''
            },



            addToCart(productId){
                var qty = this.getCartProductQty(productId);
                qty = parseInt(qty);
                qty = (qty>0 ? qty : 0);
                var isProductAlreadyAdded = (qty>0 ? 1 : 0)
                if(isProductAlreadyAdded )
                {
                    qty = qty + 1;
                    var productObj = {"productId":productId, "qty": qty};
                    this.removeProductFromCart(productId)
                    this.cartItemsTemp.push(productObj);
                }
                else
                {
                    var productObj = {"productId":productId, "qty": 1};
                    this.cartItemsTemp.push(productObj);
                }
            },
            getCartProductQty(productId){
                var qty = 0;

                this.cartItemsTemp.forEach(product => {
                    if (product.productId == productId)
                    {
                        qty = product.qty
                    }
                });
                return qty;
            },
            removeProductFromCart(productId){
                this.cartItemsTemp = this.cartItemsTemp.filter(function(product){
                    return product.productId != productId
                })
            },
            addQtyToCart(productId){
                var qty = this.getCartProductQty(productId);
                qty = parseInt(qty);
                qty = (qty>0 ? qty : 0);
                var isProductAlreadyAdded = (qty>0 ? 1 : 0)
                console.log('isProductAlreadyAdded = '+isProductAlreadyAdded)
                if(isProductAlreadyAdded )
                {
                    qty = qty + 1;
                    var productObj = {"productId":productId, "qty": qty};
                    this.removeProductFromCart(productId)
                    this.cartItemsTemp.push(productObj);
                }
                else
                {
                    var productObj = {"productId":productId, "qty": 1};
                    this.cartItemsTemp = this.getCartList;
                }
            },
            subtractProductQtyFromCart(productId){
                var qty = 0;
                this.cartItemsTemp.forEach(product => {
                    if (product.productId==productId )
                    {
                        qty = parseInt(product.qty);
                    }
                });

                if (qty>=2)
                {
                    var productObj = {"productId":productId, "qty": qty-1};
                    this.removeProductFromCart(productId)
                    this.cartItemsTemp.push(productObj);
                }
                else
                {
                    this.cartItemsTemp = this.cartItemsTemp.filter(function(product){
                        return product.productId != productId
                    });
                    this.removeProductFromCart(productId)
                }
            },

            setDeliveryAddress( deliveryaddress){
                if (deliveryaddress.deliveryAddressId && deliveryaddress.areaId) {
                    this.deliveryaddress = deliveryaddress
                }
                else{
                    this.deliveryaddress = {}
                }
            },
            getDeliverySetting(){
                var _this = this;
                axios.get('/api/getDeliverySetting')
                .then(function (response) {
                    _this.deliverySettingPrice = response.data.data.deliverySettingPrice;
                    _this.outsideDahakeDeliveryPrice = response.data.data.outsideDahakeDeliveryPrice;
                })
                .catch(function (error) {
                })
            },
            confirmOrder(){
                this.progress_mode=true

                var _this = this;
                axios.post('/api/confirmOrderForManualCart?token='+this.token, {
                    cartProducts: this.cartProducts,
                    deliveryCharge: parseInt(this.deliveryCharge),
                    cartProductsSubTotalSum: parseInt(this.cartProductsSubTotalSum),
                    cartProductsTotalSum: parseInt(this.cartProductsTotalSum),
                    deliveryaddress: this.deliveryaddress,
                    totalQty: parseInt(this.totalQty),
                    totalDiscountAmount: parseInt(this.totalDiscountAmount),
                    userId:this.cart.userId

                })
                .then(function (response) {
                    _this.s_alert = true;
                    _this.progress_mode=false
                    _this.cartId = response.data.cartId
                    _this.gotorouterview('/admin/carts?cartId='+_this.cartId)
                })
                .catch(function (error) {
                    if (!_this.deliveryaddress.areaId) {
                        _this.e_msg="Please select a delivery address."
                    }
                    _this.e_alert = true;
                    _this.progress_mode=false

                })
            },
        },
        computed: {
            cartProducts: function () {
                var _this = this;

                var productArray= [];

                this.cartProductsSubTotalSum = 0;
                this.totalQty=0

                this.totalDiscountAmount=0;


                this.products.forEach(product => {
                    _this.cartItemsTemp.forEach(cartProduct => {
                        if (product.productId == cartProduct.productId) {

                            productArray.push(product.productId)

                            product.sellingQty=cartProduct.qty
                            product.subTotal=product.sellingPrice * cartProduct.qty



                            product.subTotalDiscountAmount=product.discountAmount

                            product.subTotalTotalDiscountAmount=product.discountAmount*cartProduct.qty
                            if(product.discountPercent>0) product.subTotalTotalDiscountAmount=(product.sellingPrice*cartProduct.qty*(product.discountPercent/100))
                            else if(product.discountAmount>0) product.subTotalTotalDiscountAmount=cartProduct.qty*product.discountAmount



                            product.subTotalWithDiscount = product.subTotal-product.subTotalTotalDiscountAmount

                            _this.cartProductsSubTotalSum += product.subTotal;
                            _this.totalDiscountAmount += product.subTotalTotalDiscountAmount

                            _this.totalQty+=parseFloat(cartProduct.qty)

                        }
                    });
                });


                this.cartProductsTotalSum = this.cartProductsSubTotalSum + parseFloat(this.deliveryCharge)-parseFloat(this.totalDiscountAmount);

                return this.products.filter(function(product){
                    return productArray.includes(product.productId)
                })
            },
            deliveryCharge(){
                var charge = 0;
                if (this.deliveryaddress.areaId && this.deliveryaddress.areaId>1) {
                    charge = this.outsideDahakeDeliveryPrice
                }
                else if (this.deliveryaddress.areaId && this.deliveryaddress.areaId==1) {
                    charge = this.deliverySettingPrice
                }
                else{
                    charge = 0
                }
                return charge;
            }
        },
        watch: {

            'cart.userId': {
                handler (newValue, oldValue) {
                    if (newValue!=oldValue && newValue) {
                        this.user=this.users.filter(function(user){
                            return user.id==newValue ;
                        })[0];
                        this.clearProductItem()
                        this.getDiscountCalculationforManualCart(this.customerId)
                        this.deliveryaddress={}
                        this.cartItemsTemp=[]
                    }
                    else{
                        this.user={}
                    }
                },
                deep: true
            }
        },

    }
</script>

