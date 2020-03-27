Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#permission",
  data: {
    formData: {
      role_id: {},
      model_name: '',
      permission_id: '',
      action: ''
    },
    options: [],
    permissions: [],
    userPermissions: [],
    roles: [],
    links: '',
    permissionStatus: [],
    permission_status: '',
    search: '',
    errors: {
        permission_name: ""
    },
    isLoading: false,
  },

  methods: {
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
    openModal(modalName) {
      // this method open modal which is id/class send by as param [modalName]
      $(modalName).modal('show');
    },
    cleanForm() {
      // hide loading icon
      this.isLoading = false;
      // if updated then fetch permissionList
      this.fetchPermissions();
      // clean error object
      this.errors = {};
      this.formData.model_name = '';
      this.formData.permission_id = '';
      this.formData.action = '';
      this.formData.role_id = {};
    },
    async store(){
      // make loading icon visible
      this.isLoading = true;
      var data = new FormData();
      // add permission name array to formData object
      var role_id = this.formData.role_id['role_id'] === undefined ? '' : this.formData.role_id.role_id;
      data.append("role_id", role_id);
      data.append("model_name", this.formData.model_name);
      // send api post request to server
      var {data} = await axios.post(base_url + "settings/permissions/store", data)
      // if data stored in server
      if (data.check) {
        if (data.success) {
          // close permissionCreateModal
          $('#permissionCreateModal').modal('hide');
          // clean formData form
          this.cleanForm();
          // show success message
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Permissions stored successfully",
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
        this.errors = data.errors;
      }
    },
    async update() {
      var formData = new FormData();
      // make loading icon visible
      this.isLoading = true;
      // add permission name and id to formData object
      var role_id = this.formData.role_id['role_id'] === undefined ? this.formData.role_id : this.formData.role_id.role_id;
      formData.append("model_name", this.formData.model_name);
      formData.append("action", this.formData.action);
      formData.append("role_id", role_id);
      formData.append("permission_id", this.formData.permission_id);

      // send api request to server
      var {data} = await axios.post(base_url + "settings/permissions/update", formData);
      if (data.check) {

        /**
         * if data validation done
         * check data updated or not
         */
        if (data.success) {

          this.cleanForm();
          // close modal
          $("#permissionEditModal").modal("hide");
          // show success message
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Permission updated successfully",
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
        // if form-validation failed fetch error messages
        this.errors = data.errors;
      }
    },
    async edit(permission) {
      // open permission edit modal
      this.openModal('#permissionEditModal');
      this.formData.model_name = permission.model_name;
      this.formData.action = permission.action;
      this.formData.permission_id = permission.permission_id;
      var role = await this.roles.find((role) => {
        return role.role_id === permission.role_id;
      });
      this.formData.role_id = role;
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
        var {data} = await axios.get(base_url + "settings/permissions/remove/" + id)
        if (data.success) {
          this.fetchPermissions();
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Permission deleted successfully",
            showConfirmButton: false,
            timer: 1500
          });
        }
      }
    },
    fetchPermissions() {
      /**
       * this method fetch permissions from server
       * based on search information
       */
      var url = base_url + "access/allpermissions/";
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
      })
      if (data.success) {
        // if data is found
        // store them
        this.permissions = data.data;
        this.links = data.links
      }
    },
    async changeStatus(permission) {
      /**
       * this method change permission's status
       * @param permission_status
       * @response success
       */
      var formData = new FormData();
      var statusMsg = permission.permission_status == 1 ? "Inactive" : "Active";
      formData.append('permission_status', permission.permission_status);
      formData.append('permission_id', permission.permission_id);

      // send request to server
      var {data} = await axios.post(base_url+'settings/permissions/change-status', formData);
      // if status updated
      if (data.success) {
        this.fetchPermissions();
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Permission now "+ statusMsg,
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    async fetchModels() {
      var response = await axios.get(base_url+'roles/active');
      if (response.status === 200) {
        this.roles = response.data.data;
      }
    },
    async authPermissions() {
      var response = await axios.get(base_url+'auth/permissions');
      if (response.status === 200) {
        this.userPermissions = response.data.data;
      }
    },
    async hasPermission(action) {
      /**
       * check loggedin user has permission to action user
       * @param action
       * 
       * @response true/false
       */
      var status = false;
      var data = await this.userPermissions.find((permission) => {
        return permission.action === action;
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
    this.fetchPermissions();
    this.fetchModels();
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
    permissionStatusText(value) {
        return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
    }
  }
});
