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
    },
    bannerUrl: "url("+base_url+"uploads/default/restaurant/banner.jpg)",
    logoUrl: "url("+base_url+"uploads/default/restaurant/logo.png)",
    options: [],
    links: '',
    tagStatus: [],
    tag_status: '',
    search: '',
    errors: {
        tag_name: ""
    },
    isloading: false,
  },

  method: {

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
