<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <section id="store-listings-wrapper" class="wpb_content_element">
            <h2 class="section-heading">Latest Offers of <?php echo $restaurant['restaurant_name']; ?></h2>
            <div class="store-listings">
                <?php
                /**
                 * display offer details in home page
                 * first check offers exists or not
                 */
                if (count($offers['data']) > 0) {
                    foreach ($offers['data'] as $key => $offer) { 
                        // create restaurant logo url
                        $restaurantLogo = $offer['restaurant']['restaurant_logo'];
                        $onlyName = substr($restaurantLogo, 0, strpos($restaurantLogo, '.'));
                        $ext = substr($restaurantLogo, strpos($restaurantLogo, '.'), strlen($restaurantLogo) - 1);
                        $path = base_url().'uploads/restaurant/restaurant-'.$offer['restaurant']['restaurant_id'];
                        $restaurantLogoURL = $path.'/'.$onlyName.'_thumb'.$ext;

                        // offer description
                        $description = strip_tags($offer['offer_description']);

                        // offer url
                        $offerURL =  base_url().'web/offer/'.$offer['offer_slug']
                    ?>

                <div class="store-listing-item shadow-box">
                    <div class="store-thumb-link">
                        <div class="store-thumb">
                            <a href="<?php echo $offerURL; ?>">
                                <img src="<?php echo $restaurantLogoURL; ?>" alt="">
                            </a>
                        </div>
                        <div class="store-name"><a href="<?php echo $offerURL; ?>"><?php echo $offer['offer_name']; ?> <i class="angle right icon"></i></a></div>
                        <div class="discount">
                            <button class="btn btn-warning" style="float: left;"><?php echo $offer['offer_discount']; ?>% OFF</button>
                        </div>
                    </div>
                    <div class="latest-coupon">
                        <h3 class="coupon-title"><a href="<?php echo $offerURL; ?>"><?php echo $offer['offer_name']; ?></a></h3>
                        <div class="coupon-des">
                            <?php echo substr($description, 0, 200); ?> <?php if(strlen($description) > 200) echo '<span class="des-more">...'; ?> 
                            <a href="<?php echo $offerURL; ?>">More<i class="angle down icon"></i></a></span>
                        </div>
                    </div>
                    <div class="coupon-right">
                        <div class="qucode-wrapper">
                            <div id="qrcode<?php echo $key; ?>"></div>
                            <script>
                                new QRCode(document.getElementById("qrcode<?php echo $key; ?>"), "<?php echo $offer['offer_barcode']; ?>");
                            </script>
                        </div>
                        <div class="coupon-detail coupon-button-type">
                            <a href="#" class="coupon-button coupon-code" data-aff-url="http://google.com">
                                <span class="code-text"><?php echo $offer['offer_barcode']; ?></span>
                                <span class="get-code">Get Code</span>
                            </a>
                            <div class="exp-text">Expires <?php echo date("d/m/Y", strtotime($offer['offer_end'])); ?> </div>
                        </div>
                    </div>

                    <!-- Coupon Modal -->
                    <div id="coupon_id_1" class="ui modal coupon-modal coupon-code-modal">
                        <div class="coupon-header clearfix">
                            <div class="coupon-store-thumb">
                                <img src="<?php echo base_url(); ?>assets/web/thumb/stores/vientohotel.png" alt="">
                            </div>
                            <div class="coupon-title">Up To 40% Off January Savings Event</div>
                            <span class="close"></span>
                        </div>
                        <div class="coupon-content">
                            <p class="coupon-type-text">Copy this code and use at checkout</p>
                            <div class="modal-code">
                                <span class="code-text">EMIAXHGF</span>
                            </div>
                            <div class="clearfix">
                                <div class="user-ratting ui icon basic buttons">
                                    <div class="ui button icon-popup" data-offset="0" data-content="This worked" data-variation="inverted"><i class="smile icon"></i></div>
                                    <div class="ui button icon-popup" data-offset="0" data-content="It didn't work" data-variation="inverted"><i class="frown icon"></i></div>
                                    <div class="save-coupon ui button icon-popup" data-offset="0" data-content="Save this coupon" data-variation="inverted"><i class="empty star icon"></i></div>
                                </div>
                                <a href="http://google.com" target="_blank" class="ui button btn btn_secondary go-store">Go To Store<i class="angle right icon"></i></a>
                            </div>
                            <div class="clearfix">
                                <span class="user-ratting-text">Did it work?</span>
                                <span class="show-detail"><a href="#">Coupon Detail<i class="angle down icon"></i></a></span>
                            </div>
                            <div class="coupon-popup-detail">
                                <p>Enter this code at checkout to get 30% discount on all orders with Master's Charge card. Get free shipping on orders $50 or more. Restrictions may apply.  <p>
                                <p><strong>Expires</strong>: Feb 26, 2016</p>
                                <p><strong>Submitted</strong>: a week ago</p>
                            </div>
                        </div>
                        <div class="coupon-footer">
                            <ul class="clearfix">
                                <li><span><i class="wifi icon"></i> 268 People Used, 16 Today</span></li>
                                <li><a class="modal-share" href="#"><i class="share alternate icon"></i> Share</a></li>
                            </ul>
                            <div class="share-modal-popup ui popup">
                                <a class="tiny ui facebook button"><i class="facebook icon"></i>Facebook</a>
                                <a class="tiny ui twitter button"><i class="twitter icon"></i>Twitter</a>
                                <a class="tiny ui google plus button"><i class="google plus icon"></i>Google Plus</a>
                                <a class="tiny ui instagram button"><i class="instagram icon"></i>Instagram</a>
                            </div>
                        </div>
                    </div>

                </div>

                        
                <?php }
                } else {
                    
                }
                ?>
            </div>

            <div class="store-page-links">
                <?php
                    echo $offers['links'];
                ?>
            </div>						
        </section>
    </main><!-- #main -->
</div><!-- #primary -->