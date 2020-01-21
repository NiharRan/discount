Vue.component("multiselect", window.VueMultiselect.default);
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
