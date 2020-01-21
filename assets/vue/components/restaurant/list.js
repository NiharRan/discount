Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#restaurants_info",
  data: {
    formData: {
    },
    options: [],
    links: '',
    restaurants: [],
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
