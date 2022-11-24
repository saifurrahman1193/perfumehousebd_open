
<script>
    export default {
        data() {
            return {
                current_url: window.location.origin+window.location.pathname
            }

        },
        mounted() {
        },
        methods: {
            gotorouterview(link){
                // this.$router.push(link)
                this.$router.push(link).catch(err => err)
            },

            gotorouterviewwithload(link){
                window.location = link
            },
            axiosPost(url){
                axios.post(url+'?token='+this.token)
                .then(function (response) {
                    alert('success')
                })
                .catch(function (error) {
                    alert('failure!')
                })
            },




            // ==========================string related==================================
            // ==========================string related==================================

            string_concat(separator=' ', args=[]){
                var str = '';
                args.forEach(element => {
                    str=str+(element || '')+separator
                });

                return str
            },

            // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Regular_Expressions
            // https://codeburst.io/javascript-learn-regular-expressions-for-beginners-bb6107015d91
            string_to_slug(str) {
                str = str.replace(/^\s+|\s+$/g, ""); // trim
                str = str.toLowerCase();

                // remove accents, swap ñ for n, etc
                var from = "åàáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
                var to = "aaaaaaeeeeiiiioooouuuunc------";

                for (var i = 0, l = from.length; i < l; i++) {
                    str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
                }

                str = str
                    .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
                    .replace(/\s+/g, "-") // collapse whitespace and replace by -
                    .replace(/-+/g, "-") // collapse dashes
                    .replace(/^-+/, "") // trim - from start of text
                    .replace(/-+$/, ""); // trim - from end of text

                return str;
            },

            string_to_normalbehaviour(str) {
                str = str.replace(/, ,/, ","); // trim
                str = str.replace(/,,/, ","); // trim
                str = str.replace(/^,/, ""); // trim
                str = str.replace(/,.$/, ""); // trim
                str = str.replace(/^\./, ""); // trim
                return str;
            },

            reverseString(str) {
                // Step 1. Use the split() method to return a new array
                var splitString = str.split(""); // var splitString = "hello".split("");
                // ["h", "e", "l", "l", "o"]

                // Step 2. Use the reverse() method to reverse the new created array
                var reverseArray = splitString.reverse(); // var reverseArray = ["h", "e", "l", "l", "o"].reverse();
                // ["o", "l", "l", "e", "h"]

                // Step 3. Use the join() method to join all elements of the array into a string
                var joinArray = reverseArray.join(""); // var joinArray = ["o", "l", "l", "e", "h"].join("");
                // "olleh"

                //Step 4. Return the reversed string
                return joinArray; // "olleh"
            },
            // ==========================string related==================================
            // ==========================string related==================================




            // ===================Date related===================
            // ===================Date related===================

            formatDate(date) {
                if (!date) return null;
                const [year, month, day] = date.split("-");
                return `${day}-${month}-${year}`;
            },
            formatDateMonthDateYear(date){
                if (!date) return null;
                const [year, month, day] = date.split("-");
                return this.getMonthShortFromMonthNumber(month)+` ${day}, ${year}`;
            },
            getMonthShortFromMonthNumber(monthNumber){
                var myArray = {id1: 'Jan', id2: 'Feb', id3: 'Mar', id4: 'Apr', id5: 'May', id6: 'Jun', id7: 'Jul', id8: 'Aug', id9: 'Sep', id10: 'Oct',id11: 'Nov', id12: 'Dec'  };

                var month = '';
                for (var key in myArray) {
                    // console.log("key " + key + " has value " + myArray[key]+' monthNumber '+parseInt(monthNumber));
                    if (key==('id'+parseInt(monthNumber)))
                    {
                        month =  myArray[key]
                        break;
                    }
                }

                return month;
            },

            getMonths(){
                var myArray = [
                    {monthId: 1, monthLong: 'January', monthShort: 'Jan'},
                    {monthId: 2, monthLong: 'February', monthShort: 'Feb'},
                    {monthId: 3, monthLong: 'March', monthShort: 'Mar'},
                    {monthId: 4, monthLong: 'April', monthShort: 'Apr'},
                    {monthId: 5, monthLong: 'May', monthShort: 'May'},
                    {monthId: 6, monthLong: 'Jun', monthShort: 'Jun'},
                    {monthId: 7, monthLong: 'July', monthShort: 'Jul'},
                    {monthId: 8, monthLong: 'August', monthShort: 'Aug'},
                    {monthId: 9, monthLong: 'September', monthShort: 'Sep'},
                    {monthId: 10, monthLong: 'October', monthShort: 'Oct'},
                    {monthId: 11, monthLong: 'November', monthShort: 'Nov'},
                    {monthId: 12, monthLong: 'December', monthShort: 'Dec'},
                ];
                return myArray;
            },

            getCurrentYear(){
                return (new Date().getFullYear());
            },
            getCurrentMonthNameLong(){
                const today = new Date()
                var month = today.toLocaleString('default', { month: 'long' })

                return month;
            },
            getCurrentMonthNumber(){
                return (new Date().getMonth())+1;
            },

            getTodayDateYmd(){
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();
                today = yyyy + '-' + mm + '-' + dd ;
                return today;
            },
            getTodayDateDmy(){
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();
                today = dd + '-' + mm + '-' + yyyy ;
                return today;
            },

            getCurrnentTimeInSecconds(){
                return Date.now();
            },

            datetimeprocessing(laraveldatetime){
                if(!laraveldatetime) return null;
                var [date, time] = laraveldatetime.split(" ");
                var getTodayDateYmd= this.getTodayDateYmd();

                if(getTodayDateYmd==date) {
                    return "Today at "+this.timeCalculator(time)
                }
                else {
                    if (this.yesterdayYmd()==date)
                    {
                        return "Yesterday at "+this.timeCalculator(time)
                    }
                    else
                    {
                        return this.formatDateMonthDateYear(date)+" at "+this.timeCalculator(time)
                    }
                }

            },

            YmdToDmy(dateData){
                if(!dateData) return null;

                console.log(dateData)

                var [year, month, day] = dateData.split("-");

                return day+'-'+month+'-'+year
            },

            timeCalculator(time){
                var [hour, min, seconds] = time.split(":")

                var finalResult = "";

                hour>12? finalResult = finalResult+(hour%12): finalResult = finalResult+hour;
                finalResult = finalResult+":"+min;
                hour>=12? finalResult = finalResult+" PM": finalResult = finalResult+ " AM";

                return finalResult;
            },

            yesterdayYmd()
            {
                var date = new Date();
                date.setDate(date.getDate()-1);
                return date.getFullYear()  + '-' +  String(date.getMonth()+1).padStart(2, '0')  + '-' +  String(date.getDate()).padStart(2, '0') ;
            },

            getTodayDayNumberOfCurrentMonth(){
                return new Date().getDate();
            },

            getTodayDayNumberOfCurrentYear(){
                var now = new Date();
                var start = new Date(now.getFullYear(), 0, 0);
                var diff = now - start;
                var oneDay = 1000 * 60 * 60 * 24;
                var day = Math.floor(diff / oneDay);

                return day;
            },

            getNumberOfDaysFrom2YmdDates(YmdDate1='', YmdDate2=''){

                var Difference_In_Days = 0;
                if(YmdDate1.length>0 && YmdDate2.length>0 ){
                    var date1 = new Date(YmdDate1);
                    var date2 = new Date(YmdDate2);

                    // To calculate the time difference of two dates
                    var Difference_In_Time = date2.getTime() - date1.getTime();

                    // To calculate the no. of days between two dates
                    var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
                }

                return Difference_In_Days;
            },


            get_countdown(countDownDate) {
                // console.log(countDownDate)
                // Set the date we're counting down to
                var countDownDate = new Date(countDownDate).getTime();
                // console.log(countDownDate)
                    // Update the count down every 1 second

                // Get today's date and time
                var now = (new Date()).getTime();

                // Find the distance between now and the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in an element with id="demo"
                // cd_data = days + "d " + hours + "h " +
                //     minutes + "m " + seconds + "s ";

                // If the count down is over, write some text
                // if (distance < 0) {
                //     clearInterval(x);
                //     cd_data = "EXPIRED";
                // }

                // console.log(days)
                // console.log(hours)
                // console.log(minutes)
                // console.log(seconds)

                if (distance < 0) {
                    return -1
                }

                return { days, hours, minutes, seconds }
            },


            // ===================Date related===================
            // ===================Date related===================



            // ====================Number related=============================
            // ====================Number related=============================
            numberWithCommas(number, decimals=0) {

                var decimalNumbers = '';
                if ((number.toString()).indexOf('.')>=0)  // if string/number has '.' , like 5.5, .56, 0.6
                {
                    decimalNumbers = (number.toString()).substr( (number.toString()).indexOf('.'));
                    decimalNumbers = decimalNumbers.substr( 1, decimals);
                }
                else
                {
                    decimalNumbers = '';
                    for (var i = 2; i <=decimals ; i++)
                    {
                        decimalNumbers = decimalNumbers+'0';
                    }
                }
                // return decimalNumbers;



                number = parseInt(number);
                number = number.toString();
                // // reverse
                number = this.reverseString(number.toString());

                var n = '';
                var stringlength = number.length;

                for (i = 0; i < stringlength; i++)
                {
                    if (i%2==0 && i!=stringlength-1 && i>1)
                    {
                        n = n+number[i]+',';
                    }
                    else
                    {
                        n = n+number[i];
                    }
                }

                number = n;
                // // reverse
                number = this.reverseString(number);

                (decimals!=0)? number=(number+'.'+decimalNumbers) : number ;


                return number;
            },
            // ====================Number related=============================
            // ====================Number related=============================



            topFunction() {
                // document.body.scrollTop = 0; // For Safari
                // document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
                window.scrollTo({ top: 0, behavior: "smooth" });
            },

            // =====================Object related=========================
            // =====================Object related=========================
                // {
                //     {id:1, name:'saifur'}
                // }
                // =============to==============
                // [
                //     {id:1, name:'saifur'}
                // ]
            objectToArray(obj)
            {
                if ((Array.isArray(obj))  )
                {
                    return obj;
                }
                else
                {
                    return Object.values(obj)
                }
            },
            arrayToObject(arr)
            {
                if ((arr||[]).length>0)
                {
                    return (arr)[0]
                }
                else
                {
                    return {}
                }
            },
            // =====================Object related=========================
            // =====================Object related=========================


            // =====================picture related========================
            // =====================picture related========================

            // getFileSize($event, 'category','picPath')
            getFileSizeWithPicPath(picPath)
            {
                if ((picPath||'').length>0)
                {
                    var fileSize = this.getFileSize(picPath);

                    if ( (fileSize/1024) >= 1024 )
                    {
                        fileSize= parseInt((fileSize/1024)/1024) + ' MB';
                    }
                    else{
                        fileSize=parseInt(fileSize/1024) + ' KB';
                    }
                    return fileSize;
                }
                return '';
            },

            getFileSize(url)
            {
                var fileSize = '';
                var http = new XMLHttpRequest();
                http.open('HEAD', url, false); // false = Synchronous

                http.send(null); // it will stop here until this http request is complete

                // when we are here, we already have a response, b/c we used Synchronous XHR

                if (http.status === 200) {
                    fileSize = http.getResponseHeader('content-length');
                }

                return fileSize;
            },

            get_url_extension( url ) {
                return url.split(/[#?]/)[0].split('.').pop().trim();
            },
            // =====================picture related========================
            // =====================picture related========================


            shuffle(array=[]) {
                var currentIndex = array.length,  randomIndex;
                while (0 !== currentIndex) {
                    randomIndex = Math.floor(Math.random() * currentIndex);
                    currentIndex--;
                    [array[currentIndex], array[randomIndex]] = [
                    array[randomIndex], array[currentIndex]];
                }
                return array;
            }


        },
        computed: {

            // frontend & backend
            isFrontend() {
                return !(this.$router.currentRoute.path).includes('/admin/')
            },
            isBackend() {
                return (this.$router.currentRoute.path).includes('/admin/')
            },


        },
    }

</script>

<style scoped>
    .hover-text-underline:hover{
        text-decoration: underline;
    }
</style>>


