<style>

.navbar-vertical.navbar-expand-md>[class*="container"] {
    flex-direction: column;
    align-items: stretch;
    min-height: auto !important;
    padding-left: 0;
    padding-right: 0;
}

@media (max-width: 575.98px) {
  .btn-toggler-pc {
    display: none;
  }
}

@media only screen and (min-width: 575.98px) and (max-width: 768px) {
  .navbar-vertical.navbar-expand-md.fixed-left {
      left: 0;
      border-width: 0 1px 0 0;
  }
  .navbar-vertical.navbar-expand-md {
      display: block;
      position: fixed;
      top: 0;
      bottom: 0;
      width: 100%;
      max-width: 150px;
      padding-left: 1.2rem;
      padding-right: 1.2rem;
      overflow-y: auto;
  }
  .navbar-vertical.navbar-expand-md.fixed-left+.main-content {
      margin-left: 150px;
  }
  .main-content .container-fluid {
    padding-left: 30px !important;
    padding-right: 30px !important;
  }
}

</style>
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
            <li class="nav-item active">
            <a class=" nav-link active " href="<?php echo site_url('/'); ?>"> <i class="ni ni-tv-2 text-primary"></i> <span class="nav-link-text">Dashboard</span>
              </a>
            </li>

            <?php if($this->permission->has_permission('user', 'list-view')) {?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>users">
              <i class="fas fa-user text-teal"></i> Users
              </a>
            </li>
            <?php } ?>


            <?php if($this->permission->has_permission('restaurant', 'list-view')) {?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo site_url('restaurants'); ?>">
                <i class="fas fa-store text-teal"></i> <span class="nav-link-text">Restaurants</span>
              </a>
            </li>
            <?php } ?>

            <?php if($this->permission->has_permission('offer', 'list-view')) {?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo site_url('offers'); ?>">
              <i class="fas fa-store text-teal"></i> <span class="nav-link-text">Offers</span>
              </a>
            </li>
            <?php } ?>
          </ul>
          <h6 class="navbar-heading text-muted">Setting</h6>
          <ul class="navbar-nav mb-md-3">
            <!-- if user has permission to view resturant list -->
            <?php if($this->permission->has_permission('resturant', 'list-view')) {?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>settings/resturants">
              <i class="fas fa-store text-green"></i> Resturants
              </a>
            </li>
            <?php }?>
            

            <!-- if user has permission to view tag list -->
            <?php if($this->permission->has_permission('tag', 'list-view')) {?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>settings/tags">
              <i class="fas fa-tag text-green"></i> Tags
              </a>
            </li>
            <?php }?>

            <!-- if user has permission to view template list -->
            <?php if($this->permission->has_permission('template', 'list-view')) {?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>settings/templates">
              <i class="fas fa-tag text-green"></i> Templates
              </a>
            </li>
            <?php }?>
          </ul>
        </div>
      </div>
    </div>
  </nav>