
 <div class="container-fluid" id="create_user">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0"><i class="fas fa-plus"></i> Create New Admin
                        <a href="<?php echo site_url('users'); ?>" class="btn btn-sm btn-primary float-right"><i class="fas fa-arrow-left"></i> Back</a>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="col-md-8 col-sm-12 offset-md-2">
                        <form @submit.prevent="store">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" v-model="formData.name" class="form-control" placeholder="User Name">
                                        <!-- if name field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.name }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <input type="text" v-model="formData.contact_number" class="form-control" placeholder="User Contact Number">
                                        <!-- if contact_number field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.contact_number }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group mb-0">
                                        <input type="text" v-model="formData.email" class="form-control" placeholder="User E-mail Address">
                                        <small class="text-danger">{{ errors.email }}</small>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group mb-0">
                                        <multiselect 
                                            v-model="formData.user_type" 
                                            :options="usertypes" 
                                            :close-on-select="true" 
                                            :clear-on-select="true" 
                                            :preserve-search="true" 
                                            placeholder="Select User Type"
                                            label="user_type_name" 
                                            track-by="user_type_name" 
                                            :preselect-first="false"
                                        >
                                        </multiselect>
                                        <!-- if user_type field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.user_type }}</small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 text-center">
                                    <input type="submit" value="Create" class="btn btn-sm btn-success">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>