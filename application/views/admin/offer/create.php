
 <div class="container-fluid" id="offer_entry_form">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0"><i class="fas fa-plus"></i> Create New Offer
                        <a href="<?php echo site_url('offers'); ?>" class="btn btn-sm btn-primary float-right"><i class="fas fa-arrow-left"></i> Back</a>
                    </h3>
                </div>
                <form @submit.prevent="store">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <input type="text" v-model="formData.offer_name" placeholder="Offer Name" class="form-control">
                                        <!-- if offer_name field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.offer_name }}</small>
                                    </div>
                                    <div class="col-md-6">
                                        <multiselect 
                                            v-model="formData.template_id" 
                                            :height="300"
                                            :options="templates" 
                                            :multiple="false" 
                                            :close-on-select="true" 
                                            :clear-on-select="true" 
                                            :preserve-search="true" 
                                            placeholder="Select Template"
                                            label="template_name" 
                                            track-by="template_name" 
                                            :preselect-first="false"
                                        >
                                        </multiselect>
                                        <!-- if template_id field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.template_id }}</small>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <textarea v-model="formData.offer_description" class="tinymce"></textarea>
                                        <!-- if offer_description field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.offer_description }}</small>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <input type="text" v-model="formData.offer_discount" placeholder="Offer Discount" class="form-control">
                                        <!-- if offer_discount field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.offer_discount }}</small>
                                    </div>
                                    <div class="col-md-4">
                                        <datetime 
                                            v-model="formData.offer_start" 
                                            placeholder="Offer Start At"
                                            auto>
                                        </datetime>
                                        <!-- if offer_start field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.offer_start }}</small>
                                    </div>
                                    <div class="col-md-4">
                                        <datetime 
                                            v-model="formData.offer_end" 
                                            placeholder="Offer End At"
                                            auto>
                                        </datetime>
                                        <!-- if offer_end field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.offer_end }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 text-center">
                                <button type="submit" class="btn btn-success btn-sm">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
