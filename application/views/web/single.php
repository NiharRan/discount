<div id="primary" class="content-area">
    <style>

    .post-footer, .post-body {
        margin-top: 30px;
    }
    .badge {
        font-weight: 600;
        font-size: 14px;
        padding: 2px 6px;
        margin: 2px 3px;
        border-radius: 4px;
    }
    .badge-success{
        color: #fff;
        background-color: #07ce8d;
    }
    a.badge:hover {
        color: #f2f2f2;
    }
    .badge:first-child {
        margin-left: 0px;
    }
    .coupon-right {
        float: right !important;
        max-width: 200px;
    }
    </style>
    <main id="main" class="site-main">
    <?php
        /**
         * display offer details in home page
         * first check offers exists or not
         */
        if (count($offer) > 0) {
            // create restaurant logo url
            $restaurantLogo = $offer['restaurant']['restaurant_logo'];
            $onlyName = substr($restaurantLogo, 0, strpos($restaurantLogo, '.'));
            $ext = substr($restaurantLogo, strpos($restaurantLogo, '.'), strlen($restaurantLogo) - 1);
            $path = base_url().'uploads/restaurant/restaurant-'.$offer['restaurant']['restaurant_id'];
            $restaurantLogoURL = $path.'/'.$onlyName.$ext;

            // offer description
            $description = $offer['offer_description'];

            // offer url
            $offerURL =  base_url().'web/offer/'.$offer['offer_slug']
        ?>

        
        <div class="post-entry shadow-box content-box post-1178 post type-post status-publish format-standard hentry category-markup tag-content-2 tag-css tag-formatting-2 tag-html tag-markup-2">
            <div class="post-content">
            <div class="store-listing-item shadow-box">
                    <div class="store-thumb-link">
                        <div class="discount">
                            <button class="btn btn-warning" style="float: left;"><?php echo $offer['offer_discount']; ?>% OFF</button>
                        </div>
                    </div>
                    <div class="coupon-right">
                        <div class="qucode-wrapper">
                            <div id="qrcode"></div>
                            <script>
                                new QRCode(document.getElementById("qrcode"), "<?php echo $offer['offer_barcode']; ?>");
                            </script>
                        </div>
                        <div class="coupon-detail coupon-button-type">
                            <div class="exp-text">Expires <?php echo date("d/m/Y", strtotime($offer['offer_end'])); ?> </div>
                        </div>
                    </div>
                </div>
                <div class="post-image">
                    <a href="<?php echo $offerURL; ?>">
                        <img src="<?php echo $restaurantLogoURL; ?>" alt="">
                    </a>
                </div>
                <div class="post-body">
                    <?php echo $description; ?>
                </div>
                <div class="post-footer">
                    <?php
                    if (count($offer['restaurant']['tags'] > 0)) {
                        $tags = $offer['restaurant']['tags'];
                        foreach ($tags as $tag) {
                            echo '<a href="'.base_url().'web/category/'.$tag['tag_slug'].'" class="badge badge-success">'.$tag['tag_name'].'</a>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
                
        <?php
        }
        ?>
    </main><!-- #main -->
</div><!-- #primary -->