
 <div class="container-fluid" id="restaurants_info">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
            <div class="card-header border-0">
                <h3 class="mb-0"><i class="fas fa-store"></i> Restaurant Lists
                    <!-- if logged in user has permission to create new restaurant -->
                    <?php if($this->permission->has_permission('restaurant', 'create')) { ?>
                        <a href="<?php echo site_url('restaurants/create'); ?>" class="btn btn-sm btn-primary float-right"><i class="fas fa-plus"></i> New</a>
                    <?php }?>
                </h3>
            </div>
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Restaurant</th>
                        <th scope="col">Author</th>
                        <th scope="col">Tags</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Status</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                    <tbody>
                        <tr v-for="(restaurant, key) in restaurants" :key="key">
                            <th scope="row">
                                <div class="media align-items-center">
                                <a href="#" class="avatar-sm mr-3">
                                    <img 
                                    alt="Image placeholder" 
                                    style="width: 100%;"
                                    :src=" (restaurant.restaurant_logo.length > 0) ? 'uploads/restaurant/restaurant-'+restaurant.restaurant_id+'/'+restaurant.restaurant_logo : 'uploads/default/restaurant/default-logo.png'">
                                </a>
                                <div class="media-body">
                                    <h1 class="mb-0 text-m">{{ restaurant.restaurant_name }}</h1>
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
                                <div class="d-flex align-items-center">
                                    <span class="mr-2" v-if=" restaurant.rating != '' ">{{ restaurant.rating }}(<span>{{ restaurant.totalRated }}</span>)</span>
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
                                            v-if="hasPermission(restaurant, 'edit')" 
                                            target="_blank" 
                                            :href="'<?php echo site_url('restaurants/'); ?>'+restaurant.restaurant_slug">
                                                <i class="fas fa-edit text-primary"></i> Edit
                                        </a>

                                        <a class="dropdown-item" href="#" @click="preview(restaurant)"><i class="fas fa-eye text-info"></i> Preview</a>
                                        <a class="dropdown-item" v-if="hasPermission(restaurant, 'edit')" href="#" @click="changeStatus(restaurant)"><i class="fas" :class="[restaurant.restaurant_status == 1 ? 'fa-times text-danger' : 'fa-check text-success' ]"></i> {{ restaurant.restaurant_status == 1 ? 'Inactive' : 'Active' }}</a>
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
            <!-- Modal -->
            <div class="modal fade" id="previewModal" data-keyboard="false" data-backdrop="false" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body pt-0 vendor-info">
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
                                    <span class="line-wrapper">Closing in 56 min </span>  
                                    <span class="schedule-times">{{ customTime(restaurant.restaurant_open_at) }} - {{ customTime(restaurant.restaurant_close_at) }} </span>
                                </div>
                                <div id="loyalty-percentage-rip-react-root" class="loyalty-percentage-wrapper"></div>
                                <nav class="nav-tabs">
                                    <ul>
                                        <li><a class="tab selected" href="#vendor-about">About</a></li>
                                        <li><a class="tab" href="#vendor-reviews">Reviews</a></li>
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
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        <img class="map" data-img-url="https://maps.googleapis.com/maps/api/staticmap?center=24.896668,91.873445&amp;zoom=17&amp;scale=1&amp;size=512x512&amp;client=gme-deliveryheroholding&amp;signature=na9cQp0rFdrP4PYC5dLYcNvedFw=" src="https://maps.googleapis.com/maps/api/staticmap?center=24.896668,91.873445&amp;zoom=17&amp;scale=1&amp;size=512x512&amp;client=gme-deliveryheroholding&amp;signature=na9cQp0rFdrP4PYC5dLYcNvedFw=">
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>