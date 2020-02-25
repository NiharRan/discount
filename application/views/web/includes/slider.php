<div id="home-slider" class="wpb_content_element">
    <div class="home-slider-wrapper shadow-box">
        <div class="slideshow home-slider">
            <div class="slideshow_item">
                <a href="#"><img src="<?php echo base_url(); ?>assets/web/thumb/slider/slider1.jpg" alt=""></a>
            </div>
            <div class="slideshow_item">
                <a href="#"><img src="<?php echo base_url(); ?>assets/web/thumb/slider/slider2.png" alt=""></a>
            </div>
        </div><!-- END .slideshow -->
    </div><!-- END .home-slideshow-wrapper -->
</div><!-- END #home-slider -->
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery(".slideshow").owlCarousel({
            navigation : true, // Show next and prev buttons
            navigationText : ['<img src="<?php echo base_url(); ?>assets/web/images/arrow-left.png">','<img src="<?php echo base_url(); ?>assets/web/images/arrow-right.png">'],
            //pagination : true,
            //paginationNumbers: true,
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem: true,
            autoPlay: true,
            stopOnHover: true
            // "singleItem:true" is a shortcut for:
            // items : 1,
            // itemsDesktop : false,
            // itemsDesktopSmall : false,
            // itemsTablet: false,
            // itemsMobile : false
        });
    });
</script>