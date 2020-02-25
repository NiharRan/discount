<section class="list-categories-wrapper wpb_content_element">
    <h2 class="section-heading">Most viewed categories</h2>
    <div class="list-categories shadow-box">
        <ul class="clearfix">
            <?php
            
            if (count($tags) > 0) {
                foreach ($tags as $tag) {
                    echo '<li><a href="'.base_url().'web/category/'.$tag['tag_slug'].'"><span class="coupon-count">28</span>'.$tag['tag_name'].'</a></li>';
                }
            }
            
            ?>
        </ul>
    </div>
</section>