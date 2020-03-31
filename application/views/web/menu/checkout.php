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

    <div id="app">
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
                        <a href="#" @click="panelCartToggle" class="module module-cart right">
                            <span class="cart-icon">
                                <i class="ti ti-shopping-cart"></i>
                                <span class="notification">{{ productCount }}</span>
                            </span>
                            <span class="cart-value">${{ totalPrice }}</span>
                        </a>
                    </div>
                </div>
            </div>

        </header>
        <!-- Header / End -->
        <input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
        <input type="hidden" id="restaurant_slug" value="<?php echo $restaurant_slug; ?>">
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
        <div class="page-title bg-dark dark">
            <!-- BG Image -->
            <div class="bg-image bg-parallax"><img src="<?php echo base_url(); ?>assets/menu/img/photos/bg-croissant.jpg" alt=""></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 push-lg-4">
                        <h1 class="mb-0">Checkout</h1>
                        <h4 class="text-muted mb-0">Some informations about our restaurant</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section -->
        <section class="section bg-light">

            <div class="container">
                <div class="row">
                    <div class="col-xl-4 push-xl-8 col-lg-5 push-lg-7">
                        <div class="shadow bg-white stick-to-content mb-4">
                            <div class="bg-dark dark p-4"><h5 class="mb-0">My order</h5></div>
                            <table class="table-cart">
                                <tr v-if="orderList.length > 0"
                                    v-for="(order, key) in orderList"
                                    :key="key">
                                    <td class="title">
                                        <span class="name"><a href="#productModal" data-toggle="modal">{{ order.food_name }}</a></span>
                                        <span class="caption text-muted">{{ order.food_size_name}} ({{ order.food_weight }}g)</span>
                                    </td>
                                    <td class="price">${{ parseFloat(parseFloat(order.food_price) + parseFloat(order.food_aditional_price)).toFixed(2) }}</td>
                                    <td class="actions">
                                        <a href="#" @click="showFoodInfoForEdit(order, key)" data-toggle="modal" class="action-icon"><i class="ti ti-pencil"></i></a>
                                        <a href="#" class="action-icon" @click="removeFromCart(key)"><i class="ti ti-close"></i></a>
                                    </td>
                                </tr>
                            </table>
                            <div class="cart-summary">
                                <div class="row">
                                    <div class="col-7 text-right text-muted">Order total:</div>
                                    <div class="col-5"><strong>${{ totalPrice }}</strong></div>
                                </div>
                                <hr class="hr-sm">
                                <div class="row text-lg">
                                    <div class="col-7 text-right text-muted">Total:</div>
                                    <div class="col-5"><strong>${{ totalPrice }}</strong></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 pull-xl-4 col-lg-7 pull-lg-5">
                        <form @submit.prevent="orderNow">
                            <div class="bg-white p-4 p-md-5 mb-4">
                                <h4 class="border-bottom pb-4"><i class="ti ti-user mr-3 text-primary"></i>Basic informations</h4>
                                <div class="row mb-5">
                                    <div class="form-group col-sm-6">
                                        <label>Name:</label>
                                        <input v-model="formData.customer_name" type="text" class="form-control">
                                        <span class="text-danger">{{ errors.customer_name }}</span>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Surename:</label>
                                        <input v-model="formData.customer_surname" type="text" class="form-control">
                                        <span class="text-danger">{{ errors.customer_surname }}</span>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Street and number:</label>
                                        <input v-model="formData.customer_street_no" type="text" class="form-control">
                                        <span class="text-danger">{{ errors.customer_street_no }}</span>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>City:</label>
                                        <input v-model="formData.customer_city" type="text" class="form-control">
                                        <span class="text-danger">{{ errors.customer_city }}</span>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>Phone number:</label>
                                        <input v-model="formData.customer_phone" type="text" class="form-control">
                                        <span class="text-danger">{{ errors.customer_phone }}</span>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label>E-mail address:</label>
                                        <input v-model="formData.customer_email" type="email" class="form-control">
                                        <span class="text-danger">{{ errors.customer_email }}</span>
                                    </div>
                                </div>

                                <h4 class="border-bottom pb-4"><i class="ti ti-package mr-3 text-primary"></i>Delivery</h4>
                                <div class="row mb-5">
                                    <div class="form-group col-sm-6">
                                        <label>Delivery time:</label>
                                        <div class="select-container">
                                            <select v-model="formData.order_priority" class="form-control">
                                                <option value="1" selected="selected">As fast as possible</option>
                                                <option value="2">In one hour</option>
                                                <option value="3">In two hours</option>
                                            </select>
                                            <span class="text-danger">{{ errors.order_priority }}</span>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="border-bottom pb-4"><i class="ti ti-wallet mr-3 text-primary"></i>Payment</h4>
                                <div class="row text-lg">
                                    <div class="col-md-4 col-sm-6 form-group">
                                        <label class="custom-control custom-radio">
                                            <input type="radio" v-model="formData.payment_type" value="1" class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Cash</span>
                                        </label>
                                    </div>
                                    <span class="text-danger">{{ errors.payment_type }}</span>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg"><span>Order now!</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </section>

        <!-- Panel Cart -->
    <div id="panel-cart">
        <div class="panel-cart-container">
            <div class="panel-cart-title">
                <h5 class="title">My Cart</h5>
                <button class="close" @click="hidePanelCart" data-toggle="panel-cart"><i class="ti ti-close"></i></button>
            </div>
            <div class="panel-cart-content">
                <table class="table-cart">
                    <tr v-if="orderList.length > 0"
                        v-for="(order, key) in orderList"
                        :key="key">
                        <td class="title">
                            <span class="name"><a href="#productModal" data-toggle="modal">{{ order.food_name }}</a></span>
                            <span class="caption text-muted">{{ order.food_size_name}} ({{ order.food_weight }}g)</span>
                        </td>
                        <td class="price">${{ order.food_price }}</td>
                        <td class="actions">
                            <a href="#" @click="showFoodInfoForEdit(order, key)" data-toggle="modal" class="action-icon"><i class="ti ti-pencil"></i></a>
                            <a href="#" class="action-icon" @click="removeFromCart(key)"><i class="ti ti-close"></i></a>
                        </td>
                    </tr>
                </table>
                <div class="cart-summary">
                    <div class="row">
                        <div class="col-7 text-right text-muted">Order total:</div>
                        <div class="col-5"><strong>${{ totalPrice }}</strong></div>
                    </div>
                    <hr class="hr-sm">
                    <div class="row text-lg">
                        <div class="col-7 text-right text-muted">Total:</div>
                        <div class="col-5"><strong>${{ totalPrice }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
        <a href="<?php echo base_url(); ?>menu/<?php echo $restaurant_slug; ?>/checkout" class="panel-cart-action btn btn-secondary btn-block btn-lg"><span>Go to checkout</span></a>
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
                        <h6 class="mb-0">{{ formData.food_name }}</h6>
                        <input type="hidden" v-model="formData.tag_ids">
                        <span class="text-muted">{{ formData.tag_names }}</span>
                    </div>
                    <div class="col-3 text-lg text-right">${{ formData.food_lowest_price }}</div>
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
                            <div 
                                v-if="formData.food_prices.length > 0"
                                v-for="(food_price, fp_key) in formData.food_prices" 
                                :key="fp_key"
                                class="form-check">
                                <label>
                                    <input v-model="formData.food_price_id"
                                        :value="food_price.food_price_id" 
                                        type="radio">
                                    <span class="label-text">{{ food_price.food_size.food_size_name }} - {{ food_price.food_weight }}g (${{ food_price.food_price }})</span>
                                </label>
                            </div>
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
                                    <div 
                                        v-if="aditionals.length > 0"
                                        v-for="(aditional, a_key) in aditionals" 
                                        :key="a_key"
                                        class="form-check">
                                        <label>
                                            <input 
                                                type="checkbox" 
                                                class="food-aditional-id"
                                                v-model="formData.food_aditional_ids"
                                                :value="aditional.food_aditional_id">
                                            <span class="label-text">{{ aditional.food_aditional_name }}(${{ aditional.food_aditional_price }})</span>
                                        </label>
                                    </div>  
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
                        <textarea v-model="formData.order_description" cols="30" rows="4" class="form-control" placeholder="Put this any other informations..."></textarea>
                    </div>
                </div>
            </div>
            <button type="button" @click="addToCart" class="add-cart modal-btn btn btn-secondary btn-block btn-lg"><span>Add to Cart</span></button>
        </div>
    </div>
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
<script src="<?php echo base_url(); ?>assets/menu/plugins/skrollr/dist/skrollr.min.js"></script>
<script src="<?php echo base_url(); ?>assets/menu/plugins/animsition/dist/js/animsition.min.js"></script>

<!-- JS Core -->
<script src="<?php echo base_url(); ?>assets/menu/js/core.js"></script>

<script src="<?php echo base_url(); ?>assets/vue/vue.js"></script>
<script src="<?php echo base_url(); ?>assets/vue/axios.js"></script>
<script src="<?php echo base_url(); ?>assets/vue/sweetalert2.js"></script>
<script src="<?php echo base_url(); ?>assets/vue/components/menu/web.js"></script>

</body>

</html>
