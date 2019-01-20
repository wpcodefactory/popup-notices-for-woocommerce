function ttt_onElementInserted(containerSelector, selector,childSelector, callback) {
    if ("MutationObserver" in window) {
        var onMutationsObserved = function (mutations) {
            mutations.forEach(function (mutation) {
                if (mutation.addedNodes.length) {
                    if (jQuery(mutation.addedNodes).length) {
                        var finalSelector = selector;
                        var ownElement = jQuery(mutation.addedNodes).filter(selector);
                        if(childSelector!=''){
                            ownElement = ownElement.find(childSelector);                            
                            finalSelector = selector + ' ' + childSelector;
                        }
                        ownElement.each(function (index) {    
                            callback(jQuery(this), index + 1, ownElement.length, finalSelector);
                        });
                        if(!ownElement.length){                                                        
                            var childElements = jQuery(mutation.addedNodes).find(finalSelector);                        
                            childElements.each(function (index) {
                                callback(jQuery(this), index + 1, childElements.length, finalSelector);
                            });
                        }                        
                    }
                }
            });
        };

        var target = jQuery(containerSelector)[0];
        var config = {childList: true, subtree: true};
        var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
        var observer = new MutationObserver(onMutationsObserved);
        observer.observe(target, config);
    } else {
        console.log('No MutationObserver');
    }
}

function ttt_getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

var ttt_pnwc = {
    messages: [],
    open:false,
    init: function () {
        this.initializePopup();
        if (ttt_pnwc_info.ajax_opt === 'yes') {
            ttt_onElementInserted('body', '.woocommerce-error','li', ttt_pnwc.readNotice);
            ttt_onElementInserted('body', '.woocommerce-message','', ttt_pnwc.readNotice);
            ttt_onElementInserted('body', '.woocommerce-info','', ttt_pnwc.readNotice);
        }

		//ttt_pnwc.checkExistingElements('.woo-wallet-transactions-items li');
        ttt_pnwc.checkExistingElements('.woocommerce-error li');
        ttt_pnwc.checkExistingElements('.woocommerce-message');
        ttt_pnwc.checkExistingElements('.woocommerce-info');
    },
    open_popup_by_query_string:function(query_parameter){
        query_parameter = query_parameter || 'ttt_pnwc';
        var query_string_value = ttt_getParameterByName(query_parameter);
        if (query_string_value !== null && query_string_value !== '') {
            ttt_pnwc.clearPopupMessages();
            ttt_pnwc.messages.push({message: 'Customize Popup Notices style easily!', type: 'success'});
            ttt_pnwc.messages.push({message: 'Please take a look at an error message', type: 'error'});
            ttt_pnwc.messages.push({message: 'And a default one too', type: 'info'});
            ttt_pnwc.addMessagesToPopup();
            ttt_pnwc.openPopup();
        }
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
    removeDuplicatedMessages:function(){
       var obj = {};
       for ( var i=0, len=ttt_pnwc.messages.length; i < len; i++ )
           obj[ttt_pnwc.messages[i]['message']] = ttt_pnwc.messages[i];

       ttt_pnwc.messages = new Array();
       for ( var key in obj )
           ttt_pnwc.messages.push(obj[key]);
    },
    storeMessage: function (notice, type) {
        ttt_pnwc.messages.push({message: notice.html(), type: type});
        ttt_pnwc.removeDuplicatedMessages();        
    },
    getAdditionalIconClass:function(noticeType){
        var iconClass="";
        switch (noticeType){
            case "success":
				iconClass = ttt_pnwc_info.success_icon_class;
                break;
			case "error":
				iconClass = ttt_pnwc_info.error_icon_class;
				break;
			case "info":
				iconClass = ttt_pnwc_info.info_icon_class;
				break;
        }
        if(iconClass==""){
			iconClass+=" "+ttt_pnwc_info.icon_default_class;
        }
        return iconClass;
    },
    addMessagesToPopup: function (notice) {
        jQuery.each(ttt_pnwc.messages, function (index, value) {
            var additional_icon_class=ttt_pnwc.getAdditionalIconClass(value.type);
            jQuery('#ttt-pnwc-notice .ttt-pnwc-content').append("<div class='ttt-pnwc-notice "+value.type+"'><i class='ttt-pnwc-notice-icon " + additional_icon_class + "'></i><div class='ttt-pnwc-message'>" + value.message + "</div></div>");
        });
    },
    initializePopup: function () {
        MicroModal.init({
            awaitCloseAnimation: true,
        });
    },
    openPopup: function () {
        if(!ttt_pnwc.open){
			ttt_pnwc.open=true;
			MicroModal.show('ttt-pnwc-notice', {
				awaitCloseAnimation: true,
				onClose: function (modal) {
					ttt_pnwc.open=false;
					ttt_pnwc.clearMessages();
				}
			});
        }

    }
};
document.addEventListener('DOMContentLoaded', function () {
    ttt_pnwc.init();
    ttt_pnwc.open_popup_by_query_string();
    jQuery('body').trigger({
        type: 'ttt_pnwc',
        obj: ttt_pnwc
    });
});