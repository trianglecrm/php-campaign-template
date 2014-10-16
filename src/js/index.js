jQuery(document).ready(function() {
    jQuery('.safe-purchase-badge' ).delay(1000).fadeIn('slow');
    jQuery('.header-alert' ).slideDown( 'slow' );
    jQuery('#exit-image').click(function() {
        jQuery('.redbar').slideDown();
        jQuery('.CS_black_overlay, .CS_div1').hide();
    });
});

