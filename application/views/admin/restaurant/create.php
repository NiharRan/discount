
 <div class="container-fluid" id="resturant_entry_form">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0"><i class="fas fa-plus"></i> Create New Restaurant
                        <a href="<?php echo site_url('restaurants'); ?>" class="btn btn-sm btn-primary float-right"><i class="fas fa-arrow-left"></i> Back</a>
                    </h3>
                </div>
                <form @submit.prevent="store">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 col-sm-12">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <input type="text" v-model="formData.restaurant_moto" placeholder="Restaurant Moto" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" v-model="formData.restaurant_name" placeholder="Restaurant Name" class="form-control">
                                        <!-- if restaurant_name field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.restaurant_name }}</small>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <input type="text" v-model="formData.restaurant_contact_number" placeholder="Restaurant Contact Number" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" v-model="formData.restaurant_email" placeholder="Restaurant E-mail" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <textarea v-model="formData.restaurant_address" placeholder="Restaurant Address" style="height: 77px !important;" class="form-control"></textarea>
                                        <!-- if restaurant_address field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.restaurant_address }}</small>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <datetime 
                                            type="time"
                                            v-model="formData.restaurant_open_at" 
                                            placeholder="Restaurant Open At"
                                            auto>
                                        </datetime>
                                        <!-- <input type="text" id="restaurant_open_at" v-model="formData.restaurant_open_at" placeholder="Restaurant Open At" class="form-control"> -->
                                        <!-- if restaurant_open_at field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.restaurant_open_at }}</small>
                                    </div>
                                    <div class="col-md-4">
                                        <datetime 
                                            type="time"
                                            v-model="formData.restaurant_close_at" 
                                            placeholder="Restaurant Close At"
                                            auto>
                                        </datetime>
                                        <!-- <input type="text" id="restaurant_close_at" v-model="formData.restaurant_close_at" placeholder="Restaurant Close At" class="form-control"> -->
                                        <!-- if restaurant_close_at field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.restaurant_close_at }}</small>
                                    </div>
                                    <div class="col-md-4">
                                        <datetime 
                                            v-model="formData.restaurant_establish_date" 
                                            placeholder="Restaurant Establish Date"
                                            format="dd-MM-yyyy"
                                            auto>
                                        </datetime>
                                        <!-- <input type="text" id="restaurant_establish_date" v-model="formData.restaurant_establish_date" placeholder="Restaurant Establish Date" class="form-control"> -->
                                        <!-- if restaurant_establish_date field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.restaurant_establish_date }}</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                    <multiselect 
                                        v-model="formData.tags" 
                                        :height="300"
                                        :options="tags" 
                                        :multiple="true" 
                                        :taggable="true"
                                        :close-on-select="true" 
                                        :clear-on-select="true" 
                                        :preserve-search="true" 
                                        placeholder="Search or add a tag"
                                        label="tag_name" 
                                        track-by="tag_name" 
                                        :preselect-first="false"
                                    >
                                    </multiselect>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="banner-logo-upload-box width-full mb-4" :style="{ backgroundImage: bannerUrl}">
                                    <label class="btn-pill">
                                        <i class="fas fa-camera"></i>
                                        <input @change="selectBanner" type="file" class="hidden"/>
                                    </label>
                                </div>
                                <div class="banner-logo-upload-box width-half" :style="{ backgroundImage: logoUrl}">
                                    <label class="btn-pill">
                                        <i class="fas fa-camera"></i>
                                        <input @change="selectLogo" type="file" class="hidden"/>
                                    </label>
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
