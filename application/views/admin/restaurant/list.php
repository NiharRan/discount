
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
                    <tr>
                    <th scope="row">
                        <div class="media align-items-center">
                        <a href="#" class="avatar-md mr-3">
                            <img alt="Image placeholder" src="../assets/img/theme/bootstrap.jpg">
                        </a>
                        <div class="media-body">
                            <h1 class="mb-0 text-m">Argon Design System</h1>
                            <p class="mb-0 text-sm"><i class="fas fa-map-marker-alt"></i> Jallarpar Road Zindabazar</p>
                            <p class="mb-0 text-sm"><i class="fas fa-phone"></i> 01761-152939</p>
                            <p class="mb-0 text-sm"><i class="fas fa-globe"></i> www.panshirestaurant.com</p>
                        </div>
                        </div>
                    </th>
                    <td>
                        Nihar Ranjan Das
                    </td>
                    <td>
                        <div class="btn-group">
                        <span class="badge badge-success">sweets</span>
                        <span class="badge badge-success">familly</span>
                        <span class="badge badge-success">barger</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                        <span class="mr-2">*****</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-dot mr-4">
                        <i class="bg-success"></i> active
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </td>
                    </tr>
                </tbody>
                </table>
            </div>
            <div class="card-footer py-4">
                <nav aria-label="...">
                <ul class="pagination justify-content-end mb-0">
                    <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">
                        <i class="fas fa-angle-left"></i>
                        <span class="sr-only">Previous</span>
                    </a>
                    </li>
                    <li class="page-item active">
                    <a class="page-link" href="#">1</a>
                    </li>
                    <li class="page-item">
                    <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                    <a class="page-link" href="#">
                        <i class="fas fa-angle-right"></i>
                        <span class="sr-only">Next</span>
                    </a>
                    </li>
                </ul>
                </nav>
            </div>
            </div>
        </div>
    </div>
</div>