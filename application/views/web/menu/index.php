<!DOCTYPE html>
<html lang="en">
<head>

<!-- Meta -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<!-- Title -->
<title><?php echo $title; ?></title>

<!-- Favicons -->
<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/menu/img/favicon.png">
<link rel="apple-touch-icon" href="<?php echo base_url(); ?>assets/menu/img/favicon_60x60.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>assets/menu/img/favicon_76x76.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url(); ?>assets/menu/img/favicon_120x120.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url(); ?>assets/menu/img/favicon_152x152.png">

<!-- CSS Plugins -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/menu/plugins/bootstrap/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/menu/plugins/slick-carousel/slick/slick.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/menu/plugins/animate.css/animate.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/menu/plugins/animsition/dist/css/animsition.min.css" />

<!-- CSS Icons -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/menu/css/themify-icons.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/menu/plugins/font-awesome/css/font-awesome.min.css" />

<!-- CSS Theme -->
<link id="theme" rel="stylesheet" href="<?php echo base_url(); ?>assets/menu/css/themes/theme-beige.min.css" />

</head>

<body>

<!-- Body Wrapper -->
<div id="body-wrapper" class="animsition">

    <!-- Header -->
    <header id="header" class="light">

        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <!-- Logo -->
                    <div class="module module-logo dark">
                        <a href="<?php echo base_url(); ?>menu/web">
                            <img src="<?php echo base_url(); ?>assets/menu/img/logo-light.svg" alt="" width="88">
                        </a>
                    </div>
                </div>
                <div class="col-md-7">
                    <!-- Navigation -->
                    <nav class="module module-navigation left mr-4">
                        <ul id="nav-main" class="nav nav-main">
                            <li>
                                <a href="<?php echo base_url(); ?>menu/web">Home</a>
                            </li>
                            <li class="has-dropdown">
                                <a href="#">About</a>
                                <div class="dropdown-container">
                                    <ul class="dropdown-mega">
                                        <li><a href="page-about.html">About Us</a></li>
                                        <li><a href="page-services.html">Services</a></li>
                                        <li><a href="page-gallery.html">Gallery</a></li>
                                        <li><a href="page-reviews.html">Reviews</a></li>
                                        <li><a href="page-faq.html">FAQ</a></li>
                                    </ul>
                                    <div class="dropdown-image">
                                        <img src="<?php echo base_url(); ?>assets/menu/img/photos/dropdown-about.jpg" alt="">
                                    </div>
                                </div>
                            </li>
                            <li><a href="<?php echo base_url(); ?>web">Offers</a></li>
                        </ul>
                    </nav>
                    <div class="module left">
                        <a href="menu-list-navigation.html" class="btn btn-outline-secondary"><span>Order</span></a>
                    </div>
                </div>
                <div class="col-md-2">
                    <a href="#" class="module module-cart right" data-toggle="panel-cart">
                        <span class="cart-icon">
                            <i class="ti ti-shopping-cart"></i>
                            <span class="notification">2</span>
                        </span>
                        <span class="cart-value">$32.98</span>
                    </a>
                </div>
            </div>
        </div>

    </header>
    <!-- Header / End -->

    <!-- Header -->
    <header id="header-mobile" class="light">

        <div class="module module-nav-toggle">
            <a href="#" id="nav-toggle" data-toggle="panel-mobile"><span></span><span></span><span></span><span></span></a>
        </div>    

        <div class="module module-logo">
            <a href="<?php echo base_url(); ?>menu/web">
                <img src="<?php echo base_url(); ?>assets/menu/img/logo-horizontal-dark.svg" alt="">
            </a>
        </div>

        <a href="#" class="module module-cart" data-toggle="panel-cart">
            <i class="ti ti-shopping-cart"></i>
            <span class="notification">2</span>
        </a>

    </header>
    <!-- Header / End -->

    <!-- Content -->
    <div id="content">

        <!-- Page Title -->
        <div class="page-title bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 push-lg-4">
                        <h1 class="mb-0">Menu List</h1>
                        <h4 class="text-muted mb-0">Some informations about our restaurant</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="page-content">
            <div class="container">
                <div class="row no-gutters">
                    <div class="col-md-3">
                        <!-- Menu Navigation -->
                        <nav id="menu-navigation" class="stick-to-content" data-local-scroll>
                            <ul class="nav nav-menu bg-dark dark">
                                <?php
                                if (count($categories) > 0) {
                                    foreach ($categories as $category) {
                                        echo '<li><a href="#'.ucfirst($category['category_name']).'">'.$category['category_name'].'</a></li>';
                                    }
                                }
                                ?>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-md-9">
                        
                        <?php
                        if (count($categories) > 0) {
                            foreach ($categories as $category) { ?>
                                <div id="<?php echo ucfirst($category['category_name']); ?>" class="menu-category">
                                    <div class="menu-category-title">
                                        <div class="bg-image"><img src="<?php echo base_url(); ?>uploads/category/menu-title-<?php echo $category['category_name']; ?>.jpg" alt=""></div>
                                        <h2 class="title"><?php echo ucfirst($category['category_name']); ?></h2>
                                    </div>
                                    <div class="menu-category-content">
                                        <!-- Menu Item -->
                                        
                                        <?php
                                        if (count($category['foods']) > 0) {
                                            foreach ($category['foods'] as $food) { ?>
                                                <div class="menu-item menu-list-item">
                                                    <div class="row align-items-center">
                                                        <div class="col-sm-6 mb-2 mb-sm-0">
                                                            <h6 class="mb-0"><?php echo $food['food_name']; ?></h6>
                                                            <span class="text-muted text-sm">
                                                            <?php
                                                                $tags = '';
                                                                if (count($food['food_tags']) > 0) {
                                                                    foreach ($food['food_tags'] as $food_tag) {
                                                                        $tags .= ucfirst($food_tag['menu_tag_name']).', ';
                                                                    }
                                                                    $tags = rtrim($tags, ', ');
                                                                    echo $tags;
                                                                }
                                                            ?>
                                                            </span>
                                                        </div>
                                                        <div class="col-sm-6 text-sm-right">
                                                            <span class="text-md mr-4"><span class="text-muted">from</span> $<?php echo $food['food_lowest_price']; ?></span>
                                                            <button class="btn btn-outline-secondary btn-sm" data-target="#productModal" data-toggle="modal"><span>Add to cart</span></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal / Product -->
                                                <div class="modal fade" id="productModal" role="dialog">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header modal-header-lg dark bg-dark">
                                                                <div class="bg-image"><img src="<?php echo base_url(); ?>assets/menu/img/photos/modal-add.jpg" alt=""></div>
                                                                <h4 class="modal-title">Specify your dish</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="ti-close"></i></button>
                                                            </div>
                                                            <div class="modal-product-details">
                                                                <div class="row align-items-center">
                                                                    <div class="col-9">
                                                                        <h6 class="mb-0"><?php echo $food['food_name']; ?></h6>
                                                                        <span class="text-muted"><?php echo $tags; ?></span>
                                                                    </div>
                                                                    <div class="col-3 text-lg text-right">$<?php echo $food['food_lowest_price']; ?></div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-body panel-details-container">
                                                                <!-- Panel Details / Size -->
                                                                <div class="panel-details">
                                                                    <h5 class="panel-details-title">
                                                                        <label class="custom-control custom-radio">
                                                                            <input name="radio_title_size" type="radio" class="custom-control-input">
                                                                            <span class="custom-control-indicator"></span>
                                                                        </label>
                                                                        <a href="#panelDetailsSize" data-toggle="collapse">Size</a>
                                                                    </h5>
                                                                    <div id="panelDetailsSize" class="collapse show">
                                                                        <div class="panel-details-content">
                                                                            
                                                                            <?php
                                                                            if (count($food['food_prices']) > 0) {
                                                                                foreach ($food['food_prices'] as $food_price) { ?>
                                                                                <div class="form-group">
                                                                                    <label class="custom-control custom-radio">
                                                                                        <input name="radio_size" type="radio" class="custom-control-input" checked>
                                                                                        <span class="custom-control-indicator"></span>
                                                                                        <span class="custom-control-description"><?php echo $food_price['food_size']['food_size_name']; ?> - <?php echo $food_price['food_weight']; ?>g ($<?php echo $food_price['food_price']; ?>)</span>
                                                                                    </label>
                                                                                </div>
                                                                            <?php }
                                                                            }
                                                                            ?>
                                                                            
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- Panel Details / Additions -->
                                                                <div class="panel-details">
                                                                    <h5 class="panel-details-title">
                                                                        <label class="custom-control custom-radio">
                                                                            <input name="radio_title_additions" type="radio" class="custom-control-input">
                                                                            <span class="custom-control-indicator"></span>
                                                                        </label>
                                                                        <a href="#panelDetailsAdditions" data-toggle="collapse">Additions</a>
                                                                    </h5>
                                                                    <div id="panelDetailsAdditions" class="collapse">
                                                                        <div class="panel-details-content">
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                <?php
                                                                                    if (count($aditionals) > 0) {
                                                                                        foreach ($aditionals as $aditional) { ?>
                                                                                            <div class="form-group">
                                                                                                <label class="custom-control custom-checkbox">
                                                                                                    <input type="checkbox" class="custom-control-input">
                                                                                                    <span class="custom-control-indicator"></span>
                                                                                                    <span class="custom-control-description"><?php echo $aditional['food_aditional_name']; ?> ($<?php echo $aditional['food_aditional_price']; ?>)</span>
                                                                                                </label>
                                                                                            </div>  
                                                                                    <?php }   
                                                                                    }
                                                                                ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- Panel Details / Other -->
                                                                <div class="panel-details">
                                                                    <h5 class="panel-details-title">
                                                                        <label class="custom-control custom-radio">
                                                                            <input name="radio_title_other" type="radio" class="custom-control-input">
                                                                            <span class="custom-control-indicator"></span>
                                                                        </label>
                                                                        <a href="#panelDetailsOther" data-toggle="collapse">Other</a>
                                                                    </h5>
                                                                    <div id="panelDetailsOther" class="collapse">
                                                                        <textarea cols="30" rows="4" class="form-control" placeholder="Put this any other informations..."></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="button" class="modal-btn btn btn-secondary btn-block btn-lg" data-dismiss="modal"><span>Add to Cart</span></button>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php }
                                        }
                                        ?>
                                        
                                    </div>
                                </div>
                        <?php }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer id="footer" class="bg-dark dark">
            
            <div class="container">
                <!-- Footer 1st Row -->
                <div class="footer-first-row row">
                    <div class="col-lg-3 text-center">
                        <a href="<?php echo base_url(); ?>menu/web"><img src="<?php echo base_url(); ?>assets/menu/img/logo-light.svg" alt="" width="88" class="mt-5 mb-5"></a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <h5 class="text-muted">Latest news</h5>
                        <ul class="list-posts">
                            <li>
                                <a href="blog-post.html" class="title">How to create effective webdeisign?</a>
                                <span class="date">February 14, 2015</span>
                            </li>
                            <li>
                                <a href="blog-post.html" class="title">Awesome weekend in Polish mountains!</a>
                                <span class="date">February 14, 2015</span>
                            </li>
                            <li>
                                <a href="blog-post.html" class="title">How to create effective webdeisign?</a>
                                <span class="date">February 14, 2015</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-5 col-md-6">
                        <h5 class="text-muted">Subscribe Us!</h5>
                        <!-- MailChimp Form -->
                        <form action="//suelo.us12.list-manage.com/subscribe/post-json?u=ed47dbfe167d906f2bc46a01b&amp;id=24ac8a22ad" id="sign-up-form" class="sign-up-form validate-form mb-3" method="POST">
                            <div class="input-group">
                                <input name="EMAIL" id="mce-EMAIL" type="email" class="form-control" placeholder="Tap your e-mail..." required>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary btn-submit" type="submit">
                                        <span class="description">Subscribe</span>
                                        <span class="success">
                                            <svg x="0px" y="0px" viewBox="0 0 32 32"><path stroke-dasharray="19.79 19.79" stroke-dashoffset="19.79" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M9,17l3.9,3.9c0.1,0.1,0.2,0.1,0.3,0L23,11"/></svg>
                                        </span>
                                        <span class="error">Try again...</span>
                                    </button>
                                </span>
                            </div>
                        </form>
                        <h5 class="text-muted mb-3">Social Media</h5>
                        <a href="#" class="icon icon-social icon-circle icon-sm icon-facebook"><i class="fa fa-facebook"></i></a>
                        <a href="#" class="icon icon-social icon-circle icon-sm icon-google"><i class="fa fa-google"></i></a>
                        <a href="#" class="icon icon-social icon-circle icon-sm icon-twitter"><i class="fa fa-twitter"></i></a>
                        <a href="#" class="icon icon-social icon-circle icon-sm icon-youtube"><i class="fa fa-youtube"></i></a>
                        <a href="#" class="icon icon-social icon-circle icon-sm icon-instagram"><i class="fa fa-instagram"></i></a>
                    </div>
                </div>
                <!-- Footer 2nd Row -->
                <div class="footer-second-row">
                    <span class="text-muted">Copyright Soup 2017©. Made with love by Suelo.</span>
                </div>
            </div>

            <!-- Back To Top -->
            <a href="#" id="back-to-top"><i class="ti ti-angle-up"></i></a>

        </footer>
        <!-- Footer / End -->

    </div>
    <!-- Content / End -->

    <!-- Panel Cart -->
    <div id="panel-cart">
        <div class="panel-cart-container">
            <div class="panel-cart-title">
                <h5 class="title">Your Cart</h5>
                <button class="close" data-toggle="panel-cart"><i class="ti ti-close"></i></button>
            </div>
            <div class="panel-cart-content">
                <table class="table-cart">
                    <tr>
                        <td class="title">
                            <span class="name"><a href="#productModal" data-toggle="modal">Pizza Chicked BBQ</a></span>
                            <span class="caption text-muted">26”, deep-pan, thin-crust</span>
                        </td>
                        <td class="price">$9.82</td>
                        <td class="actions">
                            <a href="#productModal" data-toggle="modal" class="action-icon"><i class="ti ti-pencil"></i></a>
                            <a href="#" class="action-icon"><i class="ti ti-close"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="title">
                            <span class="name"><a href="#productModal" data-toggle="modal">Beef Burger</a></span>
                            <span class="caption text-muted">Large (500g)</span>
                        </td>
                        <td class="price">$9.82</td>
                        <td class="actions">
                            <a href="#productModal" data-toggle="modal" class="action-icon"><i class="ti ti-pencil"></i></a>
                            <a href="#" class="action-icon"><i class="ti ti-close"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="title">
                            <span class="name"><a href="#productModal" data-toggle="modal">Extra Burger</a></span>
                            <span class="caption text-muted">Small (200g)</span>
                        </td>
                        <td class="price text-success">$0.00</td>
                        <td class="actions">
                            <a href="#productModal" data-toggle="modal" class="action-icon"><i class="ti ti-pencil"></i></a>
                            <a href="#" class="action-icon"><i class="ti ti-close"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="title">
                            <span class="name">Weekend 20% OFF</span>
                        </td>
                        <td class="price text-success">-$8.22</td>
                        <td class="actions"></td>
                    </tr>
                </table>
                <div class="cart-summary">
                    <div class="row">
                        <div class="col-7 text-right text-muted">Order total:</div>
                        <div class="col-5"><strong>$21.02</strong></div>
                    </div>
                    <div class="row">
                        <div class="col-7 text-right text-muted">Devliery:</div>
                        <div class="col-5"><strong>$3.99</strong></div>
                    </div>
                    <hr class="hr-sm">
                    <div class="row text-lg">
                        <div class="col-7 text-right text-muted">Total:</div>
                        <div class="col-5"><strong>$24.21</strong></div>
                    </div>
                </div>
            </div>
        </div>
        <a href="<?php echo base_url(); ?>menu/web/checkout" class="panel-cart-action btn btn-secondary btn-block btn-lg"><span>Go to checkout</span></a>
    </div>

    <!-- Panel Mobile -->
    <nav id="panel-mobile">
        <div class="module module-logo bg-dark dark">
            <a href="#">
                <img src="<?php echo base_url(); ?>assets/menu/img/logo-light.svg" alt="" width="88">
            </a>
            <button class="close" data-toggle="panel-mobile"><i class="ti ti-close"></i></button>
        </div>
        <nav class="module module-navigation"></nav>
        <div class="module module-social">
            <h6 class="text-sm mb-3">Follow Us!</h6>
            <a href="#" class="icon icon-social icon-circle icon-sm icon-facebook"><i class="fa fa-facebook"></i></a>
            <a href="#" class="icon icon-social icon-circle icon-sm icon-google"><i class="fa fa-google"></i></a>
            <a href="#" class="icon icon-social icon-circle icon-sm icon-twitter"><i class="fa fa-twitter"></i></a>
            <a href="#" class="icon icon-social icon-circle icon-sm icon-youtube"><i class="fa fa-youtube"></i></a>
            <a href="#" class="icon icon-social icon-circle icon-sm icon-instagram"><i class="fa fa-instagram"></i></a>
        </div>
    </nav>

    <!-- Body Overlay -->
    <div id="body-overlay"></div>

</div>

<!-- JS Plugins -->
<script src="<?php echo base_url(); ?>assets/menu/plugins/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/menu/plugins/tether/dist/js/tether.min.js"></script>
<script src="<?php echo base_url(); ?>assets/menu/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/menu/plugins/slick-carousel/slick/slick.min.js"></script>
<script src="<?php echo base_url(); ?>assets/menu/plugins/jquery.appear/jquery.appear.js"></script>
<script src="<?php echo base_url(); ?>assets/menu/plugins/jquery.scrollto/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url(); ?>assets/menu/plugins/jquery.localscroll/jquery.localScroll.min.js"></script>
<script src="<?php echo base_url(); ?>assets/menu/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/menu/plugins/jquery.mb.ytplayer/dist/jquery.mb.YTPlayer.min.js"></script>
<script src="<?php echo base_url(); ?>assets/menu/plugins/twitter-fetcher/js/twitterFetcher_min.js"></script>
<script src="<?php echo base_url(); ?>assets/menu/plugins/skrollr/dist/skrollr.min.js"></script>
<script src="<?php echo base_url(); ?>assets/menu/plugins/animsition/dist/js/animsition.min.js"></script>

<!-- JS Core -->
<script src="<?php echo base_url(); ?>assets/menu/js/core.js"></script>

</body>

</html>
