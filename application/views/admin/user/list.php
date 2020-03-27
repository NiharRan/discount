
 <div class="container-fluid" id="user_list">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0"><i class="fas fa-store"></i> User Lists
                        <!-- if user has permission to create user -->
                        <?php if($this->permission->has_permission('user', 'create')) {?>
                            <a href="<?php echo site_url('user/create'); ?>" class="btn btn-sm btn-primary float-right">
                                <i class="fas fa-plus"></i> New
                            </a>
                        <?php }?>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="min-height: 400px;">
                        <!-- if users is not empty -->
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#SN</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>User Type</th>
                                    <th class="text-center">Contact Number</th>
                                    <th>E-mail</th>
                                    <th class="text-center">Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user, key) in users" :key="key">
                                    <th>#{{ key + 1}}</th>
                                    <th scope="row">
                                        <div class="media align-items-center">
                                            <a href="#" class="avatar rounded-circle mr-3">
                                            <img alt="Image placeholder" :src="[ user.avatar == '' ? avatarUrl : '<?php echo base_url(); ?>uploads/user/user-'+user.id+'/'+user.avatar_thumb ]">
                                            </a>
                                            <div class="media-body">
                                            <span class="name mb-0 text-sm">{{ user.name }}</span>
                                            </div>
                                        </div>
                                    </th>
                                    <td>{{ user.username }}</td>
                                    <td>{{ user.role_name }}</td>
                                    <td>{{ user.contact_number }}</td>
                                    <td>{{ user.email }}</td>
                                    <td><span class="badge" :class="[ user.activated == 1 ? 'badge-success' : 'badge-danger']">{{ user.activated == 1 ? 'Active' : 'Inactive' }}</span></td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" v-if="hasPermission('edit', 'user')" @click="openUserEditModel(user)"><i class="fas fa-edit text-info"></i> Edit</a>
                                                <a class="dropdown-item" v-if="hasPermission('delete', 'user')" href="#" @click="remove(user.id)"><i class="fas fa-trash text-danger"></i> Remove</a>
                                                <a class="dropdown-item" 
                                                    v-if="hasPermission('edit', 'user')" 
                                                    href="#" 
                                                    @click="changeStatus(user)">
                                                        <i  class="fas" 
                                                            :class="[user.activated == 1 ? 'fa-times text-danger' : 'fa-check text-success' ]">
                                                        </i> {{ user.activated == 1 ? 'Inactive' : 'Active' }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    <!-- pagination links -->
                    <nav aria-label="..." v-html="links"></nav>
                </div>
            </div>
        </div>
    </div>
    <!-- User Edit Modal -->
    <div class="modal fade" id="userEditModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="userEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" style="flex: 1;">Update <span class="text-success">{{ formData.name }}</span>'s Info
                        <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h3>
                </div>
                <div class="lds-ripple" v-if="isloading">
                    <div></div>
                    <div></div>
                </div>
                <form @submit.prevent="update" :class="[isloading ? 'v-hidden' : '']">
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
                                                v-model="formData.role" 
                                                :options="usertypes" 
                                                :close-on-select="true" 
                                                :clear-on-select="true" 
                                                :preserve-search="true" 
                                                placeholder="Select User Type"
                                                label="role_name" 
                                                track-by="role_name" 
                                                :preselect-first="false"
                                            >
                                            </multiselect>
                                            <!-- if role field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.role }}</small>
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
                                <button type="submit" class="btn btn-success btn-sm">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>