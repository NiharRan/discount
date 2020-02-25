Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
Vue.use( CKEditor );
new Vue({
  el: "#restaurants_info",
  data: {
    editor: ClassicEditor,
    editorConfig: {},
    formData: {
      restaurant_id: "",
      restaurant_name: "",
      template_id: "",
      offer_name: "",
      offer_description: "",
      offer_discount: "",
      offer_start: "",
      offer_end: "",
    },
    errors: {},
    options: [],
    links: '',
    restaurants: [],
    templates: [],
    restaurant: {},
    search: '',
    isloading: false,
  },

  methods: {
    customTime(value) {
      return moment(value, 'hh:mm A').format('hh:mm A');
    },
    async hasPermission(restaurant, action) {
      /**
       * check loggedin user has permission to edit restaurant
       * @param restaurant_id
       * @param restaurant_creator
       * 
       * @response true/false
       */
      var status = false;
      var formData = new FormData();
      formData.append('restaurant_id', restaurant.restaurant_id);
      formData.append('restaurant_creator', restaurant.restaurant_creator);
      formData.append('action', action);

      var {data} = await axios.post(base_url+'restaurants/check-permission', formData);
      // if status updated
      if (data.success) {
        status = true;
      }
      return status;
    },
    preview(restaurant) {
      this.restaurant = restaurant;
      $('#previewModal').modal('show');
      this.isloading = true;
      var self = this;
      setTimeout(function () {
        self.isloading = false;
      }, 2000);
    },
    openOfferModal(restaurant) {
      this.formData.restaurant_id = restaurant.restaurant_id;
      this.formData.restaurant_name = restaurant.restaurant_name;
      $('#offerModal').modal('show');
      this.isloading = true;
      var self = this;
      setTimeout(function () {
        self.isloading = false;
      }, 1000);
    },
    async createOffer() {
       // store vue object to self veriable
       var self = this;
       var template_id = typeof self.formData.template_id == "object" ? self.formData.template_id.template_id : '';
       var data = new FormData();
       data.append("offer_name", self.formData.offer_name);
       data.append("restaurant_id", self.formData.restaurant_id);
       data.append("restaurant_name", self.formData.restaurant_name);
       data.append("offer_description", self.formData.offer_description);
       data.append("offer_discount", self.formData.offer_discount);
       data.append("offer_start", self.formData.offer_start);
       data.append("offer_end", self.formData.offer_end);
       data.append("template_id", template_id);
       // send api post request to server
       var {data} = await axios.post(base_url + "offer/store", data);
       // if form validation done
       if (data.check) {
         // if data stored in server
         if (data.success) {
           // show success message
           Swal.fire({
               position: "top-end",
               icon: "success",
               title: "Offer created successfully",
               showConfirmButton: false,
               timer: 1500
           });
           $("#offerModal").modal("hide");
           Object.keys(self.formData).forEach(function (key) {
             self.formData[key] = "";
           })
         }else {
             // if not successfull
             Swal.fire({
               icon: "error",
               title: "Oops...",
               text: "Something went wrong!"
             });
         }
       } else {
         self.errors = data.errors;
       }
    },
    async changeStatus(restaurant) {
      /**
       * this method change restaurant's status
       * @param restaurant_status
       * @response success
       */
      var formData = new FormData();
      var self = this;
      var statusMsg = restaurant.restaurant_status == 1 ? "Inactive" : "Active";
      formData.append('restaurant_status', restaurant.restaurant_status);
      formData.append('restaurant_id', restaurant.restaurant_id);

      // send request to server
      var {data} = await axios.post(base_url+'restaurant/change-status', formData);
      // if status updated
      if (data.success) {
        self.fetchrestaurants();
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Restaurant now "+ statusMsg,
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    async fetchAllActiveTemplates() {
      var {data} = await axios.get(base_url+"templates/active");
      if (data.success) {
        this.templates = data.data;
      }
    },
    fetchrestaurants() {
      /**
       * this method fetch restaurants from server
       * based on search information
       */
      var url = base_url + "restaurants/all";
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
    async getPaginateData(url, data) {
      // set vue to self veriable
      var self = this;
      // call the api get request through url
      var {data} = await axios.get(url, {
        params: data
      });
      if (data.success) {
        // if data is found
        // store them
        self.restaurants = data.data;
        self.links = data.links
      }
    },
  },
  created() {
    // after page is created call those method
    this.fetchrestaurants();
    this.fetchAllActiveTemplates();

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
    customTime(value) {
      return moment(value).format('hh:mm A');
    },
    restaurantStatusText(value) {
        return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
    }
  }
});
