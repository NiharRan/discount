<div id="primary" class="content-area" style="width: 100%;">
    <main id="main" class="site-main">
        <div class="content-box shadow-box">
            <section class="browse-store stackable ui grid">
                <div class="four wide column store-listing-left">
                    <div class="ui fluid vertical menu">
                        <div class="item">
                            <div class="ui icon input">
                                <input placeholder="Search store..." type="text">
                                <i class="search icon"></i>
                            </div>
                        </div>
                        <?php
                        $totalRes = 0;
                        if (count($total) > 0) {
                            foreach ($total as $key => $value) {
                                $totalRes += $value;
                            }
                        }
                        ?>
                        <a class="active item"><div class="ui mini label"><?php echo $totalRes; ?></div>Popular Stores</a>
                        <?php
                        if (count($total) > 0) {
                            foreach ($total as $key => $value) { ?>
                            <a href="<?php echo base_url(); ?>web/restaurants?key=<?php echo $key; ?>" class="item"><div class="ui mini label"><?php echo $value; ?></div><?php echo $key; ?></a>
                        <?php }
                        }
                        ?>
                    </div>
                </div>
                <div class="twelve wide column">
                    <div class="store-listing">

                        <div class="store-listing-box store-popular-listing">
                            <div class="store-letter-heading">
                                <h2 class="section-heading">Featured Stores</h2>
                            </div>
                            <div class="store-letter-content">
                                <div class="ui four column grid">
                                    <?php
                                    if (count($featureRestaurants) > 0) {
                                        foreach ($featureRestaurants as $restaurant) { ?>
                                        <div class="column">
                                            <div class="store-thumb">
                                                <a href="<?php echo base_url(); ?>web/restaurant/<?php echo $restaurant['restaurant_slug']; ?>">
                                                    <img src="<?php echo base_url(); ?>uploads/restaurant/restaurant-<?php echo $restaurant['restaurant_id'];?>/<?php echo $restaurant['restaurant_logo']; ?>" alt="<?php echo $restaurant['restaurant_name']; ?>">
                                                </a>
                                            </div>
                                        </div>
                                    <?php }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <?php
                        if (count($restaurants) > 0) {
                            foreach ($restaurants as $key => $value) { ?>
                                <div class="store-a store-listing-box">
                                    <div class="store-letter-heading">
                                        <h2 class="section-heading">Stores - <?php echo $key; ?></h2>
                                    </div>
                                    <div class="store-letter-content">
                                        <ul class="clearfix">
                                            <?php
                                            if (count($value) > 0) {
                                                foreach ($value as $restaurant) {
                                                    echo '<li><a href="'.base_url().'menu/'.$restaurant['restaurant_slug'].'">'.$restaurant['restaurant_name'].'</a></li>';
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                        <?php }
                        }
                        ?>

                    </div>
                </div>
            </section>

        </div>

    </main><!-- #main -->
</div><!-- #primary -->