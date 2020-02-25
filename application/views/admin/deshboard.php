  <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <div class="row">
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Users</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $totalUser; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                        <i class="fas fa-chart-bar"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Restaurants</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $totalRestaurant; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                        <i class="fas fa-chart-pie"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Offers</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $totalOffer; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Templates</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $totalTemplate; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                        <i class="fas fa-percent"></i>
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
    <div class="container-fluid mt--7">
      <div class="row">
        <div class="col-xl-8 mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Running Offers</h3>
                </div>
                <div class="col text-right">
                  <a href="#!" class="btn btn-sm btn-primary">See all</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Offer Name</th>
                    <th scope="col">Discount</th>
                    <th scope="col">Restaurant</th>
                    <th scope="col">Total Views</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  
                  if (count($runningOffers) > 0) {
                    foreach ($runningOffers as $offer) { ?>
                      <tr>
                        <th scope="row">
                          <?php echo $offer['offer_name']; ?>
                        </th>
                        <td class="text-center">
                          <?php echo $offer['offer_discount']; ?>%
                        </td>
                        <td>
                          <?php echo $offer['restaurant']['restaurant_name']; ?>
                        </td>
                        <td class="text-center">
                            <i class="fas fa-arrow-up text-success mr-3"></i> <?php echo $offer['visit_count']; ?>
                        </td>
                      </tr>
                  <?php }
                  }else {
                    
                  }
                  
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Popular Tags</h3>
                </div>
                <div class="col text-right">
                  <a href="#!" class="btn btn-sm btn-primary">See all</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <?php
              if (count($popularTags) > 0) { ?>
                <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Tag</th>
                    <th scope="col">Total Search</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($popularTags as $tag) { ?>
                    <tr>
                      <th scope="row">
                        <?php echo $tag['tag_name']; ?>
                      </th>
                      <td>
                        <?php echo $tag['visit_count']; ?>
                      </td>
                    </tr>
                  <?php }
                  ?>
                </tbody>
              </table>
              <?php }else {
                echo '<p class="text-center">There is no tag....</p>';
              }
              ?>
            </div>
          </div>
        </div>
      </div>