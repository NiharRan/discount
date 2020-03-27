Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#template",
  data: {
    formData: {
      templates: [],
      template_name: '',
      template_id: ''
    },
    options: [],
    templates: [],
    userPermissions: [],
    links: '',
    templateStatus: [],
    template_status: '',
    search: '',
    errors: {
        template_name: ""
    },
    isloading: false,
  },

  methods: {
    openModal(modalName) {
      // this method open modal which is id/class send by as param [modalName]
      $(modalName).modal('show');
    },
    addTemplate(newTemplate) {
      // this method add new template to options
      const template = {
        template_name: newTemplate,
      }
      this.options.push(template);
    },
    async store(){
        // store vue object to self veriable
        var self = this;
        // if template form is not empty
        if (self.formData.template_name != "") {
          // make loading icon visiable
          self.isloading = true;
          var data = new FormData();
          // add template name array to formData object
          data.append("template_name", self.formData.template_name);
          // send api post request to server
          var {data} = await axios.post(base_url + "settings/templates/store", data);
          // if data stored in server
          if (data.success) {
            // hide loading icon
            self.isloading = false;
            // fetch templatelist along with new template
            self.fetchtemplates();
            // close templatecreateModal
            $('#templateCreateModal').modal('hide');
            // clean formData form
            Object.keys(self.formData).forEach(function (key) {
              self.formData[key] = "";
            })
            // clean errors
            self.errors = {};
            // show success message
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Template info stored successfully",
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
            if (self.formData.template_name == "") {
                self.errors.template_name = "template name is required";
            }
        }
    },
    async update() {
      var self = this;
      var data = new FormData();

      // add Template name and id to formData object
      data.append("template_name", self.formData.template_name);
      data.append("template_id", self.formData.template_id);

      // send api request to server
      var {data} = await axios.post(base_url + "settings/templates/update", data);
      if (data.check) {

        /**
         * if data validation done
         * check data updated or not
         */
        if (data.success) {

          // if updateed then fetch templateList
          self.fetchtemplates();
          // clean error object
          self.errors = {};
          // close modal
          $("#templateEditModal").modal("hide");
          // show success message
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Template updated successfully",
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
    edit(template) {
      // open template edit modal
      this.openModal('#templateEditModal');
      this.formData.template_name = template.template_name;
      this.formData.template_id = template.template_id;
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
      });
      if (result.value) {
        var {data} = await axios.get(base_url + "settings/templates/remove/" + id);
        if (data.success) {
          self.fetchtemplates();
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Template deleted successfully",
            showConfirmButton: false,
            timer: 1500
          });
        }
      }
    },
    async authPermissions() {
      var response = await axios.get(base_url+'auth/permissions');
      if (response.status === 200) {
        this.userPermissions = response.data.data;
      }
    },
    async hasPermission(action, model_name) {
      /**
       * check loggedin user has permission to action user
       * @param action
       * 
       * @response true/false
       */
      var status = false;
      var data = await this.userPermissions.find((permission) => {
        return permission.action === action && permission.model_name === model_name;
      });
      // if status updated
      if (data !== null) {
        status = true;
      }
      return status;
    },
    fetchtemplates() {
      /**
       * this method fetch templates from server
       * based on search information
       */
      var url = base_url + "template/alltemplates/";
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
      var self = this;
      // call the api get request through url
      var {data} = await axios.get(url, {
        params: data
      });
      if (data.success) {
        // if data is found
        // store them
        self.templates = data.data;
        self.links = data.links
      }
    },
    async changeStatus(template) {
      var self = this;
      var msg = template.template_status == 1 ? "deactivated" : "activate";
      var result = await Swal.fire({
        title: "Are you sure?",
        text: template.template_name+" will be "+msg,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes"
      });
      if (result.value) {
        var formData = new FormData();
        formData.append("template_status", template.template_status);
        formData.append("template_id", template.template_id);
        var {data} = await axios.post(base_url + "settings/templates/change-status", formData);
        if (data.success) {
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: template.template_name+" "+msg+" successfully",
            showConfirmButton: false,
            timer: 1500
          });
          self.fetchtemplates();
        }
      }
    },
  },
  created() {
    // after page is created call those method
    this.fetchtemplates();
    this.authPermissions();
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
    templateStatusText(value) {
        return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
    }
  }
});
