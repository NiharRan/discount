
 <div class="container-fluid" id="role">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0"><i class="fas fa-user"></i> Role Lists
                        <!-- Button trigger role create modal -->
                        <?php if($this->permission->has_permission('role', 'create')) {?>
                            <button type="button" class="btn btn-sm btn-primary float-right" @click="openModal('#roleCreateModal')" data-toggle="modal">
                                <i class="fas fa-plus"></i> New
                            </button>
                        <?php }?>
                    </h3>
                </div>
                <div class="card-body">
                    <!-- if roles is not empty -->
                    <div v-if="roles.length > 0">
                        <!-- show all roles -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Creator</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="role in roles" :key="role.role_id">
                                    <th>{{ role.role_name }}</th>
                                    <td><span class="badge" :class="[ role.role_status == 1 ? 'badge-success' : 'badge-danger' ]">{{ role.role_status == 1 ? "Active" : "In-active" }}</span></td>
                                    <td>{{ role.user.name }}</td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" v-if="hasPermission('edit', 'role')" href="#" @click="edit(role)"><i class="fas fa-edit text-info"></i> Edit</a>
                                                <a class="dropdown-item" v-if="hasPermission('edit', 'role')" href="#" @click="changeStatus(role)"><i class="fas" :class="[role.role_status == 1 ? 'fa-times text-warning' : 'fa-check text-success' ]"></i> {{ role.role_status == 1 ? 'Inactive' : 'Active' }}</a>
                                                <a class="dropdown-item" v-if="hasPermission('delete', 'role')" href="#" @click="remove(role.role_id)"><i class="fas fa-trash text-danger"></i> Delete</a>
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
    <!-- role Create Modal -->
    <div class="modal fade" id="roleCreateModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="roleCreateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="roleCreateModalLabel">Create New Role</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="lds-ripple" v-if="isLoading">
                    <div></div>
                    <div></div>
                </div>
                <form @submit.prevent="store" :class="[isLoading ? 'v-hidden' : '']">
                    <div class="modal-body pb-0">
                        <input type="text" class="form-control" v-model="formData.role_name">
                        <!-- if role field is empty and try to submit show error message -->
                        <span class="text-danger">{{ errors.role_name }}</span>
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

    <!-- role Edit Modal -->
    <div class="modal fade" id="roleEditModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="roleEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content modal-xs">
                <div class="lds-ripple" v-if="isLoading">
                    <div></div>
                    <div></div>
                </div>
                <form @submit.prevent="update" :class="[isLoading ? 'v-hidden' : '']">
                    <div class="modal-body pb-0">
                        <input type="text" v-model="formData.role_name" class="form-control">
                        <input type="hidden" v-model="formData.role_id">
                        <!-- if role field is empty and try to submit show error message -->
                        <span class="text-danger">{{ errors.role_name }}</span>
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