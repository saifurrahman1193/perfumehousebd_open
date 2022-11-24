<template>
    <div >


            <!-- <v-text-field
                hide-details
                append-icon="search"
                single-line
                placeholder="I'm looking for.."
                @keyup="searchOnKeyUp($event)"
                v-model="searchInput"
                ref="searchInput"
                light
                solo
                color="red accent-4"

                elevation="0"
                class="hidden-xs-only toggle pr-0"

            ></v-text-field>

             -->

            <div class="wrap" v-if="isMDAGT">
                <div class="search">
                    <input type="text" class="searchTerm" placeholder="Search for..."
                        @keyup="searchOnKeyUp($event)"
                        v-model="searchInput"
                        ref="searchInput"
                    >
                    <button type="submit" class="searchButton">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>

            <v-text-field
                hide-details
                single-line
                placeholder="Search for.."
                @keyup="searchOnKeyUp($event)"
                v-model="searchInput"
                ref="searchInput"
                light
                elevation="0"
                v-else
            ></v-text-field>
    </div>
</template>


<script>
    import responsive from '../../mixins/responsive.vue'

    export default {
        mounted() {
            if(this.$router.currentRoute.path=='/productlist' && this.$route.query.searchInput){
                this.searchInput = this.$route.query.searchInput
                this.$refs.searchInput.focus();
            }
            this.searchOnMount()

        },
        mixins: [responsive],
        data() {
            return {
                searchInput:''
            }
        },
        methods: {
            searchOnKeyUp(event){
                console.log(event.code)
                var codes = ['ControlLeft', 'ControlRight', 'AltLeft', 'AltRight', 'OSLeft', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Escape', 'ShiftLeft', 'ShiftRight', 'ContextMenu', 'CapsLock' ];
                if (codes.includes(event.code)) {
                    event.preventDefault();
                }
                else{
                    this.search()
                }
            },
            searchOnMount(event){
                this.searchInput=this.$route.query.searchInput
                this.search()
            },
            search(){
                if ((this.searchInput || []).length<3) {
                    this.$store.dispatch('setSearchLoadingFalse')
                    this.$store.commit('SET_SEARCH_PRODUCTS', []);
                }
                else{
                    this.topFunction()
                    this.gotorouterview('/productlist?searchInput='+this.searchInput)

                    this.$store.commit('SET_SEARCH_INPUT', this.searchInput)
                    this.$store.dispatch('setSearchLoading')
                    // this.$store.dispatch('setSearchProducts')
                    this.$store.dispatch('setSearchProductsWP')
                }
            },


        }
    }
</script>


<style scoped>

@import url(https://fonts.googleapis.com/css?family=Open+Sans);

body{
  background: #f2f2f2;
  font-family: 'Open Sans', sans-serif;
}

.search {
  width: 100%;
  position: relative;
  display: flex;
}

.searchTerm {
  width: 100%;
  border: 2px solid #C9151B;
  border-right: none;
  padding: 5px;
  height: 40px;
  border-radius: 5px 0 0 5px;
  outline: none;
  color: #9DBFAF;

}

.searchTerm:focus{
  color: #C9151B;
}

.searchButton {
  width: 40px;
  height: 40px;
  border: 1px solid #C9151B;
  background: #C9151B;
  text-align: center;
  color: #fff;
  border-radius: 0 5px 5px 0;
  cursor: pointer;
  font-size: 18px;
  padding: 0px 49px 0px 29px !important;
}

/*Resize the wrap to see the search bar change!*/
.wrap{
  width: 30%;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

</style>
