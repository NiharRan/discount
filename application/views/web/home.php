<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <section id="store-listings-wrapper" class="wpb_content_element">
            <h2 class="section-heading">Latest Coupon Codes and Deals</h2>
            <div class="store-listings">
                <?php
                /**
                 * display offer details in home page
                 * first check offers exists or not
                 */
                if (count($offers['data']) > 0) {
                    foreach ($offers['data'] as $key => $offer) { 
                        // create restaurant logo url
                        $restaurantLogo = $offer['restaurant']['restaurant_logo_thumb'];
                        $restaurantLogoURL = base_url().'uploads/restaurant/restaurant-'.$offer['restaurant']['restaurant_id'].'/'.$restaurantLogo;

                        // create offer image url
                        $offerImage = $offer['offer_image_thumb'];
                        $offerImageURL = base_url().'uploads/offer/offer-'.$offer['offer_id'].'/'.$offerImage;

                        // offer description
                        $description = strip_tags($offer['offer_description']);

                        // offer url
                        $offerURL =  base_url().'web/offer/'.$offer['offer_slug'];
                        $restaurantURL =  base_url().'web/restaurant/'.$offer['restaurant']['restaurant_slug'];
                    ?>

                <div class="store-listing-item shadow-box">
                    <div class="store-thumb-link">
                        <div class="store-thumb">
                            <a href="<?php echo $offerURL; ?>">
                                <img src="<?php echo $offerImageURL; ?>" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="latest-coupon">
                        <h3 class="coupon-title clearfix">
                            <a href="<?php echo $offerURL; ?>" style="float: left;"><?php echo $offer['offer_name']; ?></a>
                            <a href="#" class="coupon-discount"><?php echo $offer['offer_discount']; ?>% OFF</a>
                        </h3>
                        <div class="coupon-des">
                            <?php echo substr(preg_replace("/\s|&nbsp;/", ' ', $description), 0, 260); ?> 
                            <?php if(strlen($description) > 260) echo '<span class="des-more">...'; ?></span>
                            <a style="margin-left: 10px;" href="<?php echo $offerURL; ?>">More<i class="angle down icon"></i></a>
                            <a class="clearfix restaurant-btn" href="<?php echo $restaurantURL; ?>"><img style="width: 45px;height:45px;" src="<?php echo $restaurantLogoURL; ?>" alt=""> <span><?php echo $offer['restaurant']['restaurant_name']; ?></span></a>
                        </div>
                    </div>
                    <div class="coupon-right">
                        <div class="qucode-wrapper">
                            <div id="qrcode<?php echo $key; ?>"></div>
                            <script>
                                new QRCode(document.getElementById("qrcode<?php echo $key; ?>"), "<?php echo $offer['offer_barcode']; ?>");
                            </script>
                            <div class="exp-text">Expires <?php echo date("d/m/Y", strtotime($offer['offer_end'])); ?> </div>
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