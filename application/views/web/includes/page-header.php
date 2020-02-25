<section class="page-header">
    <div class="container">

        <div class="inner">
            <div class="inner-content clearfix">
                <div class="header-content">
                    <h1><?php echo $offer['offer_name']; ?></h1>
                </div>
                <div class="ui small breadcrumb">
                    <a href="<?php echo base_url(); ?>" class="section">Home</a>
                    <i class="right angle right icon divider"></i>
                    <a href="<?php echo base_url(); ?>web/restaurant/<?php echo $offer['restaurant']['restaurant_slug']; ?>" class="section"><?php echo $offer['restaurant']['restaurant_name']; ?></a>
                    <i class="right angle right icon divider"></i>
                    <div class="active section"><?php echo $offer['offer_name']; ?></div>
                </div>
            </div>
        </div>
    </div>
</section>