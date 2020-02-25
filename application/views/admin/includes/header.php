  <nav class="navbar navbar-top navbar-expand-md bg-gradient-primary navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <button class="btn-toggler btn-toggler-pc" type="button">
        <i class="fas fa-bars text-white"></i>
        </button>

        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="/">Welcome To Offer</a>
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="media align-items-center">
                <span class="avatar avatar-sm rounded-circle">
                  <img alt="Image placeholder" src="<?php echo base_url(); ?>uploads/user/user-<?php echo $this->session->userdata('user_id'); ?>/<?php echo $this->session->userdata('avatar');?>">
                </span>
                <div class="media-body ml-2 d-none d-lg-block">
                  <span class="mb-0 text-sm  font-weight-bold"><?php echo $this->session->userdata('name'); ?></span>
                </div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
              <div class=" dropdown-header noti-title">
                <h6 class="text-overflow m-0">Welcome!</h6>
              </div>
              <a href="<?php echo site_url('users/'.$this->session->userdata('username')); ?>" class="dropdown-item">
                <i class="ni ni-single-02"></i>
                <span>My profile</span>
              </a>
              <a href="<?php echo site_url('users/setting/'.$this->session->userdata('username')); ?>" class="dropdown-item">
                <i class="ni ni-settings-gear-65"></i>
                <span>Settings</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="<?php echo base_url(); ?>auth/logout" class="dropdown-item">
                <i class="ni ni-user-run"></i>
                <span>Logout</span>
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>