Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();
new Vue({
  el: "#order_list",
  data: {
    formData: {
      order_status: '',
      order_id: ''
    },
    options: [],
    orders: [],
    order: {},
    totalPrice: 0,
    userPermissions: [],
    links: '',
    order_status: '',
    search: '',
    errors: {
    },
    isLoading: false,
  },

  methods: {
    cleanForm(modalName) {
      // hide loading icon
      this.isLoading = false;
      // clean formData form
      this.formData.orders = [];
      // if updated then fetch order list
      this.fetchOrders();
      // clean error object
      this.errors = {};
      // close modal
      $(modalName).modal("hide");
    },
    openModal(modalName) {
      // this method open modal which is id/class send by as param [modalName]
      $(modalName).modal('show');
    },
    sumOfFoodPrice() {
      return this.order.food_prices.map(function (food_price) {
        return parseFloat(food_price.food_price) + parseFloat(food_price.aditional_price);
      }).reduce(function (a, b) {
        return parseFloat(a) + parseFloat(b);
      })
    },
    sum(food_price, aditional_price) {
      return parseFloat(parseFloat(food_price) + parseFloat(aditional_price)).toFixed(2);
    },
    preview(order) {
      // open order edit modal
      this.openModal('#orderPreviewModal');
      this.order = order;
      var foodPrice = this.sumOfFoodPrice();
      this.totalPrice = parseFloat(foodPrice).toFixed(2);
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
        var {data} = await axios.get(base_url + "orders/remove/" + id)
        if (data.success) {
          this.fetchOrders();
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Order deleted successfully",
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
       * check logged in user has permission to action user
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
    fetchOrders() {
      /**
       * this method fetch orders from server
       * based on search information
       */
      var url = base_url + "orders/all";
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
        this.orders = data.data;
        this.links = data.links
      }
    },
    async changeStatus(order) {
      /**
       * this method change order's status
       * @param order_status
       * @response success
       */
      var formData = new FormData();
      var statusMsg = order.order_status == 1 ? "Inactive" : "Active";
      formData.append('order_status', order.order_status);
      formData.append('order_id', order.order_id);

      // send request to server
      var {data} = await axios.post(base_url+'orders/change-status', formData);
      // if status updated
      if (data.success) {
        this.fetchOrders();
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "order now "+ statusMsg,
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
  },
  created() {
    // after page is created call those method
    this.fetchOrders();
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
    orderStatusText(value) {
        return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
    }
  }
});
