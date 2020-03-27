Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#create_user",
  data: {
    formData: {
      name: '',
      contact_number: '',
      email: '',
      role: '',
      address: '',
      dob: "",
      city: "",
      country: "",
      postal_code: "",
      avatar: "",
      banner: "",
      username: "",
      password: "",
      confirm_password: ""
    },
    bannerUrl: base_url+"uploads/default/user/default-banner.png",
    avatarUrl: base_url+"uploads/default/user/default-avatar.jpg",
    usertypes: [],
    errors: {
        name: "",
        contact_number: "",
        email: "",
        role: "",
        confirm_password: "",
    },
    isloading: false,
    isPasswordMatched: false,
  },

  methods: {
    selectBanner(e) {
      var file = e.target.files[0];
      this.formData.banner = file;
      this.bannerUrl = URL.createObjectURL(file);
    },
    selectAvatar(e) {
      var file = e.target.files[0];
      this.formData.avatar = file;
      this.avatarUrl = URL.createObjectURL(file);
    },
    async store(){
        // store vue object to self veriable
        var self = this;
        var data = new FormData();
        // user date of birth
        var dob = self.formData.dob == "" ? "" : moment(this.formData.dob).format("YYYY-MM-DD");
        // add user info to formData object
        var role = typeof this.formData.role == "object" ? this.formData.role.role_id : this.formData.role;
        data.append("name", this.formData.name);
        data.append("contact_number", this.formData.contact_number);
        data.append("email", this.formData.email);
        data.append("role", role);
        data.append("city", this.formData.city);
        data.append("country", this.formData.country);
        data.append("postal_code", this.formData.postal_code);
        data.append("banner", this.formData.banner);
        data.append("avatar", this.formData.avatar);
        data.append("address", this.formData.address);
        data.append("dob", dob);
        data.append("username", this.formData.username);
        data.append("password", this.formData.password);
        // send api post request to server
        var {data} = await axios.post(base_url + "user/store", data);
        // if form validation done
        if (data.check) {
          // if data stored in server
          if (data.success) {
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
            window.open(base_url+"users", "_self");
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
    async fetchusertypes() { // this method fetch all active usertypes
      // set vue to self veriable 
      var self = this;
      // call the api get request through url
      var {data} = await axios.get(base_url+'user/types');
      if (data.success) {
        // if data is found
        // store them
        self.usertypes = data.data;
      }
    },
    checkIsPasswordMatch () {
      if(this.formData.password == this.formData.confirm_password) {
        this.isPasswordMatched = true;
        this.errors.confirm_password = "";
      }else {
        this.errors.confirm_password = "Password doesn't matched!";
      }
    }
  },
  created() {
    // after page is created call those method
    this.fetchusertypes();
  },
  filters: {
  }
});
