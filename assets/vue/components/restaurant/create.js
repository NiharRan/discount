Vue.component("multiselect", window.VueMultiselect.default);

var base_url = $("#base_url").val();
new Vue({
    el: "#resturant_entry_form",
    data: {
        formData: {
            restaurant_moto: '',
            restaurant_name: '',
            restaurant_contact_number: '',
            restaurant_email: '',
            restaurant_logo: '',
            restaurant_banner: '',
            restaurant_address: '',
            restaurant_open_at: '',
            restaurant_close_at: '',
            restaurant_establish_date: '',
            tags: []
        },
        bannerUrl: base_url + "uploads/default/restaurant/default-banner.jpg",
        logoUrl: base_url + "uploads/default/restaurant/default-logo.png",
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
        async store() {
            // store vue object to this veriable
            var data = new FormData();
            // add user info to formData object

            /**
             * convert tags object array to tag name array
             * exp. [1, 2, 3, 4]
             */
            var tags = this.formData.tags.map(function(tag) {
                return tag.tag_id
            });

            data.append("restaurant_moto", this.formData.restaurant_moto);
            data.append("restaurant_name", this.formData.restaurant_name);
            data.append("restaurant_contact_number", this.formData.restaurant_contact_number);
            data.append("restaurant_email", this.formData.restaurant_email);
            data.append("restaurant_address", this.formData.restaurant_address);
            data.append("restaurant_open_at", this.formData.restaurant_open_at);
            data.append("restaurant_close_at", this.formData.restaurant_close_at);
            data.append("restaurant_establish_date", this.formData.restaurant_establish_date);
            data.append("restaurant_banner", this.formData.restaurant_banner);
            data.append("restaurant_logo", this.formData.restaurant_logo);
            data.append("tags", tags);
            // send api post request to server
            var { data } = await axios.post(base_url + "restaurant/store", data);
            // if form validation done
            if (data.check) {
                // if data stored in server
                if (data.success) {
                    // show success message
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Restaurant created successfully",
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
        async fetchtags() {
            var { data } = await axios.get(base_url + 'tag/allactivetags')
            if (data.success) {
                this.tags = data.data
            }
        }
    },
    created() {
        this.fetchtags();
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