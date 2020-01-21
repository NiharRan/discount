Vue.component("multiselect", window.VueMultiselect.default);

$('#restaurant_open_at,#restaurant_close_at').datepicker();

var base_url = $("#base_url").val();
new Vue({
  el: "#resturant_entry_form",
  data: {
    formData: {
        restaurant_moto: '',
        restaurant_name: '',
        restaurant_contact_number: '',
        restaurant_email: '',
        restaurant_logo: '',
        restaurant_banner: '',
        restaurant_address: '',
        restaurant_open_at: '',
        restaurant_close_at: '',
        restaurant_establish_date: '',
        tags: []
    },
    bannerUrl: "url("+base_url+"uploads/default/restaurant/banner.jpg)",
    logoUrl: "url("+base_url+"uploads/default/restaurant/logo.png)",
    tags: [],
    errors: {
      restaurant_name: '',
      restaurant_address: '',
      restaurant_open_at: '',
      restaurant_close_at: '',
      restaurant_establish_date: '',
    },
    isloading: false,
  },

  methods: {
    selectBanner(e) {
      this.formData.restaurant_banner = e.target.files[0];
    },
    selectLogo(e) {
      this.formData.restaurant_logo = e.target.files[0];
    },
    store(){
      // store vue object to self veriable
      var self = this;
      var data = new FormData();
      // add user info to formData object

      /**
       * convert tags object array to tag name array
       * exp. [1, 2, 3, 4]
       */
      var tags = self.formData.tags.map(function (tag) {
        return tag.tag_id
      });

      data.append("restaurant_moto", self.formData.restaurant_moto);
      data.append("restaurant_name", self.formData.restaurant_name);
      data.append("restaurant_contact_number", self.formData.restaurant_contact_number);
      data.append("restaurant_email", self.formData.restaurant_email);
      data.append("restaurant_address", self.formData.restaurant_address);
      data.append("restaurant_open_at", self.formData.restaurant_open_at);
      data.append("restaurant_close_at", self.formData.restaurant_close_at);
      data.append("restaurant_establish_date", self.formData.restaurant_establish_date);
      data.append("restaurant_banner", self.formData.restaurant_banner);
      data.append("restaurant_logo", self.formData.restaurant_logo);
      data.append("tags", tags);
      // send api post request to server
      axios
      .post(base_url + "restaurant/store", data)
      .then(function({ data }) {
          // if form validation done
          if (data.check) {
            // if data stored in server
            if (data.success) {
              // show success message
              Swal.fire({
                  position: "top-end",
                  icon: "success",
                  title: "Restaurant created successfully",
                  showConfirmButton: false,
                  timer: 1500
              });
              location.reload();
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
    fetchtags() {
      var self = this;
      axios
      .get(base_url+'tag/allactivetags')
      .then(function ({ data }) {
        if (data.success) {
          self.tags = data.data
        }
      })
    }
  },
  created() {
    this.fetchtags();
  },
  filters: {
    customDate(value) {
      return moment(value).format("DD-MM-YYYY");
    },
    tagStatusText(value) {
        return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
    }
  }
});
