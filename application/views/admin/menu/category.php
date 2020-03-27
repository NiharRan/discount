
 <div class="container-fluid" id="category">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="form-inline justify-content-between">
                        <div class="form-group">
                            <h3 class="mb-0"><i class="fas fa-category"></i> Category Lists</h3>
                        </div>
                        <div class="form-group">
                            <input type="text" @keyup="fetchCategories" v-model="search" class="form-control" placeholder="Search">
                        </div>
                        <div class="form-group mx-sm-3">
                            <!-- Button trigger category create modal -->
                            <?php if($this->permission->has_permission('menu-category', 'create')) {?>
                                <button type="button" class="btn btn-sm btn-primary float-right" @click="openModal('#categoryCreateModal')" data-toggle="modal">
                                    <i class="fas fa-plus"></i> New
                                </button>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- if categories is not empty -->
                    <div class="row" v-if="categories.length > 0">
                        <!-- show all categories -->
                        <div class="flex-container">
                            <div class="flex-item" v-for="category in categories" :key="category.category_id">
                                <p class="custom-btn" :class="[ category.category_status == 1 ? 'btn-outline-success' : 'btn-outline-warning' ]">
                                    {{ category.category_name }}
                                    <span class="icon-list">
                                        <i class="fas fa-edit text-info" v-if="hasPermission('menu-category', 'edit')" @click="edit(category)"></i>
                                        <i class="fas fa-trash text-danger" v-if="hasPermission('menu-category', 'delete')" @click="remove(category.category_id)"></i>
                                        <i class="fas fa-eye text-warning" v-if="hasPermission('menu-category', 'edit')" @click="changeStatus(category)"></i>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer py-4">
                    <!-- pagination links -->
                    <nav aria-label="..." v-html="links"></nav>
                </div>
            </div>
        </div>
    </div>
    <!-- category Create Modal -->
    <div class="modal fade" id="categoryCreateModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="categoryCreateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="categoryCreateModalLabel">Create New Category</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="lds-ripple" v-if="isLoading">
                    <div></div>
                    <div></div>
                </div>
                <form @submit.prevent="store" :class="[isLoading ? 'v-hidden' : '']">
                    <div class="modal-body mb-0">
                        <input type="text" v-model="formData.category_name" class="form-control" placeholder="Category Name">
                        <!-- if category field is empty and try to submit show error message -->
                        <span class="text-danger">{{ errors.category_name }}</span>
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

    <!-- category Edit Modal -->
    <div class="modal fade" id="categoryEditModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="categoryEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content modal-xs">
                <div class="lds-ripple" v-if="isLoading">
                    <div></div>
                    <div></div>
                </div>
                <form @submit.prevent="update" :class="[isLoading ? 'v-hidden' : '']">
                    <div class="modal-body pb-0">
                        <input type="text" v-model="formData.category_name" class="form-control">
                        <!-- if category field is empty and try to submit show error message -->
                        <span class="text-danger">{{ errors.category_name }}</span>
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