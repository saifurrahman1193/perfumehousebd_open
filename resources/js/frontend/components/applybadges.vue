<template>
    <div>
        <v-list-item v-for="(item, index) in discountbadges" :key="index" dense style="width: 300px; margin-left: -25px;">
            <v-list-item-content>
                <v-list-item-title>
                    <v-progress-linear
                    :color="item.color"
                    height="10"
                    :value="(availableBadgeDiscountPoints*100)/item.reqPointsToAchieve"
                    >
                        <strong>{{ Math.ceil((availableBadgeDiscountPoints*100)/item.reqPointsToAchieve) }}%</strong>
                    </v-progress-linear>
                </v-list-item-title>
                <v-list-item-subtitle>
                    <div class="row ma-0 pa-0">
                        <v-chip x-small outlined color="green" class="mr-1">{{ availableBadgeDiscountPoints+'/'+item.reqPointsToAchieve }}</v-chip>
                        <v-chip x-small outlined color="pink" class="mr-1">{{ item.discBadgeDiscountPercent+'% OFF' }}</v-chip>
                        <v-chip x-small outlined color="orange">
                            <v-icon x-small>arrow_upward</v-icon>
                            {{ item.discountUpToAmount+'/=' }}
                        </v-chip>
                        <v-spacer></v-spacer>
                        <v-chip x-small  dark color="green"
                            v-if="availableBadgeDiscountPoints>item.reqPointsToAchieve && (userInfo.discountBadgeId||'')!=item.discountBadgeId"
                            @click="enableCustToHoldBadgeDiscount(item.discountBadgeId)"
                            >
                            Apply
                        </v-chip>

                        <v-chip x-small  dark color="green"
                            v-if="availableBadgeDiscountPoints>item.reqPointsToAchieve && (userInfo.discountBadgeId||'')==item.discountBadgeId"
                        >
                            Applied
                        </v-chip>

                    </div>
                </v-list-item-subtitle>
            </v-list-item-content>
        </v-list-item>
    </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'

export default {
    components: {
    },
    data() {
        return {
            token:localStorage.getItem('token'),
            discountbadges: [],
            discountbadge: {},
            availableBadgeDiscountPoints: 0,
        }
    },
    mounted() {
        this.getTotAvailValidDiscBadgePointsForCU();
        this.getDiscountBadges();
    },
    methods: {
        getDiscountBadges(){
            var _this = this;
            axios.get('/api/getDiscountBadges')
            .then(function (response) {
                _this.discountbadges = response.data.data;
            })
            .catch(function (error) {
            })
        },

        enableCustToHoldBadgeDiscount(discountBadgeId){
            var _this = this;
            axios.post('/api/enableCustToHoldBadgeDiscount', { "token": this.token, "discountBadgeId": discountBadgeId})
            .then(function () {
                _this.gotorouterviewwithload(_this.current_url)
            })
            .catch(function () {
                alert("Something went wrong!")
            })
        },

        getTotAvailValidDiscBadgePointsForCU(){
            var _this = this;
            axios.get('/api/getTotAvailValidDiscBadgePointsForCU?token='+ this.token)
            .then(function (response) {
                _this.availableBadgeDiscountPoints = response.data.points
            })
            .catch(function () {
                _this.availableBadgeDiscountPoints = 0
            })
        },

    },
    computed: {
        ...mapState([
            'userInfo'
        ]),
        ...mapGetters([
        ]),
    },

    watch: {

    },

}
</script>
