
<div class="container-fluid" id="create_user">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0"><i class="fas fa-plus"></i> Create New User
                        <a href="<?php echo site_url('users'); ?>" class="btn btn-sm btn-primary float-right"><i class="fas fa-arrow-left"></i> Back</a>
                    </h3>
                </div>
                <form @submit.prevent="store">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 col-sm-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Name</label>
                                            <input type="text" v-model="formData.name" class="form-control form-control-alternative" placeholder="User Name">
                                            <!-- if name field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.name }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Contact No.</label>
                                            <input type="text" v-model="formData.contact_number" class="form-control form-control-alternative" placeholder="User Contact Number">
                                            <!-- if contact_number field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.contact_number }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">E-mail</label>
                                            <input type="text" v-model="formData.email" class="form-control form-control-alternative" placeholder="User E-mail Address">
                                            <small class="text-danger">{{ errors.email }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">User Type</label>
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


                                    <div class="col-lg-8 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Address</label>
                                            <input type="text" v-model="formData.address" class="form-control form-control-alternative" placeholder="Address">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-control-label">D.O.B</label>
                                            <datetime 
                                                type="date"
                                                v-model="formData.dob" 
                                                placeholder="Date of Birth"
                                                auto>
                                            </datetime>
                                        </div>
                                    </div>


                                    <div class="col-lg-4 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-control-label">City</label>
                                            <input type="text" v-model="formData.city" class="form-control form-control-alternative" placeholder="City">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Country</label>
                                            <input type="text" v-model="formData.country" class="form-control form-control-alternative" placeholder="Country">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Postal code</label>
                                            <input type="number" v-model="formData.postal_code" class="form-control form-control-alternative" placeholder="Postal code">
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Username</label>
                                            <input type="text" v-model="formData.username" class="form-control form-control-alternative" placeholder="Username">
                                            <!-- if username field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.username }}</small>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Password</label>
                                            <input type="password" v-model="formData.password" class="form-control form-control-alternative" placeholder="Password">
                                            <!-- if password field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.password }}</small>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Confirm Password</label>
                                            <input type="password" v-model="formData.confirm_password" @keyup="checkIsPasswordMatch" class="form-control form-control-alternative" placeholder="Confirm Password">
                                            <!-- if confirm_password field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.confirm_password }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="banner-logo-upload-box width-full mb-4">
                                    <img :src="bannerUrl" alt="">
                                    <label class="btn-pill">
                                        <i class="fas fa-camera"></i>
                                        <input @change="selectBanner" type="file" class="hidden"/>
                                    </label>
                                </div>
                                <div class="banner-logo-upload-box width-half" style="height: 165px;">
                                    <img :src="avatarUrl" alt="">
                                    <label class="btn-pill">
                                        <i class="fas fa-camera"></i>
                                        <input @change="selectAvatar" type="file" class="hidden"/>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 text-center">
                                <button type="submit" class="btn btn-success btn-sm" :class="[ isPasswordMatched ? '' : 'disabled'] ">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
