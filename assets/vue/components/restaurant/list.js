Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#restaurants_info",
  data: {
    formData: {
    },
    options: [],
    links: '',
    restaurants: [],
    search: '',
    isloading: false,
  },

  methods: {
    fetchrestaurants() {
      /**
       * this method fetch restaurants from server
       * based on search information
       */
      var url = base_url + "restaurant/allrestaurants/";
      var data = {
        search: this.search
      };
      /**
       * call pagination method
       * @param url string
       * @param data object
       */
      this.getPaginateData(url, data);
    },
    getPaginateData(url, data) {
      // set vue to self veriable
      var self = this;
      // call the api get request through url
      axios.get(url, {
        params: data
      })
      .then(function ({data}) {
        if (data.success) {
          // if data is found
          // store them
          self.restaurants = data.data;
          self.links = data.links
        }
      })
      .catch(function (error) {
        console.log(error);
      })
    },
  },
  created() {
    // after page is created call those method
    this.fetchrestaurants();

    var self = this;
    // if click on pagination link icon
    $(document).on("click", ".pagination li a", function(e) {
      e.preventDefault();
      // get the href attribute data using jquery attr method
      var urlString = $(this).attr("href");
      // convert the string url to url object
      var url = new URL(urlString);
      // get the basepath of url object
      var baseUrl = url.pathname;
      // get the params from url 
      url = url.search.substring(1, url.length);
      // split the new string url
      url = url.split("&");
      var data = {};
      /**
       * create an object using splited url array 
       */
      for (var i = 0; i < url.length; i++) {
        var params = url[i].split("=");
        data[params[0]] = params[1];
      }
      // call the pagination method
      self.getPaginateData(baseUrl, data);
    });
  },
  filters: {
    customDate(value) {
      return moment(value).format("DD-MM-YYYY");
    },
    restaurantStatusText(value) {
        return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
    }
  }
});
