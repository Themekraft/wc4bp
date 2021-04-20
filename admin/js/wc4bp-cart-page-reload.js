jQuery( document ).ready(function() {
    jQuery(document.body).on('wc_cart_emptied', function() {
        window.location.reload();
    });
})