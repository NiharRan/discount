Vue.component("multiselect", window.VueMultiselect.default);

var base_url = $("#base_url").val();
new Vue({
    el: "#resturant_edit_form",
    data: {
        formData: {},
        bannerUrl: base_url + 'uploads/default/restaurant/default-banner.jpg',
        logoUrl: '',
        restaurant_new_banner: '',
        restaurant_new_logo: '',
        tags: [],
        errors: {
            restaurant_name: '',
            restaurant_address: '',
            restaurant_open_at: '',
            restaurant_close_at: '',
            restaurant_establish_date: '',
        },
        isloading: false,
    },

    methods: {
        selectBanner(e) {
            var file = e.target.files[0];
            this.formData.restaurant_banner = file;
            this.bannerUrl = URL.createObjectURL(file);
        },
        selectLogo(e) {
            var file = e.target.files[0];
            this.formData.restaurant_logo = file;
            this.logoUrl = URL.createObjectURL(file);
        },
        formatDate(date) {
            return moment(date).format("YYYY-MM-DD");
        },
        async update() {
            var formData = new FormData();
            // add user info to formData object

            /**
             * convert tags object array to tag name array
             * exp. [1, 2, 3, 4]
             */
            var tags = this.formData.tags.map(function(tag) {
                return tag.tag_id
            });

            formData.append("restaurant_moto", this.formData.restaurant_moto);
            formData.append("restaurant_id", this.formData.restaurant_id);
            formData.append("restaurant_name", this.formData.restaurant_name);
            formData.append("restaurant_contact_number", this.formData.restaurant_contact_number);
            formData.append("restaurant_email", this.formData.restaurant_email);
            formData.append("restaurant_address", this.formData.restaurant_address);
            formData.append("restaurant_open_at", this.formData.restaurant_open_at); // Exp: 21:31:45
            formData.append("restaurant_close_at", this.formData.restaurant_close_at); // Exp: 21:31:45
            formData.append("restaurant_establish_date", this.formatDate(this.formData.restaurant_establish_date)); // Exp: 21-01-2020
            formData.append("restaurant_banner", this.formData.restaurant_banner);
            formData.append("restaurant_logo", this.formData.restaurant_logo);
            formData.append("tags", tags);
            formData.append("restaurant_new_banner", this.restaurant_new_banner);
            formData.append("restaurant_new_logo", this.restaurant_new_logo);
            // send api post request to server
            const { data } = await axios.post(base_url + "restaurant/update", formData);
            // if form validation done
            if (data.check) {
                // if data updated in server
                if (data.success) {
                    // show success message
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Restaurant updated successfully",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.href = base_url + 'restaurants';
                } else {
                    // if not successfull
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
        async fetchRestaurant(slug) {
            // call request to server to fetch restaurant info
            var { data } = await axios.get(base_url + 'restaurant/edit/' + slug);
            if (data.success) {
                this.formData = data.data[0];
            }
        },
        async fetchtags() {
            var { data } = await axios.get(base_url + 'tag/allactivetags');
            if (data.success) {
                this.tags = data.data;
            }
        }
    },
    created() {
        this.fetchtags();
        // first get the current url
        var urlString = window.location.href;
        // convert string to array
        var arr = urlString.split("/");
        // the last index value will be restaurant slug
        var slug = arr[arr.length - 1];
        if (slug.length > 0) {
            // fetch restaurant info
            this.fetchRestaurant(slug);
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