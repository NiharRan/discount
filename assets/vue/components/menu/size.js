Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#size",
  data: {
    formData: {
      food_size_name: '',
      food_size_id: ''
    },
    options: [],
    sizes: [],
    userPermissions: [],
    links: '',
    sizeStatus: [],
    food_size_status: '',
    search: '',
    errors: {
        food_size_name: ""
    },
    isLoading: false,
  },

  methods: {
    openModal(modalName) {
      // this method open modal which is id/class send by as param [modalName]
      $(modalName).modal('show');
    },
    cleanForm(modalName) {
      // hide loading icon
      this.isLoading = false;
      this.formData.food_size_name = '';
      // fetch size list along with new size
      this.fetchSizes();
      // close sizeCreateModal
      $(modalName).modal('hide');
      // clean formData form
      // clean errors
      this.errors = {};
    },
    async store(){
      this.isLoading = true;
        // if size form is not empty
        if (this.formData.food_size_name != "") {
          // make loading icon visible
          this.isLoading = true;
          var data = new FormData();
          // add size name array to formData object
          data.append("food_size_name", this.formData.food_size_name);
          // send api post request to server
          var {data} = await axios.post(base_url + "menu/food-sizes/store", data);
          // if data stored in server
          if (data.success) {
            this.cleanForm('#sizeCreateModal');
            // show success message
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Product size stored successfully",
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
        }else {
          this.isLoading = false;
            /**
             * if required field is empty
             * show set error message
             */
            if (this.formData.food_size_name == "") {
                this.errors.food_size_name = "Product size is required";
            }
        }
    },
    async update() {
      var data = new FormData();
      this.isLoading = true;
      // add size name and id to formData object
      data.append("food_size_name", this.formData.food_size_name);
      data.append("food_size_id", this.formData.food_size_id);

      // send api request to server
      var {data} = await axios.post(base_url + "menu/food-sizes/update", data);
      if (data.check) {

        /**
         * if data validation done
         * check data updated or not
         */
        if (data.success) {
          this.cleanForm("#sizeEditModal");
          // show success message
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Product size updated successfully",
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
        // if form-validation faild fetch error messages
        this.errors = data.errors;
      }
    },
    edit(size) {
      // open size edit modal
      this.openModal('#sizeEditModal');
      this.formData.food_size_name = size.food_size_name;
      this.formData.food_size_id = size.food_size_id;
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
      });
      if (result.value) {
        var {data} = await axios.get(base_url + "menu/food-sizes/remove/" + id);
        if (data.success) {
          this.fetchSizes();
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Product size deleted successfully",
            showConfirmButton: false,
            timer: 1500
          });
        }
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
    fetchSizes() {
      /**
       * this method fetch sizes from server
       * based on search information
       */
      var url = base_url + "menu/food-sizes/all/";
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
      // call the api get request through url
      var {data} = await axios.get(url, {
        params: data
      });
      if (data.success) {
        // if data is found
        // store them
        this.sizes = data.data;
        this.links = data.links
      }
    },
    async changeStatus(size) {
      var msg = size.food_size_status == 1 ? "deactivated" : "activate";
      var result = await Swal.fire({
        title: "Are you sure?",
        text: size.food_size_name+" will be "+msg,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes"
      });
      if (result.value) {
        var formData = new FormData();
        formData.append("food_size_status", size.food_size_status);
        formData.append("food_size_id", size.food_size_id);
        var {data} = await axios.post(base_url + "menu/food-sizes/change-status", formData);
        if (data.success) {
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: size.food_size_name+" "+msg+" successfully",
            showConfirmButton: false,
            timer: 1500
          });
          this.fetchSizes();
        }
      }
    },
  },
  created() {
    // after page is created call those method
    this.fetchSizes();
    this.authPermissions();
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
    sizeStatusText(value) {
        return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
    }
  }
});
