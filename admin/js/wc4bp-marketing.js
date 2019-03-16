 function wc4bp_marketing() {
	var container;
    return {
         init: function () {
    		container = jQuery('#wc4bp-notice');
            if (container.length > 0 && container.find('a.review-notice-dismiss').length > 0) {
                this.attachEvents();
            }
        },
        attachEvents: function () {
            container.find('a.review-notice-dismiss').each(function () {
                var button = jQuery(this);
                button.click(function () {
                    var item = jQuery(this);
                    container.hide();
                });
            });
        }
    }
}

var wc4bp_marketing_action = wc4bp_marketing();
jQuery(document).ready(function () {
	wc4bp_marketing_action.init();
});
