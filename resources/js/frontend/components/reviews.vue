<template>
    <div>
        <v-row class="pa-2" >
            <v-col>
                <div class="hidden-xs-only">
                    <h5 class="d-inline-flex font-weight-bold">Overall Rating:
                        <v-rating
                            background-color="orange lighten-3"
                            color="#C9151B"
                            medium
                            dense
                            readonly
                            v-model="avgRating"
                            half-increments
                        >
                        </v-rating>
                    </h5>
                    <h6 style="line-height:0px; font-size:12px;">{{(avgRating || 0).toFixed(2) }} out of 5.00 (based on {{reviews.length}} reviews)</h6>
                </div>
                <div class="hidden-sm-and-up">
                    <h6 class="d-inline-flex font-weight-bold">Overall Rating: </h6>
                    <br>
                    <v-rating
                        background-color="orange lighten-3"
                        color="#C9151B"
                        medium
                        dense
                        readonly
                        v-model="avgRating"
                        half-increments
                    >
                    </v-rating>
                    <h6 style="font-size:12px;">{{(avgRating || 0).toFixed(2) }} out of 5.00 (based on {{reviews.length}} reviews)</h6>
                </div>
            </v-col>
            <v-col>
                <v-spacer></v-spacer>
                <v-btn right @click="dialog_crud=true" v-if="isverifiedreviewer">W<span class="text-lowercase">rite a review</span></v-btn>
                <div v-else>
                    <v-chip outlined color="#C9151B" v-if="isMDAGT" >Only logged in customers who have purchased this product may leave a review.</v-chip>
                    <v-card outlined  class="orange--text pa-1" style="border-color:orange;"  v-else>Only logged in customers who have purchased this product may leave a review.</v-card>
                </div>
            </v-col>
        </v-row>

        <v-list dense>
            <template v-for="(review, index) in reviews_p_d" dense   >
                <v-divider :key="index" class="py-0 my-0"></v-divider>

                <v-list-item
                    :key="'R-'+index"
                >

                    <!-- <v-divider v-if="i>0" inset></v-divider> -->


                    <v-list-item-icon class="hidden-xs-only">
                        <v-rating
                            background-color="orange lighten-3"
                            color="#C9151B"
                            medium
                            dense
                            readonly
                            v-model="review.rating"
                            half-increments
                        >
                        </v-rating>

                    </v-list-item-icon>

                    <v-list-item-content style="max-width:150px;" class="hidden-xs-only">
                        <p class="d-inline-block">by <strong>{{review.reviewerName}}</strong></p>
                        <p>{{formatDate((review.created_at).substr(0, 10))}}</p>
                    </v-list-item-content>

                    <v-list-item-content class="hidden-sm-and-up">
                        <v-rating
                            background-color="orange lighten-3"
                            color="#C9151B"
                            medium
                            dense
                            readonly
                            v-model="review.rating"
                            half-increments
                        >
                        </v-rating>
                        <p class="d-inline-block">by <strong>{{review.reviewerName}}</strong></p>
                        <p>{{formatDate((review.created_at).substr(0, 10))}}</p>
                        <v-list-item-title v-text="review.review" class="hidden-sm-and-up" style="white-space: normal;"></v-list-item-title>
                    </v-list-item-content>



                    <v-list-item-content  class="hidden-xs-only">
                        <v-list-item-title v-text="review.review" style="white-space: normal;"></v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </template>

        </v-list>

        <v-row justify="center">
            <v-col >
                <v-container class="max-width">
                    <v-list>
                        <v-list-item v-for="i in review_length_d" :key="i" class="d-inline-block pa-0 mx-1" >
                            <v-btn small class="pa-0" @click="getProductReviewsWithPaginate(i)"
                            :class="review_current_page_d==i? 'red accent-4 white--text ':''"
                            >{{i}}</v-btn>
                        </v-list-item>
                    </v-list>
                </v-container>
            </v-col>
        </v-row>

        <v-dialog v-model="dialog_crud" max-width="500px"  @keydown.esc="close()"  @keydown.enter="crudConfirm()" persistent>

            <v-card>
                <v-card-title>
                    <v-spacer></v-spacer>
                    <span class="headline"> <span v-if="!editingId">Add a new </span><span v-if="editingId">Edit </span><strong>{{ formTitle }}</strong></span>
                    <v-spacer></v-spacer>
                </v-card-title>

                <v-form @submit.prevent="crudConfirm()">
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" sm="12" md="12">
                                    <v-rating
                                        v-model="review.rating"
                                        background-color="orange lighten-3"
                                        color="#C9151B"
                                        medium
                                        dense
                                        label="Rating"
                                        :rules="ratingRules"
                                    >
                                    </v-rating>
                                </v-col>

                                <v-col cols="12" sm="12" md="12">
                                    <v-textarea name="review" label="Review"
                                    v-model="review.review"
                                    auto-grow  outlined  rows="3"
                                    :rules="reviewRules"
                                    :error-messages="reviewError.review"
                                    counter
                                    clearable

                                    ></v-textarea>
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

        <modal_alert
            :s_alert="s_alert"
            s_msg="Successfully saved!"
            :e_alert="e_alert"
            e_msg="Something went wrong!"
            :w_alert="w_alert"
            w_msg="Do you really want to proceed?"
            :i_alert="i_alert"
            i_msg="Information "
            @cancelAlert="cancelAlert()"
            @cancelAlertAndProceed="cancelAlertAndProceed()"
        ></modal_alert>


    </div>
