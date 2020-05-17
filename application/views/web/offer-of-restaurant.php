<?php
    $restaurantLogo = $restaurant['restaurant_logo'];
    $restaurantLogoURL = base_url().'uploads/restaurant/restaurant-'.$restaurant['restaurant_id'].'/'.$restaurantLogo;
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <section id="store-listings-wrapper" class="wpb_content_element">
            <div style="background-image: url(<?php echo $restaurantLogoURL; ?>)" class="heading-wrapper">
                <h2 style="padding: 100px;" class="section-heading"><span>Latest Offers of <?php echo $restaurant['restaurant_name']; ?></span></h2>
            </div>
            <div class="store-listings">
                <?php
                /**
                 * display offer details in home page
                 * first check offers exists or not
                 */
                if (count($offers['data']) > 0) {
                    foreach ($offers['data'] as $key => $offer) { 
                         // create offer image url
                         $offerImage = $offer['offer_image_thumb'];
                         $offerImageURL = base_url().'uploads/offer/offer-'.$offer['offer_id'].'/'.$offerImage;

                        // offer description
                        $description = strip_tags($offer['offer_description']);

                        // offer url
                        $offerURL =  base_url().'web/offer/'.$offer['offer_slug']
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