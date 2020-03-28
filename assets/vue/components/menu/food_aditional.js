Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#food_aditional_list",
  data: {
    formData: {
      food_aditional_id: '',
      food_aditional_name: '',
      food_aditional_price: '',
      food_aditional_weight: '',
    },
    bannerUrl: base_url+"uploads/default/user/default-banner.png",
    food_aditionals: [],
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
      this.formData.food_aditional_name = '';
      this.formData.food_aditional_price = '';
      this.formData.food_aditional_weight = '';
      this.formData.food_aditional_id = '';
      this.fetchFoodAditionals();
      $(modelName).modal("hide");
      this.isLoading = false;
      // clean errors
      this.errors = {};
    },
    async store() {
      this.isLoading = true;
      var query = new FormData();
      // add food price info to formData object
      query.append("food_aditional_name", this.formData.food_aditional_name);
      query.append("food_aditional_price", this.formData.food_aditional_price);
      query.append("food_aditional_weight", this.formData.food_aditional_weight);
      // send api post request to server
      var {data} = await axios.post(base_url + "menu/food-aditionals/store", query);
      // if form validation done
      if (data.check) {
        // if data stored in server
        if (data.success) {
          this.cleanForm("#foodAditionalCreateModal");
          // show success message
          Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Food aditional info stored successfully",
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
      query.append("food_aditional_name", this.formData.food_aditional_name);
      query.append("food_aditional_price", this.formData.food_aditional_price);
      query.append("food_aditional_id", this.formData.food_aditional_id);
      query.append("food_aditional_weight", this.formData.food_aditional_weight);
      // send api post request to server
      var {data} = await axios.post(base_url + "menu/food-aditionals/update", query);
      // if form validation done
      if (data.check) {
        // if data stored in server
        if (data.success) {
          this.cleanForm("#foodAditionalEditModal");
          // show success message
          Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Food aditional info updated successfully",
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
        var {data} = await axios.get(base_url + "menu/food-aditionals/remove/" + id)
        if (data.success) {
          this.fetchFoodAditionals();
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
    openFoodAditonalEditModel(food_aditional) {
      this.isLoading = true;
      this.formData.food_aditional_id = food_aditional.food_aditional_id;
      this.formData.food_aditional_name = food_aditional.food_aditional_name;
      this.formData.food_aditional_price = food_aditional.food_aditional_price;
      this.formData.food_aditional_weight = food_aditional.food_aditional_weight;
      $('#foodAditionalEditModal').modal('show');
      this.isLoading = false;
    },
    fetchFoodAditionals() {
      /**
       * this method fetch foods from server
       * based on search information
       */
      var url = base_url + "menu/food-aditionals/all";
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
    async getPaginateData(url, data) { //this method fetch foods 
      // call the api get request through url
      var {data} =  await axios.get(url, {
        params: data
      })
      if (data.success) {
        // if data is found
        // store them
        this.food_aditionals = data.data;
        this.links = data.links
      }
    },
    async changeStatus(food_aditional) {
      /**
       * this method change food_aditional's status
       * @param food_aditional_status
       * @response success
       */
      var formData = new FormData();
      var self = this;
      var statusMsg = food_aditional.food_aditional_status == 1 ? "Inactive" : "Active";
      formData.append('food_aditional_status', food_aditional.food_aditional_status);
      formData.append('food_aditional_id', food_aditional.food_aditional_id);

      // send request to server
      var {data} = await axios.post(base_url+'menu/food-aditionals/change-status', formData);
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
    this.authPermissions();
    this.fetchFoodAditionals();

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
