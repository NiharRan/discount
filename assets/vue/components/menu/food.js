Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#food_list",
  data: {
    fData: {
      food_id: '',
      food_name: '',
      food_lowest_price: '',
      category: {},
      food_tags: []
    },
    bannerUrl: base_url+"uploads/default/user/default-banner.png",
    categories: [],
    foods: [],
    menu_tags: [],
    userPermissions: [],
    links: '',
    search: '',
    errors: {
    },
    isLoading: false,
    isFetching: false
  },

  methods: {
    openModal(modalName) {
      // this method open modal which is id/class send by as param [modalName]
      $(modalName).modal('show');
    },
    cleanForm(modelName) {
      // clean fData form
      this.fData.food_name = '';
      this.fData.food_lowest_price = '';
      this.fData.category = {};
      this.fData.food_id = '';
      this.food_tags =  [];
      this.fetchFoods();
      $(modelName).modal("hide");
      // clean errors
      this.errors = {};
      this.isLoading = false;
    },
    async store() {
      this.isLoading = true;
      var query = new FormData();
      // add food tags to query object
      var foodTags = this.fData.food_tags.map(function (menu_tag) {
        return menu_tag.menu_tag_id;
      });
      // add food info to fData object
      var category_id = this.fData.category['category_id'] === undefined ? '' : this.fData.category.category_id;
      query.append("food_name", this.fData.food_name);
      query.append("food_tags", foodTags);
      query.append("category_id", category_id);
      query.append("food_lowest_price", this.fData.food_lowest_price);
      // send api post request to server
      var {data} = await axios.post(base_url + "menu/foods/store", query);
      // if form validation done
      if (data.check) {
        // if data stored in server
        if (data.success) {
          this.cleanForm("#foodCreateModal");
          // show success message
          Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Food info stored successfully",
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
      // add food tags to query object
      var foodTags = this.fData.food_tags.map(function (menu_tag) {
        return menu_tag.menu_tag_id;
      });
      // add food info to fData object
      var category_id = this.fData.category['category_id'] === undefined ? '' : this.fData.category.category_id;
      query.append("food_name", this.fData.food_name);
      query.append("food_id", this.fData.food_id);
      query.append("food_tags", foodTags);
      query.append("category_id", category_id);
      query.append("food_lowest_price", this.fData.food_lowest_price);
      // send api post request to server
      var {data} = await axios.post(base_url + "menu/foods/update", query);
      // if form validation done
      if (data.check) {
        // if data stored in server
        if (data.success) {
          this.cleanForm("#foodEditModal");
          // show success message
          Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Food info updated successfully",
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
        var {data} = await axios.get(base_url + "menu/foods/remove/" + id)
        if (data.success) {
          this.fetchFoods();
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "food deleted successfully",
            showConfirmButton: false,
            timer: 1500
          });
        }
      }
    },
    openFoodEditModel (food) {
      this.isLoading = true;
      this.fData.food_id = food.food_id;
      this.fData.food_name = food.food_name;
      this.fData.food_lowest_price = food.food_lowest_price;
      var category = this.categories.find(function (category) {
        return category.category_id === food.category_id;
      })
      this.fData.food_tags = food.food_tags;
      this.fData.category = category;
      $('#foodEditModal').modal('show');
      this.isLoading = false;
    },
    fetchFoods() {
      /**
       * this method fetch foods from server
       * based on search information
       */
      var url = base_url + "menu/foods/all";
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
    async fetchCategories() { // this method fetch all active categories
        // call the api get request through url
        var {data} =  await axios.get(base_url+'menu/categories/active');
        if (data.success) {
          // if data is found
          // store them
          this.categories = data.data;
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
        this.foods = data.data;
        this.links = data.links
      }
    },
    async changeStatus(food) {
      /**
       * this method change food's status
       * @param food_status
       * @response success
       */
      var fData = new FormData();
      var self = this;
      var statusMsg = food.food_status == 1 ? "Inactive" : "Active";
      fData.append('food_status', food.food_status);
      fData.append('food_id', food.food_id);

      // send request to server
      var {data} = await axios.post(base_url+'menu/foods/change-status', fData);
      // if status updated
      if (data.success) {
        self.fetchFoods();
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

    async fetchTags(query) {
      this.isFetching = true;
      var fData = new FormData();
      fData.append('search', query);
      const response = await axios.get(base_url+'menu/menu-tags/all', fData);
      if (response.status === 200) {
        this.menu_tags = response.data.data;
        this.isFetching = false;
      }
    }
  },
  created() {
    // after page is created call those method
    this.fetchFoods();
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
    foodStatusText(value) {
        return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
    }
  }
});
