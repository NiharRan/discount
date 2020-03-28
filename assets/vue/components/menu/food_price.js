Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#food_price_list",
  data: {
    formData: {
      food_price_id: '',
      food_price: '',
      food_weight: '',
      food_size: {},
      food: {}
    },
    bannerUrl: base_url+"uploads/default/user/default-banner.png",
    food_sizes: [],
    foods: [],
    food_prices: [],
    userPermissions: [],
    links: '',
    search: '',
    errors: {
      name: '',
      username: '',
      role: ''
    },
    isLoading: false,
  },

  methods: {
    openModal(modalName) {
      // this method open modal which is id/class send by as param [modalName]
      $(modalName).modal('show');
    },
    cleanForm(modelName) {
      // clean formData form
      this.formData.food_price = '';
      this.formData.food_weight = '';
      this.formData.food_size = {};
      this.formData.food = {};
      this.formData.food_price_id = '';
      this.fetchFoodPrices();
      $(modelName).modal("hide");
      this.isLoading = false;
      // clean errors
      this.errors = {};
    },
    async store() {
      this.isLoading = true;
      var query = new FormData();
      // add food price info to formData object
      var food_id = this.formData.food['food_id'] === undefined ?'' : this.formData.food.food_id;
      var food_size_id = this.formData.food_size['food_size_id'] === undefined ? '' : this.formData.food_size.food_size_id;
      query.append("food_price", this.formData.food_price);
      query.append("food_weight", this.formData.food_weight);
      query.append("food_id", food_id);
      query.append("food_size_id", food_size_id);
      // send api post request to server
      var {data} = await axios.post(base_url + "menu/food-prices/store", query);
      // if form validation done
      if (data.check) {
        // if data stored in server
        if (data.success) {
          this.cleanForm("#foodPriceCreateModal");
          // show success message
          Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Food price info stored successfully",
              showConfirmButton: false,
              timer: 1500
          });
        }else {
            // if not successful
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Something went wrong!"
            });
        }
      } else {
        this.isLoading = false;
        this.errors = data.errors;
      }
    },
    async update() {
      this.isLoading = true;
      var query = new FormData();
      // add food info to formData object
      var food_id = this.formData.food['food_id'] !==undefined ? this.formData.food.food_id : this.formData.food;
      var food_size_id = this.formData.food_size['food_size_id'] !== undefined ? this.formData.food_size.food_size_id : this.formData.food_size;
      query.append("food_price", this.formData.food_price);
      query.append("food_price_id", this.formData.food_price_id);
      query.append("food_weight", this.formData.food_weight);
      query.append("food_id", food_id);
      query.append("food_size_id", food_size_id);
      // send api post request to server
      var {data} = await axios.post(base_url + "menu/food-prices/update", query);
      // if form validation done
      if (data.check) {
        // if data stored in server
        if (data.success) {
          this.cleanForm("#foodPriceEditModal");
          // show success message
          Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Food price info updated successfully",
              showConfirmButton: false,
              timer: 1500
          });
        }else {
            // if not successful
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Something went wrong!"
            });
        }
      } else {
        this.isLoading = false;
        this.errors = data.errors;
      }
    },
    async remove(id) {
      var result = await Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      })
      if (result.value) {
        var {data} = await axios.get(base_url + "menu/food-prices/remove/" + id)
        if (data.success) {
          this.fetchFoodPrices();
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "food price info deleted successfully",
            showConfirmButton: false,
            timer: 1500
          });
        }
      }
    },
    openFoodEditModel (food_price) {
      this.isLoading = true;
      this.formData.food_price_id = food_price.food_price_id;
      this.formData.food_price = food_price.food_price;
      this.formData.food_weight = food_price.food_weight;
      var food = this.foods.find(function (row) {
        return row.food_id === food_price.food_id;
      })
      this.formData.food = food;
      var food_size = this.food_sizes.find(function (row) {
        return row.food_size_id === food_price.food_size_id;
      })
      this.formData.food_size = food_size;
      $('#foodPriceEditModal').modal('show');
      this.isLoading = false;
    },
    fetchFoodPrices() {
      /**
       * this method fetch foods from server
       * based on search information
       */
      var url = base_url + "menu/food-prices/all";
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
    /**
     * this method 
     */
    async fetchFoods() { // this method fetch all active foods
        // call the api get request through url
        var {data} =  await axios.get(base_url+'menu/foods/active');
        if (data.success) {
          // if data is found
          // store them
          this.foods = data.data;
      }
    },
    async fetchFoodSizes() { // this method fetch all active food sizes
      // call the api get request through url
      var {data} =  await axios.get(base_url+'menu/food-sizes/active');
      if (data.success) {
        // if data is found
        // store them
        this.food_sizes = data.data;
    }
  },
    async getPaginateData(url, data) { //this method fetch foods 
      // call the api get request through url
      var {data} =  await axios.get(url, {
        params: data
      })
      if (data.success) {
        // if data is found
        // store them
        this.food_prices = data.data;
        this.links = data.links
      }
    },
    async changeStatus(food_price) {
      /**
       * this method change food_price's status
       * @param food_price_status
       * @response success
       */
      var formData = new FormData();
      var self = this;
      var statusMsg = food_price.food_price_status == 1 ? "Inactive" : "Active";
      formquery.append('food_price_status', food_price.food_price_status);
      formquery.append('food_price_id', food_price.food_price_id);

      // send request to server
      var {data} = await axios.post(base_url+'menu/food-prices/change-status', formData);
      // if status updated
      if (data.success) {
        self.fetchFoodSizes();
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Food now "+ statusMsg,
          showConfirmButton: false,
          timer: 1500
        });
      }
    },

    async authPermissions() {
      var response = await axios.get(base_url+'auth/permissions');
      if (response.status === 200) {
        this.userPermissions = response.data.data;
      }
    },
    async hasPermission(action, model_name) {
      /**
       * check logged in user has permission to action user
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
  },
  created() {
    // after page is created call those method
    this.fetchFoodSizes();
    this.fetchFoods();
    this.authPermissions();
    this.fetchFoodPrices();

    var self = this;
    // if click on pagination link icon
    $(document).on("click", ".pagination li a", function(e) {
      e.preventDefault();
      // get the href attribute data using jquery attr method
      var urlString = $(this).attr("href");
      // convert the string url to url object
      var url = new URL(urlString);
      // get the base-path of url object
      var baseUrl = url.pathname;
      // get the params from url 
      url = url.search.substring(1, url.length);
      // split the new string url
      url = url.split("&");
      var data = {};
      /**
       * create an object using splitted url array 
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
    foodStatusText(value) {
        return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
    }
  }
});
