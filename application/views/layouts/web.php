<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <!-- Mobile Specific
    ========================================================================= -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Title Tag
    ========================================================================= -->
    <title><?php echo $title; ?></title>

    <!-- Browser Specical Files
    ========================================================================= -->
    <!--[if lt IE 9]><script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->
    <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

    <!-- Site Favicon
    ========================================================================= -->
    <link rel="shortcut icon" href="#" title="Favicon" />

    <!-- WP HEAD
    ========================================================================= -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600italic,600,700,700italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/web/css/semantic.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/web/style.css">

    <!-- Header JS -->
    <script src="<?php echo base_url(); ?>assets/web/js/jquery-1.11.1.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/web/js/libs/qrcode.min.js"></script>

    <!-- PLUGIN CSS -->

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/web/ST-User/css/style.css">

</head>
<body>
<div id="page" class="hfeed site">
    
    <!-- Header -->
    <?php $this->load->view('web/includes/header'); ?>

    <div id="content" class="site-content">
        <!-- Page Header -->
        <?php if(isset($pageHeader)) $this->load->view($pageHeader); ?>
        
        <div id="content-wrap" class="container right-sidebar">

            <!-- Offer Slider -->
            <?php if(isset($offerSlider)) $this->load->view($offerSlider); ?>

            <!-- Popular Offer -->
            <?php if(isset($popularOffer)) $this->load->view($popularOffer); ?>

            <!-- Dynamic Content Area -->
            <?php $this->load->view($content); ?>

            <!-- Sidebar -->
            <?php $this->load->view('web/includes/sidebar'); ?>

            <!-- Most Popular Categories -->
            <?php if(isset($mostPopularCategories)) $this->load->view($mostPopularCategories); ?>

        </div> <!-- /#content-wrap -->

        <!-- Mobile app block -->
        <?php 
        // if(isset($mobileApp)) $this->load->view($mobileApp); 
        ?>

    </div> <!-- END .site-content -->

    <!-- Footer -->
    <?php 
    // $this->load->view('web/includes/footer'); 
    ?>

</div><!-- END #page -->

<!-- Footer JS -->
<script src="<?php echo base_url(); ?>assets/web/js/config.js"></script>

<script src="<?php echo base_url(); ?>assets/web/js/libs.js"></script>
<script src="<?php echo base_url(); ?>assets/web/js/libs/semantic.min.js"></script>
<script src="<?php echo base_url(); ?>assets/web/js/libs/owl.carousel.js"></script>
<script src="<?php echo base_url(); ?>assets/web/js/global.js"></script>

<script src="<?php echo base_url(); ?>assets/web/ST-User/js/user.js"></script>


</body>
</html>
