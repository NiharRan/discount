Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#category",
  data: {
    formData: {
      category_name: '',
      category_id: '',
      restaurant: {}
    },
    options: [],
    categories: [],
    restaurants: [],
    userPermissions: [],
    links: '',
    search: '',
    errors: {
        category_name: ""
    },
    isLoading: false,
    isFetching: false
  },

  methods: {
    openModal(modalName) {
      // this method open modal which is id/class send by as param [modalName]
      $(modalName).modal('show');
    },
    cleanForm(modalName) {
      this.isLoading = false;
      // if updated then fetch category list
      this.fetchCategories();
      // clean error object
      this.errors = {};
      // clean formData object
      this.formData.category_name = '';
      this.formData.category_id = '';
      this.formData.restaurant = {};
      // close modal
      $(modalName).modal("hide");
    },
    async store(){
      this.isLoading = true;
      // store vue object to self variable
      // make loading icon visible
      this.isLoading = true;
      var data = new FormData();
      // add category name array to formData 
      var restaurant_id = this.formData.restaurant['restaurant_id'] === undefined ? '' : this.formData.restaurant.restaurant_id;
      data.append("category_name", this.formData.category_name);
      data.append("restaurant_id", restaurant_id);
      
      // send api post request to server
      var {data} = await axios.post(base_url + "menu/categories/store", data)
      if (data.check) {

        /**
         * if data validation done
         * check data updated or not
         */
        // if data stored in server
      if (data.success) {
        this.cleanForm('#categoryCreateModal');
        // show success message
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Category stored successfully",
          showConfirmButton: false,
          timer: 1500
        });
      } else {
          // if not successful
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Something went wrong!"
        });
      }
      } else {
        this.isLoading = false;
        // if form-validation field fetch error messages
        this.errors = data.errors;
      }
    },
    async update() {
      var data = new FormData();
      this.isLoading = true;
      // add category name and id to formData object
      var restaurant_id = this.formData.restaurant['restaurant_id'] === undefined ? '' : this.formData.restaurant.restaurant_id;
      data.append("category_name", this.formData.category_name);
      data.append("category_id", this.formData.category_id);
      data.append("restaurant_id", restaurant_id);

      // send api request to server
      var {data} = await axios.post(base_url + "menu/categories/update", data)
      if (data.check) {

        /**
         * if data validation done
         * check data updated or not
         */
        if (data.success) {
          this.cleanForm("#categoryEditModal");
          // show success message
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Category updated successfully",
            showConfirmButton: false,
            timer: 1500
          });
        } else {
          // if not updated then show warning message
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Something went wrong!"
          });
        }
      } else {
        this.isLoading = false;
        // if form-validation field fetch error messages
        this.errors = data.errors;
      }
    },
    async openCategoryEditModel(category) {
      // open category edit modal
      this.openModal('#categoryEditModal');
      this.formData.category_name = category.category_name;
      this.formData.category_id = category.category_id;
      this.formData.restaurant = category.restaurant;
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
        var {data} = await axios.get(base_url + "menu/categories/remove/" + id)
        if (data.success) {
          this.fetchCategories();
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Category deleted successfully",
            showConfirmButton: false,
            timer: 1500
          });
        }
      }
    },
    fetchCategories() {
      /**
       * this method fetch categories from server
       * based on search information
       */
      var url = base_url + "menu/categories/all";
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
      // set vue to self variable
      var self = this;
      // call the api get request through url
      var {data} = await axios.get(url, {
        params: data
      })
      if (data.success) {
        // if data is found
        // store them
        self.categories = data.data;
        self.links = data.links
      }
    },
    async changeStatus(category) {
      /**
       * this method change category's status
       * @param category_status
       * @response success
       */
      var formData = new FormData();
      var self = this;
      var statusMsg = category.category_status == 1 ? "Inactive" : "Active";
      formData.append('category_status', category.category_status);
      formData.append('category_id', category.category_id);

      // send request to server
      var {data} = await axios.post(base_url+'menu/categories/change-status', formData);
      // if status updated
      if (data.success) {
        self.fetchCategories();
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Category now "+ statusMsg,
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
    async fetchRestaurants(query) {
      this.isFetching = true;
      if (query.length > 2) {
        var fData = new FormData();
        fData.append('search', query);
        const response = await axios.get(base_url+'restaurants/search', fData);
        if (response.status === 200) {
          this.restaurants = response.data.data;
          this.isFetching = false;
        }
      }
    }
  },
  created() {
    // after page is created call those method
    this.fetchCategories();
    this.authPermissions();

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
    categoriestatusText(value) {
        return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
    }
  }
});