</template>

<script>
import modal_alert from './modals/alert.vue'
import modal_alert_mixin from './modals/alertMixins.vue'
import responsive from '../../mixins/responsive.vue'

export default {
    components: {
        modal_alert,
    },
    mounted() {
        this.verifiedReviewer()
    },
    mixins: [modal_alert_mixin, responsive],
    data () {
        return {
            token: localStorage.getItem('token'),
            reviews_p_d:this.reviews_p,
            review_current_page_d:this.review_current_page,
            review_length_d:this.review_length,
            review:{
                rating:0,
                review:'',
                productId:this.productId
            },
            reviewError: { review: '', rating:0 },
            reviewRules: [
                v => !!v || 'Review is required',
            ],
            ratingRules: [
                v => !!v || 'Rating is required',
            ],
            formTitle: "Review",
            isverifiedreviewer : 0
        }
    },
    props: ["reviews", "reviews_p", "review_current_page", "review_length", "productId"],
    computed: {
        avgRating: function(){
            var ratings = 0;
            var count = 0
            this.reviews.forEach(review => {
                ratings += parseInt(review.rating)
                count++
            })
            var avgRat = ratings / count;
            return avgRat
        }
    },
    methods: {
        getProductReviewsWithPaginate(page=1){
            var _this = this;
            axios.get('/api/getProductReviewsWithPaginate/'+this.productId+'?page='+page)
            .then(function (response) {
                _this.reviews_p_d = response.data.data.data;
                _this.review_current_page_d =  response.data.data.current_page
                _this.review_length_d =  response.data.data.last_page

            })
            .catch(function (error) {
            })
        },
        clearForm(){
            this.review= { rating:0, review: '', productId:this.productId }
            this.reviewError= { review: '', rating:0, productId:this.productId }
        },

        adding(){
            var _this=this
            axios.post('/api/addReview'+'?token='+this.token, this.review)
            .then(function (response) {
                _this.getProductReviewsWithPaginate()
                _this.s_alert = true;
                _this.dialog_crud = false
                _this.clearForm()
                _this.addingConfirmed=''
            })
            .catch(function (error) {
                _this.e_alert = true;
                _this.reviewError = { review: error.response.data.review, rating: error.response.data.rating,  };
            })
        },
        verifiedReviewer(){
            var _this = this;
            axios.get('/api/verifiedReviewer/'+this.productId+'?token='+this.token)
            .then(function (response) {
                _this.isverifiedreviewer = response.data.data;
            })
            .catch(function (error) {
            })
        },

    }
}
</script>
