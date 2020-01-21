Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#user_list",
  data: {
    formData: {
    },
    usertypes: [],
    users: [],
    links: '',
    search: '',
    errors: {
      name: '',
      username: '',
      user_type: ''
    },
    isloading: false,
  },

  methods: {
    openModal(modalName) {
      // this method open modal which is id/class send by as param [modalName]
      $(modalName).modal('show');
    },
    update() {
      var self = this;
      var data = new FormData();

      // add user name and id to formData object
      data.append("name", self.formData.name);
      data.append("user_id", self.formData.user_id);

      // send api request to server
      axios
        .post(base_url + "users/update", data)
        .then(function({ data }) {
          if (data.check) {

            /**
             * if data validation done
             * check data updated or not
             */
            if (data.success) {

              // if updateed then fetch userlist
              self.fetchusers();
              // clean error object
              self.errors = {};
              // close modal
              $("#userEditModal").modal("hide");
              // show success message
              Swal.fire({
                position: "top-end",
                icon: "success",
                title: "User updated successfully",
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
        })
        .catch(function(errors) {
          console.log(errors);
        });
    },
    edit(user) {
      // open user edit modal
      this.openModal('#userEditModal');
      this.formData.name = user.name;
      this.formData.user_id = user.user_id;
    },
    remove(id) {
      var self = this;
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        if (result.value) {
          axios
            .get(base_url + "users/remove/" + id)
            .then(function({ data }) {
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
            })
            .catch(function(error) {
              console.log(error);
            });
        }
      });
    },
    fetchusers() {
      /**
       * this method fetch users from server
       * based on search information
       */
      var url = base_url + "user/allusers";
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
        axios.get(base_url+'users/types')
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
    deactivate(user) {
      var self = this;
      Swal.fire({
        title: "Are you sure?",
        text: user.name+" will be deactivated",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, deactivate it!"
      }).then(result => {
        if (result.value) {
          axios
            .get(base_url + "user/changeStatusById/" + user.user_code+"/"+user.user_status)
            .then(function({ data }) {
              if (data.success) {
                self.fetchusers();
                Swal.fire({
                  position: "top-end",
                  icon: "success",
                  title: user.name+" deactivated successfully",
                  showConfirmButton: false,
                  timer: 1500
                });
              }
            })
            .catch(function(error) {
              console.log(error);
            });
        }
      });
    },
    activate(user) {
      var self = this;
      Swal.fire({
        title: "Are you sure?",
        text: user.name+" will be activate",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, active it!"
      }).then(result => {
        if (result.value) {
          axios
            .get(base_url + "user/changeStatusById/" + user.user_code+"/"+user.user_status)
            .then(function({ data }) {
              if (data.success) {
                self.fetchusers();
                Swal.fire({
                  position: "top-end",
                  icon: "success",
                  title: user.name+" activate successfully",
                  showConfirmButton: false,
                  timer: 1500
                });
              }
            })
            .catch(function(error) {
              console.log(error);
            });
        }
      });
    }
  },
  created() {
    // after page is created call those method
    this.fetchusers();
    this.fetchusertypes();

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
