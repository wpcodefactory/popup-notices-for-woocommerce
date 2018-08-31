var ttt_pnwc = {
    messages: [],
    init: function () {
        this.initializePopup();
        ttt_pnwc.checkExistingElements('.woocommerce-error li');
        ttt_pnwc.checkExistingElements('.woocommerce-message');
        ttt_pnwc.checkExistingElements('.woocommerce-info');
    },
    checkExistingElements: function (selector) {
        var element = jQuery(selector);
        if (element.length) {
            element.each(function (index) {
                ttt_pnwc.readNotice(jQuery(this), index + 1, element.length, selector);
            });
        }
    },
    readNotice: function (element, index, total, selector) {
        var noticeType = 'success';

        if (selector.indexOf('error') > -1) {
            noticeType = 'error';
        } else if (selector.indexOf('info') > -1) {
            noticeType = 'info';
        }

        if (ttt_pnwc_info.types[noticeType] === 'yes') {
            if (index <= total) {
                ttt_pnwc.storeMessage(element, noticeType);
            }
            if (index == total) {
                ttt_pnwc.clearPopupMessages();
                ttt_pnwc.addMessagesToPopup();
                ttt_pnwc.openPopup(element);
            }
        }
    },
    clearPopupMessages: function () {
        jQuery('#ttt-pnwc-notice').find('.ttt-pnwc-content').empty();
    },
    clearMessages: function () {
        ttt_pnwc.messages = [];
    },
    storeMessage: function (notice, type) {
        //ttt_pnwc.messages.push(notice.html());
        ttt_pnwc.messages.push({message: notice.html(), type: type});
    },
    addMessagesToPopup: function (notice) {
        jQuery.each(ttt_pnwc.messages, function (index, value) {
            //jQuery('#ttt-pnwc-notice .ttt-pnwc-content').append("<div class='ttt-pnwc-notice'>" + value + "</div>");
            jQuery('#ttt-pnwc-notice .ttt-pnwc-content').append("<div class='ttt-pnwc-notice "+value.type+"'><i class='ttt-pnwc-notice-icon'></i>" + value.message + "</div>");
        });
    },
    initializePopup: function () {
        MicroModal.init({
            awaitCloseAnimation: true,
        });
    },
    openPopup: function () {
        MicroModal.show('ttt-pnwc-notice', {
            awaitCloseAnimation: true,
            onClose: function (modal) {
                ttt_pnwc.clearMessages();
            }
        });
    }
};
document.addEventListener('DOMContentLoaded', function () {
    ttt_pnwc.init();
    jQuery('body').trigger({
        type: 'ttt_pnwc',
        obj: ttt_pnwc
    });
});