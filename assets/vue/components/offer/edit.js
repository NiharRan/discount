Vue.component("multiselect", window.VueMultiselect.default);

var base_url = $("#base_url").val();
new Vue({
  el: "#resturant_edit_form",
  data: {
    formData: {
    },
    restaurant_new_banner: '',
    restaurant_new_logo: '',
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
      this.restaurant_new_banner = e.target.files[0];
    },
    selectLogo(e) {
      this.restaurant_new_logo = e.target.files[0];
    },
    formatTime(time) {
      return moment(time).format("HH:mm:SS");
    },
    formatDate(date) {
      return moment(date).format("YYYY-MM-DD");
    },
    async update(){
      // update vue object to self veriable
      var self = this;
      var formData = new FormData();
      // add user info to formData object

      /**
       * convert tags object array to tag name array
       * exp. [1, 2, 3, 4]
       */
      var tags = self.formData.tags.map(function (tag) {
        return tag.tag_id
      });

      formData.append("restaurant_moto", self.formData.restaurant_moto);
      formData.append("restaurant_id", self.formData.restaurant_id);
      formData.append("restaurant_name", self.formData.restaurant_name);
      formData.append("restaurant_contact_number", self.formData.restaurant_contact_number);
      formData.append("restaurant_email", self.formData.restaurant_email);
      formData.append("restaurant_address", self.formData.restaurant_address);
      formData.append("restaurant_open_at", self.formatTime(self.formData.restaurant_open_at)); // Exp: 21:31:45
      formData.append("restaurant_close_at", self.formatTime(self.formData.restaurant_close_at)); // Exp: 21:31:45
      formData.append("restaurant_establish_date", self.formatDate(self.formData.restaurant_establish_date)); // Exp: 21-01-2020
      formData.append("restaurant_banner", self.formData.restaurant_banner);
      formData.append("restaurant_logo", self.formData.restaurant_logo);
      formData.append("tags", tags);
      formData.append("restaurant_new_banner", self.restaurant_new_banner);
      formData.append("restaurant_new_logo", self.restaurant_new_logo);
      // send api post request to server
      const { data } = await axios.post(base_url + "restaurant/update", formData);
      // if form validation done
      if (data.check) {
        // if data updated in server
        if (data.success) {
          // show success message
          Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Restaurant updated successfully",
              showConfirmButton: false,
              timer: 1500
          });
          window.close();
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
    async fetchRestaurant(slug) {
      var self = this;
      // call request to server to fetch restaurant info
      var { data } = await axios.get(base_url+'restaurant/edit/'+slug);
      if (data.success) {
        self.formData = data.data;
      }
    },
    async fetchtags() {
      var self = this;
      var {data} = await axios.get(base_url+'tag/allactivetags');
      if (data.success) {
        self.tags = data.data;
      }
    }
  },
  created() {
    this.fetchtags();
    // first get the current url
    var urlString = window.location.href;
    // convert string to array
    var arr = urlString.split("/");
    // the last index value will be restaurant slug
    var slug = arr[arr.length - 1];
    if (slug.length > 0) {
      // fetch restaurant info
      this.fetchRestaurant(slug);
    }
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
