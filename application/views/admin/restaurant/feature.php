
 <div class="container-fluid" id="feature_restaurants_info">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="form-inline justify-content-between">
                        <div class="form-group">
                            <h3 class="mb-0"><i class="fas fa-store"></i>Feature Restaurant Lists</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div v-for="index of 6" class="col-md-4 col-sm-6 col-xs-12">
                            <div class="position-relative py-3" @click="openFeatureModal" title="Select a restaurant to assign as feature restaurant">
                                <img :src="defaultImageUrl" alt="" class="feature-image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <!-- Restaurant Offer Modal -->
                <div class="modal fade" id="featureModal" data-keyboard="false" data-backdrop="false" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" style="flex: 1;"> Set Feature Restaurant
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
                                <div class="row" v-else>
                                    <div class="col-md-12 col-sm-12 mb-4">
                                        <input 
                                            type="text" 
                                            v-model="search" 
                                            @keyup="searchRestaurants"
                                            class="form-control form-control-alternative" 
                                            placeholder="Search Restaurants">
                                    </div>
                                    <div 
                                        class="col-md-4 col-sm-6 col-xs-12" 
                                        style="height: 200px;"
                                        v-for="(restaurant, key) in restaurants" 
                                        :key="key">
                                        <div class="py-3 feature-restaurant-block">
                                            <div class="restaurant-status" :class="[ restaurant.feature_restaurant === 1 ? 'text-success' : 'text-danger' ]">
                                                <i class="fas" :class="[ restaurant.feature_restaurant === 1 ? 'fa-check' : 'fa-times' ]"></i>
                                            </div>
                                            <img :src="[ restaurant.restaurant_banner != '' ? '<?php echo base_url(); ?>uploads/restaurant/restaurant-'+restaurant.restaurant_id+'/'+restaurant.restaurant_banner : bannerUrl ]" alt="" class="feature-image">
                                            <p class="restaurant-title">{{ restaurant.restaurant_name }}</p>
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