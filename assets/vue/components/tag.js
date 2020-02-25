Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#tag",
  data: {
    formData: {
      tags: [],
      tag_name: '',
      tag_id: ''
    },
    options: [],
    tags: [],
    links: '',
    tagStatus: [],
    tag_status: '',
    search: '',
    errors: {
        tag_name: ""
    },
    isloading: false,
  },

  methods: {
    openModal(modalName) {
      // this method open modal which is id/class send by as param [modalName]
      $(modalName).modal('show');
    },
    addTag(newTag) {
      // this method add new tag to options
      const tag = {
        tag_name: newTag,
      }
      this.options.push(tag);
    },
    async store(){
        // store vue object to self veriable
        var self = this;
        // if tag form is not empty
        if (self.formData.tags.length > 0) {
          // make loading icon visiable
          self.isloading = true;
          var data = new FormData();
          /**
           * convert tags object array to tag name array
           * exp. [tag1, tag2, tag3, tag4]
           */
          var tags = await self.formData.tags.map(function (tag) {
            return tag.tag_name
          })
          // add tag name array to formData object
          data.append("tags", tags);
          // send api post request to server
          var {data} = await axios.post(base_url + "settings/tags/store", data)
          // if data stored in server
          if (data.success) {
            // hide loading icon
            self.isloading = false;
            // fetch taglist along with new tag
            self.fetchtags();
            // close tagcreateModal
            $('#tagCreateModal').modal('hide');
            // clean formData form
            self.formData.tags = [];
            // clean errors
            self.errors = {};
            // show success message
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Tags stored successfully",
              showConfirmButton: false,
              timer: 1500
            });
          } else {
              // if not successfull
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
            if (self.formData.tag_name == "") {
                self.errors.tag_name = "Tag name is required";
            }
        }
    },
    async update() {
      var self = this;
      var data = new FormData();

      // add tag name and id to formData object
      data.append("tag_name", self.formData.tag_name);
      data.append("tag_id", self.formData.tag_id);

      // send api request to server
      var {data} = await axios.post(base_url + "settings/tags/update", data)
      if (data.check) {

        /**
         * if data validation done
         * check data updated or not
         */
        if (data.success) {

          // if updateed then fetch taglist
          self.fetchtags();
          // clean error object
          self.errors = {};
          // close modal
          $("#tagEditModal").modal("hide");
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
        self.errors = data.errors;
      }
    },
    edit(tag) {
      // open tag edit modal
      this.openModal('#tagEditModal');
      this.formData.tag_name = tag.tag_name;
      this.formData.tag_id = tag.tag_id;
    },
    async remove(id) {
      var self = this;
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
        var {data} = await axios.get(base_url + "settings/tags/remove/" + id)
        if (data.success) {
          self.fetchtags();
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
    fetchtags() {
      /**
       * this method fetch tags from server
       * based on search information
       */
      var url = base_url + "tag/alltags/";
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
      // set vue to self veriable
      var self = this;
      // call the api get request through url
      var {data} = await axios.get(url, {
        params: data
      })
      if (data.success) {
        // if data is found
        // store them
        self.tags = data.data;
        self.links = data.links
      }
    },
    async changeStatus(tag) {
      /**
       * this method change tag's status
       * @param tag_status
       * @response success
       */
      var formData = new FormData();
      var self = this;
      var statusMsg = tag.tag_status == 1 ? "Inactive" : "Active";
      formData.append('tag_status', tag.tag_status);
      formData.append('tag_id', tag.tag_id);

      // send request to server
      var {data} = await axios.post(base_url+'settings/tags/change-status', formData);
      // if status updated
      if (data.success) {
        self.fetchtags();
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "tag now "+ statusMsg,
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
  },
  created() {
    // after page is created call those method
    this.fetchtags();

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
