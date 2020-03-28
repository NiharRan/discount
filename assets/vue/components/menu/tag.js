Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#tag",
  data: {
    formData: {
      menu_tags: [],
      menu_tag_name: '',
      menu_tag_id: ''
    },
    options: [],
    menu_tags: [],
    links: '',
    tagStatus: [],
    menu_tag_status: '',
    search: '',
    errors: {
        menu_tag_name: ""
    },
    isLoading: false,
  },

  methods: {
    cleanForm(modalName) {
      // hide loading icon
      this.isLoading = false;
      // clean formData form
      this.formData.menu_tags = [];
      // if updated then fetch tag list
      this.fetchMenuTags();
      // clean error object
      this.errors = {};
      // close modal
      $(modalName).modal("hide");
    },
    openModal(modalName) {
      // this method open modal which is id/class send by as param [modalName]
      $(modalName).modal('show');
    },
    addTag(newTag) {
      // this method add new tag to options
      const tag = {
        menu_tag_name: newTag,
      }
      this.options.push(tag);
    },
    async store(){
        // if tag form is not empty
        if (this.formData.menu_tags.length > 0) {
          // make loading icon visible
          this.isLoading = true;
          var data = new FormData();
          /**
           * convert menu_tags object array to tag name array
           * exp. [tag1, tag2, tag3, tag4]
           */
          var menu_tags = await this.formData.menu_tags.map(function (menu_tag) {
            return menu_tag.menu_tag_name
          })
          // add tag name array to formData object
          data.append("menu_tags", menu_tags);
          // send api post request to server
          var {data} = await axios.post(base_url + "menu/menu-tags/store", data)
          // if data stored in server
          if (data.success) {
            this.cleanForm('#tagCreateModal');
            // show success message
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Tags stored successfully",
              showConfirmButton: false,
              timer: 1500
            });
          } else {
              // if not successful
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Something went wrong!"
            });
          }
        }else {
            /**
             * if required field is empty
             * show set error message
             */
            if (this.formData.menu_tag_name == "") {
                this.errors.menu_tag_name = "Tag name is required";
            }
        }
    },
    async update() {
      var data = new FormData();

      // add tag name and id to formData object
      data.append("menu_tag_name", this.formData.menu_tag_name);
      data.append("menu_tag_id", this.formData.menu_tag_id);

      // send api request to server
      var {data} = await axios.post(base_url + "menu/menu-tags/update", data)
      if (data.check) {

        /**
         * if data validation done
         * check data updated or not
         */
        if (data.success) {
          this.cleanForm('#tagEditModal');
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
        // if form-validation field fetch error messages
        this.errors = data.errors;
      }
    },
    edit(menu_tag) {
      // open tag edit modal
      this.openModal('#tagEditModal');
      this.formData.menu_tag_name = menu_tag.menu_tag_name;
      this.formData.menu_tag_id = menu_tag.menu_tag_id;
    },
    async remove(id) {
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
        var {data} = await axios.get(base_url + "menu/menu-tags/remove/" + id)
        if (data.success) {
          this.fetchMenuTags();
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Tag deleted successfully",
            showConfirmButton: false,
            timer: 1500
          });
        }
      }
    },
    fetchMenuTags() {
      /**
       * this method fetch menu_tags from server
       * based on search information
       */
      var url = base_url + "menu/menu-tags/all";
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
    async getPaginateData(url, data) {
      // set vue to self variable
      // call the api get request through url
      var {data} = await axios.get(url, {
        params: data
      })
      if (data.success) {
        // if data is found
        // store them
        this.menu_tags = data.data;
        this.links = data.links
      }
    },
    async changeStatus(menu_tag) {
      /**
       * this method change tag's status
       * @param menu_tag_status
       * @response success
       */
      var formData = new FormData();
      var statusMsg = menu_tag.menu_tag_status == 1 ? "Inactive" : "Active";
      formData.append('menu_tag_status', menu_tag.menu_tag_status);
      formData.append('menu_tag_id', menu_tag.menu_tag_id);

      // send request to server
      var {data} = await axios.post(base_url+'menu/menu-tags/change-status', formData);
      // if status updated
      if (data.success) {
        this.fetchMenuTags();
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Tag now "+ statusMsg,
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
  },
  created() {
    // after page is created call those method
    this.fetchMenuTags();

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
    tagStatusText(value) {
        return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
    }
  }
});
