
<section id="popular-stores-wrapper" class="wpb_content_element">
    <h2 class="section-heading">Featured Restaurants</h2>
    <div class="popular-stores stores-thumbs shadow-box">
        <div class="store-carousel">
            <?php
            if (count($restaurants) > 0) {
                foreach ($restaurants as $restaurant) { ?>
                <div class="column">
                    <div class="store-thumb">
                        <a href="<?php echo base_url(); ?>web/restaurant/<?php echo $restaurant['restaurant_slug']; ?>">
                            <img src="<?php echo base_url(); ?>uploads/restaurant/restaurant-<?php echo $restaurant['restaurant_id'];?>/<?php echo $restaurant['restaurant_logo']; ?>" alt="<?php echo $restaurant['restaurant_name']; ?>">
                        </a>
                    </div>
                    <div class="store-name"><a href="<?php echo base_url(); ?>web/restaurant/<?php echo $restaurant['restaurant_slug']; ?>"><?php echo $restaurant['restaurant_name']; ?></a></div>
                </div>
            <?php }
            }
            ?>
            
        </div>
    </div>
</section>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery(".store-carousel").owlCarousel({
            navigation : true, // Show next and prev buttons
            navigationText : ['<img src="<?php echo base_url(); ?>assets/web/images/arrow-left.png">','<img src="<?php echo base_url(); ?>assets/web/images/arrow-right.png">'],
            //pagination : true,
            //paginationNumbers: true,
            slideSpeed : 300,
            paginationSpeed : 400,
            rewindNav: true,
            //singleItem: false,
            autoPlay: true,
            stopOnHover: true,
            // "singleItem:true" is a shortcut for:
            items : 5,
            //itemsDesktop : 5,
            itemsDesktopSmall : 4,
            //itemsTablet: 3,
            itemsMobile : 2
        });
    });
</script>