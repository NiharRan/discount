Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#user_list",
  data: {
    formData: {
    },
    bannerUrl: base_url+"uploads/default/user/default-banner.png",
    avatarUrl: base_url+"uploads/default/user/default-avatar.jpg",
    usertypes: [],
    users: [],
    userPermissions: [],
    links: '',
    search: '',
    errors: {
      name: '',
      username: '',
      role: ''
    },
    isloading: false,
  },

  methods: {
    openModal(modalName) {
      // this method open modal which is id/class send by as param [modalName]
      $(modalName).modal('show');
    },
    selectBanner(e) {
      var file = e.target.files[0];
      this.formData.new_banner = file;
      this.bannerUrl = URL.createObjectURL(file);
    },
    selectAvatar(e) {
      var file = e.target.files[0];
      this.formData.new_avatar = file;
      this.avatarUrl = URL.createObjectURL(file);
    },
    async update() {
      // store vue object to self veriable
      var self = this;
      var data = new FormData();
      var dob = self.formData.dob == "0000-00-00" ? "" : moment(self.formData.dob).format('YYYY-MM-DD');
      // add user info to formData object
      var role = typeof this.formData.role == "object" ? this.formData.role.role_id : this.formData.role;
      data.append("name", this.formData.name);
      data.append("id", this.formData.id);
      data.append("contact_number", this.formData.contact_number);
      data.append("email", this.formData.email);
      data.append("role", role);
      data.append("city", this.formData.city);
      data.append("country", this.formData.country);
      data.append("postal_code", this.formData.postal_code);
      data.append("banner", this.formData.banner);
      data.append("avatar", this.formData.avatar);
      data.append("new_banner", this.formData.new_banner);
      data.append("new_avatar", this.formData.new_avatar);
      data.append("address", this.formData.address);
      data.append("dob", dob);
      data.append("username", this.formData.username);
      data.append("password", this.formData.password);
      // send api post request to server
      var {data} = await axios.post(base_url + "user/update", data);
      // if form validation done
      if (data.check) {
        // if data stored in server
        if (data.success) {
          // clean formData form
          self.formData = {};
          self.fetchusers();
          self.avatarUrl = base_url+"uploads/default/user/default-avatar.jpg";
          $("#userEditModal").modal("hide");
          // clean errors
          self.errors = {};
          // show success message
          Swal.fire({
              position: "top-end",
              icon: "success",
              title: "User info updated successfully",
              showConfirmButton: false,
              timer: 1500
          });
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
      })
      if (result.value) {
        var {data} = await axios.get(base_url + "user/remove/" + id)
        if (data.success) {
          self.fetchusers();
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "User deleted successfully",
            showConfirmButton: false,
            timer: 1500
          });
        }
      }
    },
    openUserEditModel (user) {
      this.formData = user;
      var userType = this.usertypes.find(function (type) {
        return user.role === type.role_id;
      })
      if (user.banner != '') {
        this.bannerUrl = base_url + 'uploads/user/user-'+user.id+'/'+user.banner;
      }
      if (user.avatar != '') {
        this.avatarUrl = base_url + 'uploads/user/user-'+user.id+'/'+user.avatar;
      }
      this.formData.role = userType;
      $('#userEditModal').modal('show');
      this.isloading = true;
      var self = this;
      setTimeout(function () {
        self.isloading = false;
      }, 1000);
    },
    fetchusers() {
      /**
       * this method fetch users from server
       * based on search information
       */
      var url = base_url + "user/all";
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
    fetchusertypes() { // this method fetch all active usertypes
        // set vue to self veriable 
        var self = this;
        // call the api get request through url
        axios.get(base_url+'user/types')
        .then(function ({data}) {
            if (data.success) {
                // if data is found
                // store them
                self.usertypes = data.data;
            }
        });
    },
    getPaginateData(url, data) { //this method fetch users 
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
          self.users = data.data;
          self.links = data.links
        }
      })
      .catch(function (error) {
        console.log(error);
      })
    },
    async changeStatus(user) {
      /**
       * this method change user's status
       * @param activated
       * @response success
       */
      var formData = new FormData();
      var self = this;
      var statusMsg = user.activated == 1 ? "Inactive" : "Active";
      formData.append('activated', user.activated);
      formData.append('id', user.id);

      // send request to server
      var {data} = await axios.post(base_url+'user/change-status', formData);
      // if status updated
      if (data.success) {
        self.fetchusers();
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "User now "+ statusMsg,
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
  },
  created() {
    // after page is created call those method
    this.fetchusers();
    this.fetchusertypes();
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
    userstatusText(value) {
        return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
    }
  }
});
