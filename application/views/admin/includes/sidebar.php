<nav class="navbar navbar-fixed navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main" style="overflow-x: hidden;z-index:1000">
    <div class="container-fluid position-relative">
      <!-- Brand -->
      <button class="btn-toggler-mobile" style="display: none" type="button">
        <i class="fas fa-bars text-primary"></i>
        </button>
      <a class="navbar-brand pt-0 mb-2 pb-2 border-bottom" href="<?php echo site_url('/'); ?>">
        <img src="<?php echo base_url(); ?>assets/img/brand/blue.png" class="navbar-brand-img" alt="...">
      </a>
        <div class="navbar-wrapper">
          <!-- Navigation -->
          <ul class="navbar-nav" style="flex: auto;">
            <li class="nav-item  class=" active" ">
            <a class=" nav-link active " href="<?php echo site_url('/'); ?>"> <i class="ni ni-tv-2 text-primary"></i> <span class="nav-link-text">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link " href="./examples/icons.html">
                <i class="ni ni-planet text-blue"></i> <span class="nav-link-text">Icons</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link " href="./examples/maps.html">
                <i class="ni ni-pin-3 text-orange"></i> <span class="nav-link-text">Maps</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link " href="./examples/profile.html">
                <i class="ni ni-single-02 text-yellow"></i> <span class="nav-link-text">User Profile</span>
              </a>
            </li>
          </ul>
          <h6 class="navbar-heading text-muted">Setting</h6>
          <ul class="navbar-nav mb-md-3">
            <?php if($this->permission->has_permission('resturant', 'list-view')) {?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>settings/resturants">
              <i class="fas fa-store text-green"></i> Resturants
              </a>
            </li>
            <?php }?>
            <?php if($this->permission->has_permission('tag', 'list-view')) {?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>settings/tags">
              <i class="fas fa-tag text-green"></i> Tags
              </a>
            </li>
            <?php }?>
            <li class="nav-item">
              <a class="nav-link" href="https://demos.creative-tim.com/argon-dashboard/docs/components/alerts.html">
                <i class="ni ni-ui-04"></i> Components
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>