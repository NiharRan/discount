
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
                    <div class="table-responsive" style="min-height: 400px;">
                        <!-- if categories is not empty -->
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#SN</th>
                                    <th>Category</th>
                                    <th>Restaurant</th>
                                    <th class="text-center">Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(category, key) in categories" :key="key">
                                    <th>#{{ key + 1}}</th>
                                    <th scope="row">
                                        {{ category.category_name }}
                                    </th>
                                    <td>{{ category.restaurant.restaurant_name }}</td>
                                    <td><span class="badge" :class="[ category.category_status == 1 ? 'badge-success' : 'badge-danger']">{{ category.category_status == 1 ? 'Active' : 'Inactive' }}</span></td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" v-if="hasPermission('menu-category', 'edit')" @click="openCategoryEditModel(category)"><i class="fas fa-edit text-info"></i> Edit</a>
                                                <a class="dropdown-item" v-if="hasPermission('menu-category', 'delete')" href="#" @click="remove(category.id)"><i class="fas fa-trash text-danger"></i> Remove</a>
                                                <a class="dropdown-item" 
                                                    v-if="hasPermission('menu-category', 'edit')" 
                                                    href="#" 
                                                    @click="changeStatus(category)">
                                                        <i  class="fas" 
                                                            :class="[category.category_status == 1 ? 'fa-times text-danger' : 'fa-check text-success' ]">
                                                        </i> {{ category.category_status == 1 ? 'Inactive' : 'Active' }}
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
                        <div class="form-group">
                            <input type="text" v-model="formData.category_name" class="form-control" placeholder="Category Name">
                            <!-- if category field is empty and try to submit show error message -->
                            <span class="text-danger">{{ errors.category_name }}</span>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Restaurant</label>
                            <multiselect 
                                v-model="formData.restaurant" 
                                label="restaurant_name" 
                                track-by="restaurant_name" 
                                placeholder="Type to search" 
                                :options="restaurants" 
                                :searchable="true" 
                                :loading="isFetching" 
                                :options-limit="300" 
                                :limit="3" 
                                :max-height="600" 
                                :hide-selected="true" 
                                @search-change="fetchRestaurants">
                            </multiselect>
                            <!-- if restaurant_id field is empty and try to submit show error message -->
                            <small class="text-danger">{{ errors.restaurant_id }}</small>
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

    <!-- category Edit Modal -->
    <div class="modal fade" id="categoryEditModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="categoryEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="lds-ripple" v-if="isLoading">
                    <div></div>
                    <div></div>
                </div>
                <div class="modal-header">
                    <h2 class="modal-title">Update Category info</h2>
                </div>
                <form @submit.prevent="update" :class="[isLoading ? 'v-hidden' : '']">
                    <div class="modal-body pb-0">
                        <div class="form-group">
                            <input type="text" v-model="formData.category_name" class="form-control" placeholder="Category Name">
                            <!-- if category field is empty and try to submit show error message -->
                            <span class="text-danger">{{ errors.category_name }}</span>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Restaurant</label>
                            <multiselect 
                                v-model="formData.restaurant" 
                                label="restaurant_name" 
                                track-by="restaurant_name" 
                                placeholder="Type to search" 
                                :options="restaurants" 
                                :searchable="true" 
                                :loading="isFetching" 
                                :options-limit="300" 
                                :limit="3" 
                                :max-height="600" 
                                :hide-selected="true" 
                                @search-change="fetchRestaurants">
                            </multiselect>
                            <!-- if restaurant_id field is empty and try to submit show error message -->
                            <small class="text-danger">{{ errors.restaurant_id }}</small>
                        </div>
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