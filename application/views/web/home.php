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