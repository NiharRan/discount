
 <div class="container-fluid">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0"><i class="fas fa-plus"></i> Create New Resturant
                        <a href="<?php echo site_url('settings/resturants'); ?>" class="btn btn-sm btn-primary float-right"><i class="fas fa-arrow-left"></i> Back</a>
                    </h3>
                </div>
                <div class="card-body">
                    <form action="<?php site_url('settings/resturants/store'); ?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-9 col-sm-12 col-xs-12">
                                <div class="form-group row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label for="resturant_name">Name <span class="text-danger">*</span></label>
                                        <input type="text" id="resturant_name" name="resturant_name" class="form-control">
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label for="resturant_moto">Moto</label>
                                        <input type="text" id="resturant_moto" name="resturant_moto" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label for="resturant_contact_number">Contact No. <span class="text-danger">*</span></label>
                                        <input type="text" id="resturant_contact_number" name="resturant_contact_number" class="form-control">
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label for="resturant_email">E-mail</label>
                                        <input type="text" id="resturant_email" name="resturant_email" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label for="resturant_address">Address <span class="text-danger">*</span></label>
                                        <input type="text" id="resturant_address" name="resturant_address" class="form-control">
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label for="resturant_website">Website</label>
                                        <input type="text" id="resturant_website" name="resturant_website" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label for="resturant_establish_date">Establish Date <span class="text-danger">*</span></label>
                                        <input type="text" id="resturant_establish_date" name="resturant_establish_date" class="form-control">
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-6">
                                        <label for="resturant_open_at">Open At</label>
                                        <input type="text" id="resturant_open_at" name="resturant_open_at" class="form-control">
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-6">
                                        <label for="resturant_close_at">Close At</label>
                                        <input type="text" id="resturant_close_at" name="resturant_close_at" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label for="tags">Tags <span class="text-danger">*</span></label>
                                        <input type="text" id="tags" name="tags[]" class="form-control">
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label for="resturant_category">Category</label>
                                        <input type="text" id="resturant_category" name="resturant_category" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12 col-xs-12">
                            <label class="input-label">Profile Pictute</label>
                                <div class="image-upload-box avatar-upload">
                                    <div class="overlay">
                                        <input @change="selectAvatar" type="file">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                    <img :src="[user.avatar != null ? `/uploads/users/${user.avatar}` : default_avatar]" alt="" class="preview-logo">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>