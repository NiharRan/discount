
 <div class="container-fluid" id="permission">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="form-inline justify-content-between">
                        <div class="form-group">
                            <h3 class="mb-0"><i class="fas fa-key"></i> Permission Lists</h3>
                        </div>
                        <div class="form-group">
                            <input type="text" @keyup="fetchPermissions" v-model="search" class="form-control" placeholder="Search">
                        </div>
                        <div class="form-group mx-sm-3">
                            <!-- Button trigger permission create modal -->
                            <?php if($this->permission->has_permission('permission', 'create')) {?>
                                <button type="button" class="btn btn-sm btn-primary float-right" @click="openModal('#permissionCreateModal')" data-toggle="modal">
                                    <i class="fas fa-plus"></i> New
                                </button>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- if permissions is not empty -->
                    <div class="table-responsive" v-if="permissions.length > 0">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Module</th>
                                    <th>Actions</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(permission, key) in permissions" :key="key">
                                    <th>#{{ key + 1 }}</th>
                                    <th>{{ permission.model_name }}</th>
                                    <td>{{ permission.action }}</td>
                                    <td>{{ permission.role.role_name }}</td>
                                    <td>
                                        <span 
                                            class="badge" 
                                            :class="[ permission.permission_status == 1 ? 'badge-success' : 'badge-danger' ]">
                                            {{ permission.permission_status == 1 ? "Active" : "In-active" }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" v-if="hasPermission('edit', 'permission')" href="#" @click="edit(permission)"><i class="fas fa-edit text-info"></i> Edit</a>
                                                <a class="dropdown-item" v-if="hasPermission('edit', 'permission')" href="#" @click="changeStatus(permission)"><i class="fas" :class="[permission.permission_status == 1 ? 'fa-times text-warning' : 'fa-check text-success' ]"></i> {{ permission.permission_status == 1 ? 'Inactive' : 'Active' }}</a>
                                                <a class="dropdown-item" v-if="hasPermission('delete', 'permission')" href="#" @click="remove(permission.permission_id)"><i class="fas fa-trash text-danger"></i> Delete</a>
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
    <!-- permsssion Create Modal -->
    <div class="modal fade" id="permissionCreateModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Create New Permissions</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="lds-ripple" v-if="isLoading">
                    <div></div>
                    <div></div>
                </div>
                <form @submit.prevent="store" :class="[isLoading ? 'v-hidden' : '']">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Module Name <span class="text-danger">*</span></label>
                            <input type="text" v-model="formData.model_name" class="form-control" placeholder="Model Name">
                            <!-- if permission field is empty and try to submit show error message -->
                            <span class="text-danger">{{ errors.model_name }}</span>
                        </div>
                        <div class="form-group mb-0">
                            <label>Role <span class="text-danger">*</span></label>
                            <multiselect 
                                v-model="formData.role_id" 
                                :height="300"
                                :options="roles" 
                                :preserve-search="true" 
                                placeholder="Search a Role"
                                label="role_name" 
                                track-by="role_name" 
                            >
                            </multiselect>
                            <!-- if permission field is empty and try to submit show error message -->
                            <span class="text-danger">{{ errors.role_id }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- close to modal by clicking this button -->
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <!-- this if for submit form -->
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- permission Edit Modal -->
    <div class="modal fade" id="permissionEditModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="permissionEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="lds-ripple" v-if="isLoading">
                    <div></div>
                    <div></div>
                </div>
                <form @submit.prevent="update" :class="[isLoading ? 'v-hidden' : '']">
                <div class="modal-body">
                        <div class="form-group">
                            <label>Model Name</label>
                            <input type="text" v-model="formData.model_name" class="form-control" placeholder="Model Name">
                            <!-- if permission field is empty and try to submit show error message -->
                            <span class="text-danger">{{ errors.permission_name }}</span>
                        </div>
                        <div class="form-group">
                            <label>Action <span class="text-danger">*</span></label>
                            <input type="text" v-model="formData.action" class="form-control" placeholder="Action">
                            <!-- if permission field is empty and try to submit show error message -->
                            <span class="text-danger">{{ errors.action }}</span>
                        </div>
                        <div class="form-group mb-0">
                            <label>Role <span class="text-danger">*</span></label>
                            <multiselect 
                                v-model="formData.role_id" 
                                :height="300"
                                :options="roles" 
                                :preserve-search="true" 
                                placeholder="Search a Role"
                                label="role_name" 
                                track-by="role_name" 
                            >
                            </multiselect>
                            <!-- if permission field is empty and try to submit show error message -->
                            <span class="text-danger">{{ errors.permission_name }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- close to modal by clicking this button -->
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <!-- this if for submit form -->
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>