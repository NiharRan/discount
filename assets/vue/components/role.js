Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#role",
  data: {
    formData: {
      roles: [],
      role_name: '',
      role_id: ''
    },
    options: [],
    roles: [],
    userPermissions: [],
    links: '',
    roleStatus: [],
    role_status: '',
    search: '',
    errors: {
        role_name: ""
    },
    isLoading: false,
  },

  methods: {
    openModal(modalName) {
      // this method open modal which is id/class send by as param [modalName]
      $(modalName).modal('show');
    },
    async store(){
        // store vue object to self variable
        var self = this;
        // if role form is not empty
        if (self.formData.role_name != "") {
          // make loading icon visible
          self.isLoading = true;
          var data = new FormData();
          // add role name array to formData object
          data.append("role_name", self.formData.role_name);
          // send api post request to server
          var {data} = await axios.post(base_url + "settings/roles/store", data);
          // if data stored in server
          if (data.success) {
            // hide loading icon
            self.isLoading = false;
            // fetch role list along with new role
            self.fetchroles();
            // close roleCreateModal
            $('#roleCreateModal').modal('hide');
            // clean formData form
            Object.keys(self.formData).forEach(function (key) {
              self.formData[key] = "";
            })
            // clean errors
            self.errors = {};
            // show success message
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Role info stored successfully",
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
            /**
             * if required field is empty
             * show set error message
             */
            if (self.formData.role_name == "") {
                self.errors.role_name = "Role name is required";
            }
        }
    },
    async update() {
      var self = this;
      var data = new FormData();

      // add role name and id to formData object
      data.append("role_name", self.formData.role_name);
      data.append("role_id", self.formData.role_id);

      // send api request to server
      var {data} = await axios.post(base_url + "settings/roles/update", data);
      if (data.check) {

        /**
         * if data validation done
         * check data updated or not
         */
        if (data.success) {

          // if updated then fetch role List
          self.fetchroles();
          // clean error object
          self.errors = {};
          // close modal
          $("#roleEditModal").modal("hide");
          // show success message
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Role updated successfully",
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
        // if form-validation faild fetch error messages
        self.errors = data.errors;
      }
    },
    edit(role) {
      // open role edit modal
      this.openModal('#roleEditModal');
      this.formData.role_name = role.role_name;
      this.formData.role_id = role.role_id;
    },
    async remove(id) {
      var self = this;
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
        var {data} = await axios.get(base_url + "settings/roles/remove/" + id);
        if (data.success) {
          self.fetchroles();
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Role deleted successfully",
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
    fetchroles() {
      /**
       * this method fetch roles from server
       * based on search information
       */
      var url = base_url + "role/allroles/";
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
      });
      if (data.success) {
        // if data is found
        // store them
        self.roles = data.data;
        self.links = data.links
      }
    },
    async changeStatus(role) {
      var self = this;
      var msg = role.role_status == 1 ? "deactivated" : "activate";
      var result = await Swal.fire({
        title: "Are you sure?",
        text: role.role_name+" will be "+msg,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes"
      });
      if (result.value) {
        var formData = new FormData();
        formData.append("role_status", role.role_status);
        formData.append("role_id", role.role_id);
        var {data} = await axios.post(base_url + "settings/roles/change-status", formData);
        if (data.success) {
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: role.role_name+" "+msg+" successfully",
            showConfirmButton: false,
            timer: 1500
          });
          self.fetchroles();
        }
      }
    },
  },
  created() {
    // after page is created call those method
    this.fetchroles();
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
    roleStatusText(value) {
        return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
    }
  }
});
