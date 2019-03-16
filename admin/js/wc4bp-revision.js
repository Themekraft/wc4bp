function wc4bp_revision() {
    function call_for_revision(action, container) {
        if (action === 'review') {
            window.open('https://wordpress.org/support/plugin/wc4bp/reviews/', '_blank');
        }
        container.hide();
        jQuery.ajax({
            type: 'POST', url: wc4bp_admin_revision_js.ajaxurl,
            data: {
                action: 'wc4bp_revision_' + action,
                nonce: wc4bp_admin_revision_js.nonce,
                trigger: action
            }
        });
    }
    return {
        init: function () {
            if (jQuery('#wc4bp-ask-revision').length > 0 && jQuery('button.review-notice-dismiss').length > 0) {
                this.attachEvents();
            }
        },
        attachEvents: function () {
            jQuery('button.review-notice-dismiss').each(function () {
                var button = jQuery(this);
                button.click(function () {
                    var item = jQuery(this);
                    call_for_revision(item.data('action'), jQuery('#wc4bp-ask-revision'));
                });
            });
        }
    }
}

var wc4bp_revision_action = wc4bp_revision();
jQuery(document).ready(function () {
    wc4bp_revision_action.init();
});
