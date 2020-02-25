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
        template_id: '',
        restaurant_id: "1",
        restaurant_name: "Panshi Inn",
    },
    templates: [],
    errors: {
      offer_name: '',
      offer_discount: '',
      offer_start: '',
      offer_end: '',
      template_id: '',
    },
    isloading: false,
  },

  methods: {
    async store(){
      // store vue object to self veriable
      var self = this;
      var template_id = typeof self.formData.template_id == "object" ? self.formData.template_id.template_id : '';
      var data = new FormData();
      data.append("offer_name", self.formData.offer_name);
      data.append("restaurant_id", self.formData.restaurant_id);
      data.append("restaurant_name", self.formData.restaurant_name);
      data.append("offer_description", self.formData.offer_description);
      data.append("offer_discount", self.formData.offer_discount);
      data.append("offer_start", self.formData.offer_start);
      data.append("offer_end", self.formData.offer_end);
      data.append("template_id", template_id);
      // send api post request to server
      var {data} = await axios.post(base_url + "offer/store", data);
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
          window.close();
        }else {
            // if not successfull
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Something went wrong!"
            });
        }
      } else {
        self.errors = data.errors;
      }
    },
    async fetchtemplates() {
      var self = this;
      var {data} = await axios.get(base_url+'template/allactivetemplates');
      if (data.success) {
        self.templates = data.data
      }
    }
  },
  created() {
    this.fetchtemplates();
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
