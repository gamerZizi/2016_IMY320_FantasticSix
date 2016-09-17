jQuery(document).ready(function($) {
        
    $("blockquote").addClass("handy");
    $(".single-post .article-title h1").addClass("comein");
    $(".single-post .post-informations span").addClass("comein");
    $('.after-content .icon-printer').click(function(){
        window.print();    
    });
       
    function sticky_footer() {
        $headerHeight = $('#masthead').outerHeight();
        $footerHeight = $('#colophon').outerHeight();
        $totalHeight = $(window).height();
        $middleHeight = $totalHeight - $headerHeight - $footerHeight;
        $('#content').css({
            'min-height': $middleHeight
            })
        $sidebarHeight = $('.position-right .sidebar').outerHeight();
        $('.off-canvas-wrapper, .off-canvas-wrapper-inner, .off-canvas-content').css({
            'min-height': $sidebarHeight
            })
    }
    
    sticky_footer();
    
    $( '.site-footer .row.widgets-12 > div' ).addClass( 'medium-12' );
    $( '.site-footer .row.widgets-6 > div' ).addClass( 'medium-6' );
    $( '.site-footer .row.widgets-4 > div' ).addClass( 'medium-4' );
    $( '.site-footer .row.widgets-3 > div' ).addClass( 'medium-3' );
})