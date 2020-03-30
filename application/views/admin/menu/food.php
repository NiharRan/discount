
 <div class="container-fluid" id="food_list">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0"><i class="fas fa-store"></i> Food Lists
                        <!-- if food has permission to create food -->
                        <?php if($this->permission->has_permission('menu-food', 'create')) {?>
                            <button type="button" class="btn btn-sm btn-primary float-right" @click="openModal('#foodCreateModal')" data-toggle="modal">
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
                                    <th>Category</th>
                                    <th>Lowest Price</th>
                                    <th>Tags</th>
                                    <th class="text-center">Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(food, key) in foods" :key="key">
                                    <th>#{{ key + 1}}</th>
                                    <th scope="row">
                                        {{ food.food_name }}
                                    </th>
                                    <td>{{ food.category.category_name }}</td>
                                    <td>{{ food.food_lowest_price }}$</td>
                                    <td>
                                        <span class="badge badge-success mx-1" v-for="food_tag in food.food_tags" :key="food_tag.menu_tag_id">{{ food_tag.menu_tag_name }}</span>
                                    </td>
                                    <td><span class="badge" :class="[ food.food_status == 1 ? 'badge-success' : 'badge-danger']">{{ food.food_status == 1 ? 'Active' : 'Inactive' }}</span></td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" v-if="hasPermission('menu-food', 'edit')" @click="openFoodEditModel(food)"><i class="fas fa-edit text-info"></i> Edit</a>
                                                <a class="dropdown-item" v-if="hasPermission('menu-food', 'delete')" href="#" @click="remove(food.id)"><i class="fas fa-trash text-danger"></i> Remove</a>
                                                <a class="dropdown-item" 
                                                    v-if="hasPermission('menu-food', 'edit')" 
                                                    href="#" 
                                                    @click="changeStatus(food)">
                                                        <i  class="fas" 
                                                            :class="[food.food_status == 1 ? 'fa-times text-danger' : 'fa-check text-success' ]">
                                                        </i> {{ food.food_status == 1 ? 'Inactive' : 'Active' }}
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
    <div class="modal fade" id="foodCreateModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="foodEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" style="flex: 1;">Create new food
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
                            <div class="col-md-8 col-sm-12">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Food</label>
                                            <input type="text" v-model="fData.food_name" class="form-control form-control-alternative" placeholder="Food Name">
                                            <!-- if food_name field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_name }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Lowest Price</label>
                                            <input type="text" v-model="fData.food_lowest_price" class="form-control form-control-alternative" placeholder="Food Lowest Price">
                                            <!-- if food_lowest_price field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_lowest_price }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Restaurant</label>
                                            <multiselect 
                                                v-model="fData.restaurant" 
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
                                                @search-change="fetchRestaurants"
                                                @input="fetchCategories">
                                            </multiselect>
                                            <!-- if restaurant_id field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.restaurant_id }}</small>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label">Category</label>
                                            <multiselect 
                                                v-model="fData.category" 
                                                :options="categories" 
                                                :close-on-select="true" 
                                                :clear-on-select="true" 
                                                :preserve-search="true" 
                                                placeholder="Select Category"
                                                label="category_name" 
                                                track-by="category_name" 
                                                :preselect-first="false"
                                            >
                                            </multiselect>
                                            <!-- if category_id field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.category_id }}</small>
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
                                <div class="banner-logo-upload-box width-full">
                                    <img :src="modalBannerUrl" alt="">
                                    <label class="btn-pill">
                                        <i class="fas fa-camera"></i>
                                        <input @change="selectModalBanner" type="file" class="hidden"/>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 mb-4">
                                <label>Tags</label>
                                <multiselect 
                                    v-model="fData.food_tags" 
                                    label="menu_tag_name" 
                                    track-by="menu_tag_name" 
                                    placeholder="Type to search" 
                                    :options="menu_tags" 
                                    :multiple="true" 
                                    :searchable="true" 
                                    :loading="isFetching" 
                                    :options-limit="300" 
                                    :limit="3" 
                                    :max-height="600" 
                                    :hide-selected="true" 
                                    @search-change="fetchTags">
                                    <template slot="food_tags" slot-scope="{ option, remove }"><span class="custom__tag"><span>{{ option.menu_tag_name }}</span><span class="custom__remove" @click="remove(option)">❌</span></span></template>
                                    <template slot="clear" slot-scope="props">
                                    <div class="multiselect__clear" v-if="fData.food_tags.length" @mousedown.prevent.stop="clearAll(props.search)"></div>
                                    </template><span slot="noResult">Oops! No elements found.</span>
                                </multiselect>
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
    <div class="modal fade" id="foodEditModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="foodEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" style="flex: 1;">Update <span class="text-success">{{ fData.name }}</span>'s Info
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
                            <div class="col-md-8 col-sm-12">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Food</label>
                                            <input type="text" v-model="fData.food_name" class="form-control form-control-alternative" placeholder="Food Name">
                                            <!-- if food_name field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_name }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Lowest Price</label>
                                            <input type="text" v-model="fData.food_lowest_price" class="form-control form-control-alternative" placeholder="Food Lowest Price">
                                            <!-- if food_lowest_price field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.food_lowest_price }}</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="form-control-label">Restaurant</label>
                                            <multiselect 
                                                v-model="fData.restaurant" 
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
                                                @search-change="fetchRestaurants"
                                                @input="fetchCategories">
                                            </multiselect>
                                            <!-- if restaurant_id field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.restaurant_id }}</small>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label">Category</label>
                                            <multiselect 
                                                v-model="fData.category" 
                                                :options="categories" 
                                                :close-on-select="true" 
                                                :clear-on-select="true" 
                                                :preserve-search="true" 
                                                placeholder="Select Category"
                                                label="category_name" 
                                                track-by="category_name" 
                                                :preselect-first="false"
                                            >
                                            </multiselect>
                                            <!-- if category_id field is empty and try to submit show error message -->
                                            <small class="text-danger">{{ errors.category_id }}</small>
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
                                <div class="banner-logo-upload-box width-full">
                                    <img :src="modalBannerUrl" alt="">
                                    <label class="btn-pill">
                                        <i class="fas fa-camera"></i>
                                        <input @change="selectModalBanner" type="file" class="hidden"/>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 mb-4">
                                <label>Tags</label>
                                <multiselect 
                                    v-model="fData.food_tags" 
                                    label="menu_tag_name" 
                                    track-by="menu_tag_name" 
                                    placeholder="Type to search" 
                                    :options="menu_tags" 
                                    :multiple="true" 
                                    :searchable="true" 
                                    :loading="isFetching" 
                                    :max-height="600" 
                                    :hide-selected="true" 
                                    @search-change="fetchTags">
                                    <template slot="food_tag" slot-scope="{ option, remove }"><span class="custom__tag"><span>{{ option.menu_tag_name }}</span><span class="custom__remove" @click="remove(option)">❌</span></span></template>
                                    <template slot="clear" slot-scope="props">
                                    <div class="multiselect__clear" v-if="fData.food_tags.length" @mousedown.prevent.stop="clearAll(props.search)"></div>
                                    </template><span slot="noResult">Oops! No elements found.</span>
                                </multiselect>
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