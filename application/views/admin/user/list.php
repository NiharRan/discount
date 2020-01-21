
 <div class="container-fluid" id="user_list">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0"><i class="fas fa-store"></i> User Lists
                        <!-- if user has permission to create user -->
                        <?php if($this->permission->has_permission('user', 'create')) {?>
                            <a href="<?php echo site_url('users/create'); ?>" class="btn btn-sm btn-primary float-right">
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
                                    <th>{{ user.name }}</th>
                                    <td>{{ user.username }}</td>
                                    <td>{{ user.user_type_name }}</td>
                                    <td>{{ user.contact_number }}</td>
                                    <td>{{ user.email }}</td>
                                    <td><span class="badge" :class="[ user.user_status == 1 ? 'badge-success' : 'badge-danger']">{{ user.user_status == 1 ? 'Active' : 'Inactive' }}</span></td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" href="#">Edit</a>
                                                <a class="dropdown-item" href="#">Remove</a>
                                                <a class="dropdown-item" href="#">Change Status</a>
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
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content modal-xs">
                <div class="lds-ripple" v-if="isloading">
                    <div></div>
                    <div></div>
                </div>
                <form @submit.prevent="update" :class="[isloading ? 'v-hidden' : '']">
                    <div class="modal-body pb-0">
                        <input type="text" v-model="formData.user_name" class="form-control">
                        <input type="hidden" v-model="formData.user_id">
                        <!-- if user field is empty and try to submit show error message -->
                        <span class="text-danger">{{ errors.user_name }}</span>
                    </div>
                    <div class="modal-footer">
                        <!-- close to modal by clicking this button -->
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                        <!-- this if for submit form -->
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>