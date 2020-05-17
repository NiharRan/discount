
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
                        <input type="text" @keyup="fetchOffers" v-model="search" class="form-control" placeholder="Search">
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
                                <div class="media align-items-center">
                                    <a href="#" class="avatar-md mr-3">
                                        <img 
                                        alt="Image placeholder" 
                                        style="width: 100%;"
                                        :src=" (offer.offer_image.length > 0) ? 'uploads/offer/offer-'+offer.offer_id+'/'+offer.offer_image_thumb : 'uploads/default/offer/default-logo.png'">
                                    </a>
                                    <div class="media-body">
                                        <h2 class="mb-0 text-m">{{ offer.offer_name }}</h2>
                                    </div>
                                </div>
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
                                            v-if="hasPermission('edit', 'offer')" 
                                            href="#"
                                            @click="preview(offer)">
                                                <i class="fas fa-edit text-primary"></i> Edit
                                        </a>
                                        <a class="dropdown-item" v-if="hasPermission('edit', 'offer')" href="#" @click="changeStatus(offer)"><i class="fas" :class="[offer.offer_status == 1 ? 'fa-times text-danger' : 'fa-check text-success' ]"></i> {{ offer.offer_status == 1 ? 'Inactive' : 'Active' }}</a>
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
                        <div class="modal-header">
                            <h3 class="modal-title" style="flex: 1;">Edit <strong class="text-primary">{{ formData.offer_name}}'s</strong> info <span class="text-success">{{ formData.restaurant_name }}</span>
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
                            <form @submit.prevent="update" v-if="!isloading">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input type="text" v-model="formData.offer_name" placeholder="Offer Name" class="form-control">
                                        <!-- if offer_name field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.offer_name }}</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <multiselect 
                                            v-model="formData.restaurant" 
                                            :height="300"
                                            :options="restaurants" 
                                            :multiple="false" 
                                            :close-on-select="true" 
                                            :clear-on-select="true" 
                                            :preserve-search="true" 
                                            placeholder="Select Restaurant"
                                            label="restaurant_name" 
                                            track-by="restaurant_name" 
                                            :preselect-first="false"
                                        >
                                        </multiselect>
                                        <!-- if template_id field is empty and try to submit show error message -->
                                        <small class="text-danger">{offer }}</small>
                                    </div>
                                    <div class="col-md-6">
                                        <multiselect 
                                            v-model="formData.template" 
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
                                    <div class="col-md-8 col-sm-12">
                                    <ckeditor :editor="editor" v-model="formData.offer_description" :config="editorConfig"></ckeditor>
                                        <!-- if offer_description field is empty and try to submit show error message -->
                                        <small class="text-danger">{{ errors.offer_description }}</small>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="banner-logo-upload-box width-full">
                                            <img id="logo" :src="imageUrl" alt="">
                                            <label class="btn-pill">
                                                <i class="fas fa-camera"></i>
                                                <input @change="selectImage" type="file" class="hidden"/>
                                            </label>
                                        </div>
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
                                <div class="form-group row">
                                    <div class="col-md-12 col-sm-12 text-center">
                                        <button type="submit" class="btn btn-success btn-sm">Update</button>
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