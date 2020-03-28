
 <div class="container-fluid" id="food_aditional_list">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0"><i class="fas fa-store"></i> Food Aditional Lists
                        <!-- if food has permission to create food -->
                        <?php if($this->permission->has_permission('menu-food-aditional', 'create')) {?>
                            <button type="button" class="btn btn-sm btn-primary float-right" @click="openModal('#foodAditionalCreateModal')" data-toggle="modal">
                                <i class="fas fa-plus"></i> New
                            </button>
                        <?php }?>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="min-height: 400px;">
                        <!-- if foods is not empty -->
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#SN</th>
                                    <th>Food Aditional</th>
                                    <th>Weight</th>
                                    <th>Price</th>
                                    <th class="text-center">Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(food_aditional, key) in food_aditionals" :key="key">
                                    <th>#{{ key + 1}}</th>
                                    <td>{{ food_aditional.food_aditional_name }}</td>
                                    <td>{{ food_aditional.food_aditional_weight }}g</td>
                                    <td>{{ food_aditional.food_aditional_price }}$</td>
                                    <td><span class="badge" :class="[ food_aditional.food_aditional_status == 1 ? 'badge-success' : 'badge-danger']">{{ food_aditional.food_aditional_status == 1 ? 'Active' : 'Inactive' }}</span></td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" v-if="hasPermission('menu-food-aditional', 'edit')" @click="openFoodAditonalEditModel(food_aditional)"><i class="fas fa-edit text-info"></i> Edit</a>
                                                <a class="dropdown-item" v-if="hasPermission('menu-food-aditional', 'delete')" href="#" @click="remove(food_aditional.food_aditional_id)"><i class="fas fa-trash text-danger"></i> Remove</a>
                                                <a class="dropdown-item" 
                                                    v-if="hasPermission('menu-food-aditional', 'edit')" 
                                                    href="#" 
                                                    @click="changeStatus(food)">
                                                        <i  class="fas" 
                                                            :class="[food_aditional.food_aditional_status == 1 ? 'fa-times text-danger' : 'fa-check text-success' ]">
                                                        </i> {{ food_aditional.food_aditional_status == 1 ? 'Inactive' : 'Active' }}
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
    <!-- food Create Modal -->
    <div class="modal fade" id="foodAditionalCreateModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="foodAditionalEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" style="flex: 1;">Create new food aditional
                        <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h3>
                </div>
                <div class="lds-ripple" v-if="isLoading">
                    <div></div>
                    <div></div>
                </div>
                <form @submit.prevent="store" :class="[isLoading ? 'v-hidden' : '']">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Food Aditional</label>
                                            <input type="text" v-model="formData.food_aditional_name" class="form-control form-control-alternative" placeholder="Food Aditional Name">
                                            <!-- if food_aditional_name field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_aditional_name }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Weight</label>
                                            <input type="text" v-model="formData.food_aditional_weight" class="form-control form-control-alternative" placeholder="Food Aditional weight">
                                            <!-- if food_aditional_weight field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_aditional_weight }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Price</label>
                                            <input type="text" v-model="formData.food_aditional_price" class="form-control form-control-alternative" placeholder="Food Aditional Price">
                                            <!-- if food_aditional_price field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_aditional_price }}</small>
                                        </div>
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

    <!-- food Edit Modal -->
    <div class="modal fade" id="foodAditionalEditModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="foodAditionalEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" style="flex: 1;">Update <span class="text-success">{{ formData.food_aditional_name }}</span>'s Info
                        <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h3>
                </div>
                <div class="lds-ripple" v-if="isLoading">
                    <div></div>
                    <div></div>
                </div>
                <form @submit.prevent="update" :class="[isLoading ? 'v-hidden' : '']">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Food Aditional</label>
                                            <input type="text" v-model="formData.food_aditional_name" class="form-control form-control-alternative" placeholder="Food Aditional Name">
                                            <!-- if food_aditional_name field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_aditional_name }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Weight</label>
                                            <input type="text" v-model="formData.food_aditional_weight" class="form-control form-control-alternative" placeholder="Food weight">
                                            <!-- if food_aditional_weight field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_aditional_weight }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Price</label>
                                            <input type="text" v-model="formData.food_aditional_price" class="form-control form-control-alternative" placeholder="Food Aditional Price">
                                            <!-- if food_aditional_price field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_aditional_price }}</small>
                                        </div>
                                    </div>
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