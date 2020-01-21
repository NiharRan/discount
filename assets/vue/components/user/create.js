Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#create_user",
  data: {
    formData: {
      name: '',
      contact_number: '',
      email: '',
      user_type: ''
    },
    usertypes: [],
    errors: {
        name: "",
        contact_number: "",
        email: "",
        user_type: ""
    },
    isloading: false,
  },

  methods: {
    store(){
        // store vue object to self veriable
        var self = this;
        var data = new FormData();
        // add user info to formData object
        var user_type = typeof self.formData.user_type == "object" ? self.formData.user_type.user_type_name : self.formData.user_type;
        data.append("name", self.formData.name);
        data.append("contact_number", self.formData.contact_number);
        data.append("email", self.formData.email);
        data.append("user_type", user_type);
        // send api post request to server
        axios
        .post(base_url + "user/store", data)
        .then(function({ data }) {
            // if form validation done
            if (data.check) {
              // if data stored in server
              if (data.success) {
                // fetch userlist along with new tag
                self.fetchusers();
                // clean formData form
                Object.keys(self.formData).forEach(function (value, key) {
                    self.formData[key] = "";
                })
                // clean errors
                self.errors = {};
                // show success message
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "User created successfully",
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
        })
        .catch(function(errors) {
            console.log(errors);
        });
    },
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
  },
  created() {
    // after page is created call those method
    this.fetchusertypes();
  },
  filters: {
  }
});
