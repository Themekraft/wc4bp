jQuery(document).ready(function (jQuery) {

    var goPro = jQuery('a[href="admin.php?page=wc4bp_bundle_screen"]');
    goPro.css('color', '#fca300' );
    goPro.parent().insertAfter('#toplevel_page_wc4bp-options-page > ul > li:last-child');

    jQuery( ".bundle-list-see-more" ).click(function() {
        jQuery(".show-more-tk").animate({
            height: "1200"
        });
        jQuery(".tk-bundle-2").height(670);
        jQuery(".separator, .bundle-list-see-more").hide();
    });

    jQuery('#purchase').on('click', function (e) {

        var handler = FS.Checkout.configure({
            plugin_id:  '425',
            plan_id:    '597',
            public_key: 'pk_71d28f28e3e545100e9f859cf8554',
            image:      '//s3-us-west-2.amazonaws.com/freemius/plugins/425/icons/385c4cd2901519c7a70da2fb9497525e.png'
        });
        
        handler.open({
            name     : 'WooBuddy Professional',
            licenses : jQuery('#licenses-1').val(),
            purchaseCompleted  : function (response) {
            },
            success  : function (response) {
            }
        });
        e.preventDefault();
    });

    jQuery('#purchase-2').on('click', function (e) {

        var handler = FS.Checkout.configure({
            plugin_id:  '8055',
            plan_id:    '13219',
            public_key: 'pk_f87ef30cf437e0452c20e2da5444b',
            image:      '//s3-us-west-2.amazonaws.com/freemius/plugins/425/icons/385c4cd2901519c7a70da2fb9497525e.png'
        });
        
        handler.open({
            name     : 'WooBuddy Bundle',
            licenses : jQuery('#licenses-2').val(),
            purchaseCompleted  : function (response) {
            },
            success  : function (response) {
            }
        });
        e.preventDefault();
    });

    jQuery('#purchase-3').on('click', function (e) {

        var handler = FS.Checkout.configure({
            plugin_id:  '2046',
            plan_id:    '4316',
            public_key: 'pk_ee958df753d34648b465568a836aa',
            image:      '//s3-us-west-2.amazonaws.com/freemius/plugins/2046/icons/2921156b0159ff6ef809b152449e6aa9.jpg'
        });
        
        handler.open({
            name     : 'ThemeKraft Bundle',
            licenses : jQuery('#licenses-3').val(),
            purchaseCompleted  : function (response) {
            },
            success  : function (response) {
            }
        });
        e.preventDefault();
    });

    jQuery("select#licenses-1").change(function () {
        var selectedCountry = jQuery(this).children("option:selected").val();
        if( selectedCountry == '1'){
            jQuery('.fs-bundle-price-1').text('39.99');
            jQuery('#savings-price').text('79.99');
        }
        if( selectedCountry == '5'){
            jQuery('.fs-bundle-price-1').text('69.99');
            jQuery('#savings-price').text('199.95');
        }
        if( selectedCountry == 'unlimited'){
            jQuery('.fs-bundle-price-1').text('79.99');
            jQuery('#savings-price').text('219.99');
        }
    });

    jQuery("select#licenses-2").change(function () {
        var selectedCountry = jQuery(this).children("option:selected").val();
        if( selectedCountry == '1'){
            jQuery('.fs-bundle-price-2').text('79.99');
            jQuery('#savings-price-2').text('119.98');
        }
        if( selectedCountry == '5'){
            jQuery('.fs-bundle-price-2').text('89.99');
            jQuery('#savings-price-2').text('342.98');
        }
        if( selectedCountry == 'unlimited'){
            jQuery('.fs-bundle-price-2').text('99.99');
            jQuery('#savings-price-2').text('419.98');
        }
    });

    jQuery("select#licenses-3").change(function () {
        var selectedCountry = jQuery(this).children("option:selected").val();
        if( selectedCountry == '1'){
            jQuery('.fs-bundle-price-3').text('99.99');
            jQuery('#savings-price-3').text('602.75');
        }
        if( selectedCountry == '5'){
            jQuery('.fs-bundle-price-3').text('119.99');
            jQuery('#savings-price-3').text('965.75');
        }
        if( selectedCountry == 'unlimited'){
            jQuery('.fs-bundle-price-3').text('129.99');
            jQuery('#savings-price-3').text('1168.76');
        }
    });

});