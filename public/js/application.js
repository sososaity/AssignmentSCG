// Global storage for comunication between component 
const eventBus = new Vue();

// Search form section
var searchForm = new Vue({
    el: "#searchForm",
    data: {
        location: "",
        response: []
    },
    methods:{
        startSearchRestaurants: function(){

            // input is not empty
            if(this.location != ""){

                eventBus.$emit("clicked-event", this.location);
            }
        }
    }
})

// Create component
Vue.component('restaurant-item', {
    props: ['item'],
    template: '#restaurant-item'
})
  
// Result from search restaurants
var searchResult = new Vue({
    el: '#search-result-list',
    data: {
        restaurantList: "",
        location : ""
    },
    created (){
        eventBus.$on("clicked-event", location => { 

            this.location = location;

            // Call API 
            axios
                .get('http://127.0.0.1:8080/RestaurantsAPI/findRestaurants/' + location)
                .then(response => {

                    this.restaurantList = response.data.results

                    // Can not found restaurants near target location?
                    if(this.restaurantList.length == 0){

                        // Show message
                        eventBus.$emit("not-found-restaurants", true);
                    }else{

                        // Hide message
                        eventBus.$emit("not-found-restaurants", false);
                    }
                })
        });
    }
})

// Not found message component
var notFoundMessage = new Vue({
    el: '#not-found-message',
    data: {
        isShowing:false,
    },
    created() {
        eventBus.$on("not-found-restaurants", isShowing => { 
           this.isShowing = isShowing;
        });
    }
})