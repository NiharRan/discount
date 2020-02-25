Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#profile",
  data: {
    user: {},
    bannerUrl: "",
    avatarUrl: "",
    errors: {
        name: "",
        username: "",
    },
    isloading: false,
  },

  methods: {
    async update() {
      var data = new FormData();

      // add tag name and id to formData object
      data.append("name", this.user.name);
      data.append("username", this.user.username);
      data.append("id", this.user.id);

      // send api request to server
      var {data} = await axios.post(base_url + "users/profile/update", data)
      if (data.check) {

        /**
         * if data validation done
         * check data updated or not
         */
        if (data.success) {

          // if updateed then fetch profile info
          this.fetchprofileinfo();
          // clean error object
          this.errors = {};
          // close modal
          // show success message
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Tag updated successfully",
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
        this.errors = data.errors;
      }
    },
    async fetchProfileInfo(username) {
        // call request to server to fetch restaurant info
        var { data } = await axios.get(base_url+'users/profile/'+username);
        if (data.success) {
          this.user = data.data;
          this.bannerUrl = base_url+'uploads/user/user-'+this.user.id+'/'+this.user.banner;
          this.avatarUrl = base_url+'uploads/user/user-'+this.user.id+'/'+this.user.avatar;
        }
    }
  },
  created() {
    // first get the current url
    var urlString = window.location.href;
    // convert string to array
    var arr = urlString.split("/");
    // the last index value will be profile slug
    var slug = arr[arr.length - 1];
    if (slug.length > 0) {
      // fetch profile info
    this.fetchProfileInfo(slug);
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
