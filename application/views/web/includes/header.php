<style>

.header-container {
    display: flex;
    justify-content: space-between;
}

.logo_area,
.header-center,
.header-right{
    flex: 1;
}



</style>
<header id="masthead" class="ui page site-header">
    <div class="primary-header">
        <div class="container header-container">
            <div class="logo_area fleft">
                <a href="<?php echo base_url(); ?>" rel="home">
                    <img src="<?php echo base_url(); ?>assets/web/images/logo.png" alt="Site Logo">
                </a>
            </div>
            <div class="header-center">
                <form id="header-search">
                    <div class="header-search-input ui search large action left icon input">
                        <input class="prompt" placeholder="Search stores for coupons, deals ..." type="text">
                        <i class="search icon"></i>
                        <div class="header-search-submit ui button">Search</div>
                        <div class="results"></div>
                    </div>
                    <div class="clear"></div>
                </form>
            </div>
            <div class="header-right">
                <a href="<?php echo base_url(); ?>web/restaurants" style="margin-top: 6px;" class="btn btn-default fright">Store</a>
            </div>
        </div>
    </div> <!-- END .header -->
</header><!-- END #masthead -->