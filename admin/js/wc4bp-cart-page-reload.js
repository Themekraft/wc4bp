jQuery( document ).ready(function() {
    jQuery(document.body).on('wc_cart_emptied', function() {
        window.location.reload();
    });

    var myAccountUrl = window.location.origin + '/my-account';
    jQuery('#wc4bp-hidden-content').load(myAccountUrl, function() {
        jQuery('#extra-content-tab').html(jQuery('#wc4bp-hidden-content .entry-content').html());
        jQuery("#extra-content-tab").on( "click", "a", function( event ) {
            event.preventDefault();
            var myAccountContentPage = jQuery(this).attr('href');
            jQuery('#extra-content-complement').load(myAccountContentPage, function() {
                jQuery('#extra-content-tab').html(jQuery('#extra-content-complement .entry-content').html());
                jQuery("#wc4bp-hidden-content, #extra-content-complement").empty();
            });
            return false; 
        });
    });
})