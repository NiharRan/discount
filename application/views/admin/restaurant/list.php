
 <div class="container-fluid" id="restaurants_info">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
            <div class="card-header border-0">
                <div class="form-inline justify-content-between">
                    <div class="form-group">
                        <h3 class="mb-0"><i class="fas fa-store"></i> Restaurant Lists</h3>
                    </div>
                    <div class="form-group">
                        <input type="text" @keyup="fetchRestaurants" v-model="search" class="form-control" placeholder="Search">
                    </div>
                    <div class="form-group mx-sm-3">
                        <!-- if logged in user has permission to create new restaurant -->
                        <?php if($this->permission->has_permission('restaurant', 'create')) { ?>
                            <a href="<?php echo site_url('restaurants/create'); ?>" class="btn btn-sm btn-primary float-right"><i class="fas fa-plus"></i> New</a>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Restaurant</th>
                        <th scope="col">Author</th>
                        <th scope="col">Tags</th>
                        <th scope="col">Menu</th>
                        <th scope="col">Status</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                    <tbody>
                        <tr v-for="(restaurant, key) in restaurants" :key="key">
                            <th scope="row">
                                <div class="media align-items-center">
                                <a href="#" class="avatar-md mr-3">
                                    <img 
                                    alt="Image placeholder" 
                                    style="width: 100%;"
                                    :src=" (restaurant.restaurant_logo.length > 0) ? 'uploads/restaurant/restaurant-'+restaurant.restaurant_id+'/'+restaurant.restaurant_logo : 'uploads/default/restaurant/default-logo.png'">
                                </a>
                                <div class="media-body">
                                    <h2 class="mb-0 text-m">{{ restaurant.restaurant_name }}</h2>
                                </div>
                                </div>
                            </th>
                            <td>
                                {{ restaurant.creator.name }}
                            </td>
                            <td>
                                <!-- if tags is not empty -->
                                <div class="btn-group" style="flex-wrap: wrap;" v-if="restaurant.tags.length > 0">
                                    <!-- show all tags -->
                                    <span class="badge badge-success m-1" v-for="(tag, k) in restaurant.tags" :key="k">{{ tag.tag_name }}</span>
                                </div>
                            </td>
                            <td>
                                <!-- if categories is not empty -->
                                <div class="btn-group" style="flex-wrap: wrap;" v-if="restaurant.categories.length > 0">
                                    <!-- show all categories -->
                                    <span class="badge badge-primary m-1" v-for="(category, k) in restaurant.categories" :key="k">{{ category.category_name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-dot mr-4">
                                <i :class="[restaurant.restaurant_status == 1 ? 'bg-success' : 'bg-danger' ]"></i> {{ restaurant.restaurant_status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        
                                        <a 
                                            class="dropdown-item" 
                                            v-if="hasPermission('edit', 'restaurant')" 
                                            target="_blank" 
                                            :href="'<?php echo site_url('restaurants/'); ?>'+restaurant.restaurant_slug">
                                                <i class="fas fa-edit text-primary"></i> Edit
                                        </a>

                                        <a  class="dropdown-item" 
                                            href="#" @click="preview(restaurant)">
                                            <i class="fas fa-eye text-info"></i> Preview
                                        </a>
                                        <a class="dropdown-item" 
                                            v-if="hasPermission('edit', 'restaurant')" 
                                            href="#" 
                                            @click="changeStatus(restaurant)">
                                                <i  class="fas" 
                                                    :class="[restaurant.restaurant_status == 1 ? 'fa-times text-danger' : 'fa-check text-success' ]">
                                                </i> {{ restaurant.restaurant_status == 1 ? 'Inactive' : 'Active' }}
                                        </a>
                                        <a class="dropdown-item" 
                                            v-if="hasPermission('edit', 'restaurant')" 
                                            href="#" 
                                            @click="changeFeatureRestaurant(restaurant)">
                                                <i  class="fas" 
                                                    :class="[restaurant.feature_restaurant == 1 ? 'fa-times text-danger' : 'fa-check text-success' ]">
                                                </i> {{ restaurant.feature_restaurant == 1 ? 'Remove From' : 'Make' }} Feature
                                        </a>
                                        <a class="dropdown-item"
                                           href="#" v-if="hasPermission('create', 'offer')" @click="openOfferModal(restaurant)">
                                            <i class="fas fa-plus text-teal" ></i> 
                                            Create Offer
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
                <div class="card-footer py-4">
                    <!-- pagination links -->
                    <nav aria-label="..." v-html="links"></nav>
                </div>
            </div>
                <!-- Restaurant Preview Modal -->
                <div class="modal fade" id="previewModal" data-keyboard="false" data-backdrop="false" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="lds-ripple" v-if="isloading">
                                <div></div>
                                <div></div>
                            </div>
                            <div class="modal-body pt-0" style="min-height: 400px;">
                                <div class="vendor-info" v-if="!isloading">
                                    <button type="button" class="close close-abs" data-dismiss="modal" aria-label="Close">
                                        <span class="text-danger" style="font-size: 2rem;" aria-hidden="true">&times;</span>
                                    </button>
                                    <div class="vendor-picture" :style=" [ restaurant.restaurant_banner != '' ? { 'background-image': 'url(<?php echo base_url(); ?>uploads/restaurant/restaurant-'+restaurant.restaurant_id+'/'+restaurant.restaurant_banner+')' } : { 'background-image': 'url(<?php echo base_url(); ?>uploads/default/restaurant/default-banner.jpg)' } ] "></div>
                                    <div class="infos">
                                        <img class="vendor-logo" :src=" (restaurant.restaurant_logo != '' ) ? 'uploads/restaurant/restaurant-'+restaurant.restaurant_id+'/'+restaurant.restaurant_logo : 'uploads/default/restaurant/default-logo.png'" :alt="restaurant.restaurant_name">
                        
                                        <div class="info-headline">
                                            <h1 class="vendor-name">{{ restaurant.restaurant_name }}</h1>
                                            <div class="ratings-component">
                                                <span class="stars">
                                                    <i class="fas fa-star text-info"></i>
                                                </span>
                                                <span class="rating" v-if=" restaurant.rating != '' "><strong>{{ restaurant.rating }}</strong>/5</span>
                                                <span class="count" v-if=" restaurant.rating != '' ">
                                                {{ restaurant.totalRated }}
                                                </span>
                                            </div>
                                        </div>

                                        <ul class="vendor-cuisines"> 
                                            <li><i class="fas fa-tag mr-2"></i></li>              
                                            <li v-for="(tag, k) in restaurant.tags" :key="k">{{ tag.tag_name }}</li>
                                        </ul>
                                        <div class="opening-time">
                                            <span class="schedule-times">{{ customTime(restaurant.restaurant_open_at) }} - {{ customTime(restaurant.restaurant_close_at) }} </span>
                                        </div>
                                        <div id="loyalty-percentage-rip-react-root" class="loyalty-percentage-wrapper"></div>
                                        <nav class="nav-tabs">
                                            <ul class="mb-2">
                                                <li><a class="tab selected" href="#vendor-about">About</a></li>
                                            </ul>
                                        </nav>
                                    </div>
                                    <div class="panel">
                                        <hr class="target-tab selected" id="vendor-about">
                                        <div class="tab-panel">
                                            <div class="panel-wrapper">
                                                <div class="content">
                                                    <h2>Address</h2>
                                                    <p class="vendor-location">{{ restaurant.restaurant_address }}</p>
                                                </div>
                                            <div class="static-map-container">
                                                <span class="static-map-pin">
                                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <!-- Restaurant Offer Modal -->
                <div class="modal fade" id="offerModal" data-keyboard="false" data-backdrop="false" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" style="flex: 1;">Create Offer For <span class="text-success">{{ formData.restaurant_name }}</span>
                                    <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </h3>
                                
                            </div>
                            <div class="modal-body pt-0" style="min-height: 400px;">
                                <div class="lds-ripple" v-if="isloading">
                                    <div></div>
                                    <div></div>
                                </div>
                                <form @submit.prevent="createOffer" v-if="!isloading">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <input type="text" v-model="formData.offer_name" placeholder="Offer Name" class="form-control">
                                                    <!-- if offer_name field is empty and try to submit show error message -->
                                                    <small class="text-danger">{{ errors.offer_name }}</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <multiselect 
                                                        v-model="formData.template_id" 
                                                        :height="300"
                                                        :options="templates" 
                                                        :multiple="false" 
                                                        :close-on-select="true" 
                                                        :clear-on-select="true" 
                                                        :preserve-search="true" 
                                                        placeholder="Select Template"
                                                        label="template_name" 
                                                        track-by="template_name" 
                                                        :preselect-first="false"
                                                    >
                                                    </multiselect>
                                                    <!-- if template_id field is empty and try to submit show error message -->
                                                    <small class="text-danger">{{ errors.template_id }}</small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                <ckeditor :editor="editor" v-model="formData.offer_description" :config="editorConfig"></ckeditor>
                                                    <!-- if offer_description field is empty and try to submit show error message -->
                                                    <small class="text-danger">{{ errors.offer_description }}</small>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <input type="text" v-model="formData.offer_discount" placeholder="Offer Discount" class="form-control">
                                                    <!-- if offer_discount field is empty and try to submit show error message -->
                                                    <small class="text-danger">{{ errors.offer_discount }}</small>
                                                </div>
                                                <div class="col-md-4">
                                                    <datetime 
                                                        v-model="formData.offer_start" 
                                                        placeholder="Offer Start At"
                                                        auto>
                                                    </datetime>
                                                    <!-- if offer_start field is empty and try to submit show error message -->
                                                    <small class="text-danger">{{ errors.offer_start }}</small>
                                                </div>
                                                <div class="col-md-4">
                                                    <datetime 
                                                        v-model="formData.offer_end" 
                                                        placeholder="Offer End At"
                                                        auto>
                                                    </datetime>
                                                    <!-- if offer_end field is empty and try to submit show error message -->
                                                    <small class="text-danger">{{ errors.offer_end }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 text-center">
                                            <button type="submit" class="btn btn-success btn-sm">Create</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>