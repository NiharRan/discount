Vue.component("multiselect", window.VueMultiselect.default);
Vue.component(VueQrcode.name, VueQrcode);
var base_url = $("#base_url").val();
new Vue({
  el: "#offers_info",
  data: {
    formData: {
    },
    options: [],
    links: '',
    offers: [],
    userPermissions: [],
    offer: {},
    search: '',
    isloading: false,
  },

  methods: {
    customTime(value) {
      return moment(value, 'hh:mm A').format('hh:mm A');
    },
    
    preview(offer) {
      this.offer = offer;
      $('#previewModal').modal('show');
    },
    async changeStatus(offer) {
      /**
       * this method change offer's status
       * @param offer_status
       * @response success
       */
      var formData = new FormData();
      var self = this;
      var statusMsg = offer.offer_status == 1 ? "Inactive" : "Active";
      formData.append('offer_status', offer.offer_status);
      formData.append('offer_id', offer.offer_id);

      // send request to server
      var {data} = await axios.post(base_url+'offer/change-status', formData);
      // if status updated
      if (data.success) {
        self.fetchoffers();
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "offer now "+ statusMsg,
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    fetchoffers() {
      /**
       * this method fetch offers from server
       * based on search information
       */
      var url = base_url + "offers/all";
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
      });
      if (data.success) {
        // if data is found
        // store them
        self.offers = data.data;
        self.links = data.links
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
  },
  created() {
    // after page is created call those method
    this.fetchoffers();
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
    humanReadableFormat(date) {
      return moment(date).format("Do MMM, YYYY");
    },
    customTime(value) {
      return moment(value).format('hh:mm A');
    },
    offerstatusText(value) {
        return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
    }
  }
});
