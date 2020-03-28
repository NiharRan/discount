
 <div class="container-fluid" id="food_price_list">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0"><i class="fas fa-store"></i> Food Price Lists
                        <!-- if food has permission to create food -->
                        <?php if($this->permission->has_permission('menu-food-price', 'create')) {?>
                            <button type="button" class="btn btn-sm btn-primary float-right" @click="openModal('#foodPriceCreateModal')" data-toggle="modal">
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
                                    <th>Food</th>
                                    <th>Size</th>
                                    <th>Weight</th>
                                    <th>Price</th>
                                    <th class="text-center">Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(food_price, key) in food_prices" :key="key">
                                    <th>#{{ key + 1}}</th>
                                    <th scope="row">
                                        {{ food_price.food.food_name }}
                                    </th>
                                    <td>{{ food_price.food_size.food_size_name }}</td>
                                    <td>{{ food_price.food_weight }}g</td>
                                    <td>{{ food_price.food_price }}$</td>
                                    <td><span class="badge" :class="[ food_price.food_price_status == 1 ? 'badge-success' : 'badge-danger']">{{ food_price.food_price_status == 1 ? 'Active' : 'Inactive' }}</span></td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" v-if="hasPermission('menu-food-price', 'edit')" @click="openFoodEditModel(food_price)"><i class="fas fa-edit text-info"></i> Edit</a>
                                                <a class="dropdown-item" v-if="hasPermission('menu-food-price', 'delete')" href="#" @click="remove(food_price.food_price_id)"><i class="fas fa-trash text-danger"></i> Remove</a>
                                                <a class="dropdown-item" 
                                                    v-if="hasPermission('menu-food-price', 'edit')" 
                                                    href="#" 
                                                    @click="changeStatus(food)">
                                                        <i  class="fas" 
                                                            :class="[food_price.food_price_status == 1 ? 'fa-times text-danger' : 'fa-check text-success' ]">
                                                        </i> {{ food_price.food_price_status == 1 ? 'Inactive' : 'Active' }}
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
    <div class="modal fade" id="foodPriceCreateModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="foodPriceEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" style="flex: 1;">Create new food price
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
                                            <label class="form-control-label">Food</label>
                                            <multiselect 
                                                v-model="formData.food" 
                                                :options="foods" 
                                                :close-on-select="true" 
                                                :clear-on-select="true" 
                                                :preserve-search="true" 
                                                placeholder="Select food"
                                                label="food_name" 
                                                track-by="food_name" 
                                                :preselect-first="false"
                                            >
                                            </multiselect>
                                            <!-- if food_name field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_id }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Size</label>
                                            <multiselect 
                                                v-model="formData.food_size" 
                                                :options="food_sizes" 
                                                :close-on-select="true" 
                                                :clear-on-select="true" 
                                                :preserve-search="true" 
                                                placeholder="Select food size"
                                                label="food_size_name" 
                                                track-by="food_size_name" 
                                                :preselect-first="false"
                                            >
                                            </multiselect>
                                            <!-- if food_size_id field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_size_id }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Weight</label>
                                            <input type="text" v-model="formData.food_weight" class="form-control form-control-alternative" placeholder="Food weight">
                                            <!-- if food_weight field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_weight }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Price</label>
                                            <input type="text" v-model="formData.food_price" class="form-control form-control-alternative" placeholder="Food Price">
                                            <!-- if food_price field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_price }}</small>
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
    <div class="modal fade" id="foodPriceEditModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="foodPriceEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" style="flex: 1;">Update <span class="text-success">{{ formData.food.food_name }}</span>'s Info
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
                                            <label class="form-control-label">Food</label>
                                            <multiselect 
                                                v-model="formData.food" 
                                                :options="foods" 
                                                :close-on-select="true" 
                                                :clear-on-select="true" 
                                                :preserve-search="true" 
                                                placeholder="Select food"
                                                label="food_name" 
                                                track-by="food_name" 
                                                :preselect-first="false"
                                            >
                                            </multiselect>
                                            <!-- if food_name field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_id }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Size</label>
                                            <multiselect 
                                                v-model="formData.food_size" 
                                                :options="food_sizes" 
                                                :close-on-select="true" 
                                                :clear-on-select="true" 
                                                :preserve-search="true" 
                                                placeholder="Select food size"
                                                label="food_size_name" 
                                                track-by="food_size_name" 
                                                :preselect-first="false"
                                            >
                                            </multiselect>
                                            <!-- if food_size_id field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_size_id }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Weight</label>
                                            <input type="text" v-model="formData.food_weight" class="form-control form-control-alternative" placeholder="Food weight">
                                            <!-- if food_weight field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_weight }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Price</label>
                                            <input type="text" v-model="formData.food_price" class="form-control form-control-alternative" placeholder="Food Price">
                                            <!-- if food_price field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_price }}</small>
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