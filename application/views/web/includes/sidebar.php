<div id="secondary" class="widget-area sidebar">
    <aside class="widget widget_categories">
        <h4 class="widget-title">Popular Categories</h4>
        <div class="widget-content shadow-box">
            <ul>
                <?php
                if (count($popularTags) > 0) {
                    foreach ($tags as $tag) {
                        echo '<li><a href="'.base_url().'web/category/'.$tag['tag_slug'].'">'.$tag['tag_name'].'</a></li>';
                    }
                } else {
                    echo '<p>No tag found</p>';
                }
                
                ?>
            </ul>
        </div>
    </aside>
</div><!-- #secondary -->