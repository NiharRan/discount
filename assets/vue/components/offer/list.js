Vue.component("multiselect", window.VueMultiselect.default);
Vue.component(VueQrcode.name, VueQrcode);
var base_url = $("#base_url").val();
Vue.use(CKEditor);
new Vue({
    el: "#offers_info",
    data: {
        editor: ClassicEditor,
        editorConfig: {},
        formData: {},
        imageUrl: base_url + "uploads/default/offer/default-image.jpg",
        imageUrl: '',
        options: [],
        links: '',
        offers: [],
        templates: [],
        restaurants: [],
        errors: {
            offer_name: '',
            offer_description: '',
            template_id: '',
            offer_discount: '',
            offer_start: '',
            offer_end: '',
        },
        userPermissions: [],
        search: '',
        isloading: false,
    },

    methods: {
        selectImage(e) {
            var file = e.target.files[0];
            this.offer_new_image = file;
            this.imageUrl = URL.createObjectURL(file);
        },
        customTime(value) {
            return moment(value, 'hh:mm A').format('hh:mm A');
        },

        async preview(offer) {
            this.formData = offer;
            this.imageUrl = base_url + 'uploads/offer/offer-' + this.formData.offer_id + '/' + this.formData.offer_image;
            $('#previewModal').modal('show');
        },
        async update() {
            // update vue object to this variable
            var template_id = typeof this.formData.template == "object" ?
                this.formData.template.template_id : '';
            var restaurant_id = typeof this.formData.restaurant == "object" ?
                this.formData.restaurant.restaurant_id : '';
            var data = new FormData();
            data.append("offer_name", this.formData.offer_name);
            data.append("offer_description", this.formData.offer_description);
            data.append("offer_discount", this.formData.offer_discount);
            data.append("offer_start", this.formData.offer_start);
            data.append("offer_end", this.formData.offer_end);
            data.append("template_id", template_id);
            data.append("restaurant_id", restaurant_id);
            data.append("offer_id", this.formData.offer_id);
            data.append("offer_image", this.formData.offer_image);
            data.append("offer_new_image", this.offer_new_image);
            // send api post request to server
            var { data } = await axios.post(base_url + "offer/update", data);
            // if form validation done
            if (data.check) {
                // if data stored in server
                if (data.success) {
                    // show success message
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Offer updated successfully",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    this.formData = {};
                    this.fetchOffers();
                    $('#previewModal').modal('hide');
                } else {
                    // if not successful
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!"
                    });
                }
            } else {
                this.errors = data.errors;
            }
        },
        async changeStatus(offer) {
            /**
             * this method change offer's status
             * @param offer_status
             * @response success
             */
            var formData = new FormData();
            var statusMsg = offer.offer_status == 1 ? "Inactive" : "Active";
            formData.append('offer_status', offer.offer_status);
            formData.append('offer_id', offer.offer_id);

            // send request to server
            var { data } = await axios.post(base_url + 'offer/change-status', formData);
            // if status updated
            if (data.success) {
                this.fetchOffers();
                Swal.fire({
                    position: "top-end",
                    icon: "success",
                    title: "offer now " + statusMsg,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        },
        fetchOffers() {
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
            var { data } = await axios.get(url, {
                params: data
            });
            if (data.success) {
                // if data is found
                // store them
                self.offers = data.data;
                self.links = data.links
            }
        },
        async fetchAllActiveTemplates() {
            var { data } = await axios.get(base_url + "templates/active");
            if (data.success) {
                this.templates = data.data;
            }
        },
        async fetchAllActiveRestaurants() {
            var { data } = await axios.get(base_url + "restaurants/all/active");
            if (data.success) {
                this.restaurants = data.data;
            }
        },
        async authPermissions() {
            var response = await axios.get(base_url + 'auth/permissions');
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
        this.fetchOffers();
        this.authPermissions();
        this.fetchAllActiveTemplates();
        this.fetchAllActiveRestaurants();

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