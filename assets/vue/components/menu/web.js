var base_url = $("#base_url").val();
var restaurant_slug = $("#restaurant_slug").val();
var productList = JSON.parse(localStorage.getItem('orderList')) || [];
new Vue({
  el: "#app",
  data: {
    formData: {
        food_name: '',
        food_id: '',
        food_price_id: '',
        order_description: '',
        food_prices: [],
        food_aditional_ids: [],

        customer_name: '',
        customer_surname: '',
        customer_street_no: '',
        customer_city: '',
        customer_phone: '',
        customer_email: '',
        order_priority: '',
        payment_type: ''
    },
    orderList: productList,
    productCount: productList.length,
    totalPrice: 0,
    userPermissions: [],
    categories: [],
    aditionals: [],
    restaurant: {},
    errors: {
        food_size_name: ""
    },
    isLoading: false,
  },

  methods: {
    cleanCart() {
      this.formData.customer_name = '';
      this.formData.customer_surname = '';
      this.formData.customer_street_no = '';
      this.formData.customer_city = '';
      this.formData.customer_phone = '';
      this.formData.customer_email = '';
      this.formData.order_priority = '';
      this.formData.payment_type = '';

      this.formData.food_name = '';
      this.formData.food_id = '';
      this.formData.food_price_id = '';
      this.formData.order_description = '';
      this.formData.food_prices = [];
      this.formData.food_aditional_ids = [];

      localStorage.removeItem('orderList');
      this.orderList = [];
      this.productCount = 0;
      this.totalPrice = 0;
    },
    async orderNow() {
      this.isLoading = true;
      var customerData = new FormData();
      // 1: First create customer
      // customer info
      customerData.append('customer_name', this.formData.customer_name);
      customerData.append('customer_surname', this.formData.customer_surname);
      customerData.append('customer_street_no', this.formData.customer_street_no);
      customerData.append('customer_city', this.formData.customer_city);
      customerData.append('customer_phone', this.formData.customer_phone);
      customerData.append('customer_email', this.formData.customer_email);
      customerData.append('order_priority', this.formData.order_priority);
      customerData.append('payment_type', this.formData.payment_type);

      var response = await axios.post(base_url+'customer', customerData);
      if (response.status === 200) {
        if (response.data.check) {
          if (response.data.success) {
            // If customer created successfully
            // 2: Then store order info
            this.storeOrderInfo(response.data.id);
          }
        } else {
          this.isLoading = false;
          this.errors = response.data.errors;
        }
      }
    },
    async storeOrderInfo(customer_id) {
      var orderData = new FormData();
            orderData.append('customer_id', customer_id);
            orderData.append('total_price', this.totalPrice);
            orderData.append('order_description', this.formData.order_description);
            orderData.append('order_priority', this.formData.order_priority);
            orderData.append('payment_type', this.formData.payment_type);
            orderData.append('restaurant_id', this.restaurant.restaurant_id);
            var response = await axios.post(base_url+'order/create', orderData);
            if (response.status === 200) {
              this.storeOrderItemInfo(response.data.id);
            }
    },
    async storeOrderItemInfo(order_id) {
      var status = true;
      // If order data stored successfully
      // 3: Then store order item info
      for (var i = 0; i < this.orderList.length; i++) {
        var orederItemData = new FormData();
        var aditional_ids = JSON.parse(this.orderList[i].food_aditional_ids);
        orederItemData.append('order_id', order_id);
        orederItemData.append('food_id', this.orderList[i].food_id);
        orederItemData.append('food_price_id', this.orderList[i].food_price_id);
        orederItemData.append('food_aditional_id', aditional_ids.toString());
        orederItemData.append('food_aditinal_price', this.orderList[i].food_aditional_price);

        var response = await axios.post(base_url+'order-item', orederItemData);
        if (response.status !== 200) status = false;
      }
      if (status) {
        alert('Order done');
        this.isLoading = false;
        this.cleanCart();
      }
    },
    openModal(modalName) {
      // this method open modal which is id/class send by as param [modalName]
      $(modalName).modal('show');
    },
    calculateAditionalPrice(food_aditional_ids) {
      var total = 0;
      var aditionals = this.aditionals;
      food_aditional_ids.forEach(function (id) {
        for (let i = 0; i < aditionals.length; i++) {
          if (aditionals[i].food_aditional_id === id) {
            total += parseFloat(aditionals[i].food_aditional_price);
          }
        }
      });
      return total;
    },
    addToCart() {
      var orderList = this.orderList;
      var food_prices = this.formData.food_prices;
      var food_price_id = this.formData.food_price_id;
      var food_price = food_prices.find(function (row) {
        return row.food_price_id === food_price_id;
      });
      var food_aditional_ids = this.formData.food_aditional_ids;
      var totalAditionalPrice = this.calculateAditionalPrice(food_aditional_ids);
      var newOrder = {
        food_name: this.formData.food_name,
        food_id: this.formData.food_id,
        food_size_name: food_price.food_size.food_size_name,
        food_size_id: food_price.food_size_id,
        food_price_id: food_price_id,
        food_weight: food_price.food_weight,
        food_price: food_price.food_price,
        food_aditional_price: totalAditionalPrice,
        order_description: this.formData.order_description,
        food_aditional_ids: JSON.stringify(food_aditional_ids),
      };
      orderList.push(newOrder);
      this.productCount++;
      this.totalPrice = parseFloat(parseFloat(this.totalPrice) + totalAditionalPrice + parseFloat(newOrder.food_price)).toFixed(2);
      localStorage.setItem('orderList', JSON.stringify(orderList));
      $('#productModal').modal('hide');

      // clean formData object
      this.formData.food_price_id = '';
      this.food_aditional_ids = [];
      this.formData.order_description = '';
      $('.food-aditional-id').removeAttr('checked');
    },
    removeFromCart(key) {
      var orderList = this.orderList;
      var removeOrder = orderList[key];
      orderList.splice(key, 1);
      localStorage.setItem('orderList', JSON.stringify(orderList));
      this.productCount--;
      this.totalPrice = parseFloat(parseFloat(this.totalPrice) - parseFloat(removeOrder.food_aditional_price) - parseFloat(removeOrder.food_price)).toFixed(2);
    },
    showPanelCart() {
      $('#panel-cart').addClass('show');
      $('#body-overlay').fadeIn(400);
    },
    hidePanelCart() {
      $('#panel-cart').removeClass('show');
      $('#body-overlay').fadeOut(400);
    },
    panelCartToggle() {
      var $panelCart = $('#panel-cart');
      if ($panelCart.hasClass('show')) {
        this.hidePanelCart();
      } else {
        this.showPanelCart();
      }
    },
    async showFoodInfoForEdit(food) {
      var response = await axios.get(base_url+'menu/foods/'+food.food_id);
      if (response.status === 200) {
        var food = response.data;
        this.showFoodInfo(food);
      }
    },
    async showFoodInfo(food) {
      var tag_names = await food.food_tags.map(function (food_tag) {
        return food_tag.menu_tag_name;
      });
      this.formData.tag_names = tag_names.toString(); 

      var tag_ids = await food.food_tags.map(function (food_tag) {
        return food_tag.menu_tag_id;
      });
      this.formData.tag_ids = tag_ids; 
      this.formData.food_name = food.food_name;
      this.formData.food_id = food.food_id;
      this.formData.food_lowest_price = food.food_lowest_price;
      this.formData.food_prices = food.food_prices;
      $('#productModal').modal('show');
    },
    async fetchCategories() {
      var formData = new FormData();
      formData.append('restaurant_slug', restaurant_slug);
      var response = await axios.post(base_url+'menu/web/fetch_categories', formData);
      if (response.status === 200) {
          this.categories = response.data.categories;
          this.aditionals = response.data.aditionals;
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
    calculateOrderPrice() {
      if (this.orderList.length > 0) {
        var totalPrice = 0;
        this.orderList.forEach(function(order) {
          totalPrice += parseFloat(order.food_price) + parseFloat(order.food_aditional_price);
        });
        this.totalPrice =  parseFloat(totalPrice).toFixed(2);
      }
    },
    async fetchRestaurantInfo() {
      var response = await axios.get(base_url+'restaurant/edit/'+restaurant_slug);
      if (response.status === 200) {
        this.restaurant = response.data.data;
      }
    }
  },
  created() {
    // after page is created call those method
    this.fetchCategories();
    this.authPermissions();
    this.calculateOrderPrice();
    this.fetchRestaurantInfo();
  },
  filters: {
    customDate(value) {
      return moment(value).format("DD-MM-YYYY");
    },
    sizeStatusText(value) {
        return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
    }
  }
});
