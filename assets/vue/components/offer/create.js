Vue.component("multiselect", window.VueMultiselect.default);
var base_url = $("#base_url").val();


new Vue({
    el: "#offer_entry_form",
    data: {
        formData: {
            offer_name: '',
            offer_description: '',
            offer_discount: '',
            offer_start: '',
            offer_end: '',
            template_id: null,
            restaurant_id: null,
            offer_image: ''
        },
        templates: [],
        restaurants: [],
        errors: {
            offer_name: '',
            offer_discount: '',
            offer_start: '',
            offer_end: '',
            template_id: '',
            restaurant_id: '',
        },
        imageUrl: base_url + "uploads/default/offer/default-image.jpg",
        isloading: false,
    },

    methods: {
        selectImage(e) {
            var file = e.target.files[0];
            this.formData.offer_image = file;
            this.imageUrl = URL.createObjectURL(file);
        },
        async store() {
            // store vue object to this variable
            var template_id = this.formData.template_id != null ?
                this.formData.template_id.template_id : '';
            var restaurant_id = this.formData.restaurant_id != null ?
                this.formData.restaurant_id.restaurant_id : '';
            var restaurant_name = this.formData.restaurant_id != null ?
                this.formData.restaurant_id.restaurant_name : '';

            var data = new FormData();
            data.append("offer_name", this.formData.offer_name);
            data.append("restaurant_name", restaurant_name);
            data.append("offer_description", this.formData.offer_description);
            data.append("offer_discount", this.formData.offer_discount);
            data.append("offer_start", this.formData.offer_start);
            data.append("offer_end", this.formData.offer_end);
            data.append("offer_image", this.formData.offer_image);
            data.append("template_id", template_id);
            data.append("restaurant_id", restaurant_id);
            // send api post request to server
            var { data } = await axios.post(base_url + "offer/store", data);
            // if form validation done
            if (data.check) {
                // if data stored in server
                if (data.success) {
                    // show success message
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Offer created successfully",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    window.location.href = base_url + "offers";
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
        async fetchAllActiveTemplates() {
            var { data } = await axios.get(base_url + 'template/allactivetemplates');
            if (data.success) {
                this.templates = data.data
            }
        },
        async fetchAllActiveRestaurants() {
            var { data } = await axios.get(base_url + "restaurants/all/active");
            if (data.success) {
                this.restaurants = data.data;
            }
        },
    },
    created() {
        this.fetchAllActiveTemplates();
        this.fetchAllActiveRestaurants();
    },
    mounted() {
        var vm = this;
        // Init tinymce
        tinymce.init({
            selector: 'textarea.tinymce', // textarea-id send from controller
            height: 300,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
                ' bold italic backcolor | alignleft aligncenter ' +
                ' alignright alignjustify | bullist numlist outdent indent |' +
                ' removeformat | help',
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tiny.cloud/css/codepen.min.css'
            ],
            setup: function(editor) {
                editor.on('keyup', function() {
                    var newContent = editor.getContent();
                    // Fire an event to let its parent know
                    vm.formData.offer_description = newContent;
                });
            }
        });
    },
    filters: {
        customDate(value) {
            return moment(value).format("DD-MM-YYYY");
        },
        templatestatusText(value) {
            return value == 1 ? 'Free' : value == 2 ? 'Assigned' : 'Inactive';
        }
    }
});