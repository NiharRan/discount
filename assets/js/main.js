$(function () {


    var btnTogglerMobile = '.btn-toggler-mobile';
    var navbarWrapper = '.navbar-wrapper';
    $(btnTogglerMobile).click(function () {
        if ($(navbarWrapper).hasClass('active')) {
            $(navbarWrapper).removeClass('active');
        } else {
            $(navbarWrapper).addClass('active');
        }
    });

    var body = 'body';
    var mainContent = '.main-content';
    var btnToggler = '.btn-toggler';
    var sidebar = '#sidenav-main';
    var linkText = '.nav-link-text';
    $(btnToggler).click(function () {
        if ($(body).hasClass('navbar-locked')) {
            $(mainContent).removeClass('expended-main-content');
            $(body).removeClass('navbar-locked');
            $(sidebar).removeClass('reduced-navbar');
            $(linkText).css('display', 'inline-block');
        } else {
            $(mainContent).addClass('expended-main-content');
            $(body).addClass('navbar-locked');
            $(sidebar).addClass('reduced-navbar');
            $(linkText).css('display', 'none');
        }
    });
    $(sidebar).mouseenter(function () {
        if ($(body).hasClass('navbar-locked')) {
            $(this).removeClass('reduced-navbar');
            $(linkText).css('display', 'inline-block');
        }
    }).mouseleave(function () {
        if ($(body).hasClass('navbar-locked')) {
            $(this).addClass('reduced-navbar');
            $(linkText).css('display', 'none');

        }
    });
});