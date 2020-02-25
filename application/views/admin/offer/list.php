
 <div class="container-fluid" id="offers_info">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
            <div class="card-header border-0">
                <div class="form-inline justify-content-between">
                    <div class="form-group">
                        <h3 class="mb-0"><i class="fas fa-store"></i> Offer Lists</h3>
                    </div>
                    <div class="form-group">
                        <input type="text" @keyup="fetchoffers" v-model="search" class="form-control" placeholder="Search">
                    </div>
                    <div class="form-group mx-sm-3">
                        <!-- if logged in user has permission to create new offer -->
                        <?php if($this->permission->has_permission('offer', 'create')) { ?>
                            <a href="<?php echo site_url('offers/create'); ?>" class="btn btn-sm btn-primary float-right"><i class="fas fa-plus"></i> New</a>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Offer</th>
                        <th scope="col">Restaurant</th>
                        <th scope="col" class="text-right">Discount</th>
                        <th scope="col" class="text-center">Duration</th>
                        <th scope="col">Template</th>
                        <th scope="col">Creator</th>
                        <th scope="col">Status</th>
                        <th>Qr Code</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                    <tbody>
                        <tr v-for="(offer, key) in offers" :key="key">
                            <th scope="row">
                                {{ offer.offer_name }}
                            </th>
                            <td>
                                {{ offer.restaurant.restaurant_name }}
                            </td>
                            <td class="text-right">
                                {{ offer.offer_discount }}%
                            </td>
                            <td class="text-center">
                                {{ offer.offer_start || humanReadableFormat }} - {{ offer.offer_end || humanReadableFormat }}
                            </td>
                            <td>
                                {{ offer.template.template_name }}
                            </td>
                            <td>
                                {{ offer.user.name }}
                            </td>
                            <td>
                                <span class="badge badge-dot mr-4">
                                <i :class="[offer.offer_status == 1 ? 'bg-success' : 'bg-danger' ]"></i> {{ offer.offer_status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <qrcode :value="[ offer.offer_barcode ]" :options="{ width: 50 }"></qrcode>
                            </td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a 
                                            class="dropdown-item" 
                                            v-if="hasPermission(offer, 'edit')" 
                                            href="#"
                                            @click="preview(offer)">
                                                <i class="fas fa-edit text-primary"></i> Edit
                                        </a>
                                        <a class="dropdown-item" v-if="hasPermission(offer, 'edit')" href="#" @click="changeStatus(offer)"><i class="fas" :class="[offer.offer_status == 1 ? 'fa-times text-danger' : 'fa-check text-success' ]"></i> {{ offer.offer_status == 1 ? 'Inactive' : 'Active' }}</a>
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
                        <div class="modal-body">
                            
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>