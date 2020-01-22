
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
                                <a href="#" class="avatar-md mr-3">
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
                                <div class="btn-group" v-if="restaurant.tags.length > 0">
                                    <!-- show all tags -->
                                    <span class="badge badge-success" v-for="(tag, k) in restaurant.tags" :key="k">{{ tag.tag_name }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="mr-2" v-if="restaurant.rating != '' ">{{ restaurant.rating.rat }}(<span>{{ restaurant.rating.total }}</span>)</span>
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
                                        <a class="dropdown-item" href="#"><i class="fas fa-edit text-primary"></i> Edit</a>
                                        <a class="dropdown-item" href="#"><i class="fas fa-eye text-info"></i> Preview</a>
                                        <a class="dropdown-item" href="#"><i class="fas" :class="[restaurant.restaurant_status == 1 ? 'fa-times text-danger' : 'fa-check text-success' ]"></i> {{ restaurant.restaurant_status == 1 ? 'Inactive' : 'Active' }}</a>
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
        </div>
    </div>
</div>