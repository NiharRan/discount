<!--

=========================================================
* Argon Dashboard - v1.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard
* Copyright 2019 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>
    <?php echo $title; ?>
  </title>
  <!-- Favicon -->
  <link href="<?php echo base_url(); ?>assets/img/brand/favicon.png" rel="icon" type="image/png">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <!-- Icons -->
  <link href="<?php echo base_url(); ?>assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="<?php echo base_url(); ?>assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>assets/css/responsive.css" rel="stylesheet" />

  <?php
  
  /**
   * includes all custom css plugins 
   * to use vuejs for front-end
   */

   if (isset($extrastyle)) {
     $this->load->view($extrastyle);
   }
  
  ?>

</head>

<body class="" style="overflow-x: hidden">
    <?php $this->load->view('admin/includes/sidebar'); ?>
  <div class="main-content">
    <!-- Navbar -->
    <?php $this->load->view('admin/includes/header'); ?>
    <!-- End Navbar -->
    <!-- Header -->
        <?php $this->load->view($content); ?>
      <!-- Footer -->
      <?php $this->load->view('admin/includes/footer'); ?>
    </div>
  </div>
  <!-- base url -->
  <input type="hidden" name="base_url" id="base_url" value="<?php echo base_url(); ?>">
  <!--   Core   -->
  <script src="<?php echo base_url(); ?>assets/js/plugins/jquery/dist/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!--   Optional JS   -->
  <script src="<?php echo base_url(); ?>assets/js/plugins/chart.js/dist/Chart.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/plugins/chart.js/dist/Chart.extension.js"></script>
  <!--   Argon JS   -->
  <script src="<?php echo base_url(); ?>assets/js/argon-dashboard.min.js?v=1.1.0"></script>
  <script src="<?php echo base_url(); ?>assets/js/main.js"></script>


  <?php
  
  /**
   * includes all custom js plugins 
   * to use vuejs for front-end
   */

   if (isset($extrascript)) {
     $this->load->view($extrascript);
   }
  
  ?>

<!-- 
  vue component will be linkedup here
 -->
<?php if(isset($vuecomponent)) {?>
<script src="<?php echo base_url(); ?>assets/vue/<?php echo $vuecomponent; ?>.js"></script>
<?php }?>

</body>

</html>