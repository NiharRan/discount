Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#feature_restaurants_info",
  data: {
    restaurants: [],
    featureRestaurants: [],
    authPermissions: [],
    search: '',
    defaultImageUrl: base_url+'assets/img/brand/factory.svg',
    bannerUrl: base_url + 'uploads/default/restaurant/default-banner.jpg',
    isloading: false,
  },

  methods: {
    customTime(value) {
      return moment(value, 'hh:mm A').format('hh:mm A');
    },
    openFeatureModal() {
      this.isloading = true;
      this.searchRestaurants();
      $('#featureModal').modal('show');
      var self = this;
      setTimeout(function () {
        self.isloading = false;
      }, 2000);
    },
    async authPermissions() {
      var response = await axios.get(base_url+'auth/permissions');
      if (response.status === 200) {
        this.userPermissions = response.data.data;
      }
    },
    async hasPermission(action, model_name) {
      /**
       * check loggedin user has permission to action user
       * @param action
       * 
       * @response true/false
       */
      var status = false;
      var data = await this.userPermissions.find((permission) => {
        return permission.action === action && permission.model_name === model_name;
      });
      // if status updated
      if (data !== null) {
        status = true;
      }
      return status;
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
        self.fetchFeatureRestaurants();
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Restaurant now "+ statusMsg,
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    async fetchFeatureRestaurants() {
    },
    async searchRestaurants() {
      var {data} = await axios.get(base_url+'restaurant/searchRestaurants/?search='+this.search);
      if (data.success) {
        this.restaurants = data.data;
      }else {
        this.restaurants = [];
      }
    }
  },
  created() {
    // after page is created call those method
    this.fetchFeatureRestaurants();
    this.authPermissions();
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
