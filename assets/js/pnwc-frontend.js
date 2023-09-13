/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/js/modules/general.js":
/*!***********************************!*\
  !*** ./src/js/modules/general.js ***!
  \***********************************/
/***/ ((module) => {

var general = {
  messages: [],
  open: false,
  sounds: {},
  init: function init() {
    general.open_popup_by_query_string();
    //Handle Sounds
    this.handle_sounds();
    this.initializePopup();
    if (ttt_pnwc_info.ajax_opt === 'yes') {
      general.onElementInserted('body', '.woocommerce-error', 'li', general.readNotice);
      general.onElementInserted('body', '.woocommerce-message', '', general.readNotice);
      general.onElementInserted('body', '.woocommerce-info', '', general.readNotice);
    }
    general.checkExistingElements('.woocommerce-error li');
    general.checkExistingElements('.woocommerce-message');
    general.checkExistingElements('.woocommerce-info');
    document.addEventListener('click', function (event) {
      if (event.target.matches('.enable-terms-checkbox')) {
        event.preventDefault();
        general.enable_terms_checkbox();
      }
    }, false);
    document.addEventListener('click', this.handle_click_inside_close, true);
    jQuery('body').trigger({
      type: 'ttt_pnwc',
      obj: this
    });
  },
  onElementInserted: function onElementInserted(containerSelector, selector, childSelector, callback) {
    if ("MutationObserver" in window) {
      var onMutationsObserved = function onMutationsObserved(mutations) {
        mutations.forEach(function (mutation) {
          if (mutation.addedNodes.length) {
            if (jQuery(mutation.addedNodes).length) {
              var finalSelector = selector;
              var ownElement = jQuery(mutation.addedNodes).filter(selector);
              if (childSelector != '') {
                ownElement = ownElement.find(childSelector);
                finalSelector = selector + ' ' + childSelector;
              }
              ownElement.each(function (index) {
                callback(jQuery(this), index + 1, ownElement.length, finalSelector, true);
              });
              if (!ownElement.length) {
                var childElements = jQuery(mutation.addedNodes).find(finalSelector);
                childElements.each(function (index) {
                  callback(jQuery(this), index + 1, childElements.length, finalSelector, true);
                });
              }
            }
          }
        });
      };
      var target = jQuery(containerSelector)[0];
      var config = {
        childList: true,
        subtree: true
      };
      var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
      var observer = new MutationObserver(onMutationsObserved);
      observer.observe(target, config);
    } else {
      console.log('No MutationObserver');
    }
  },
  getParameterByName: function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
      results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
  },
  handle_click_inside_close: function handle_click_inside_close(e) {
    if (ttt_pnwc_info.click_inside_close == 'yes' && e.target.matches('a,button')) {
      var modal = e.target.closest(".ttt-pnwc-modal");
      if (modal) {
        MicroModal.close('ttt-pnwc-notice');
      }
    }
  },
  enable_terms_checkbox: function enable_terms_checkbox() {
    document.getElementById("terms").checked = 'checked';
  },
  handle_sounds: function handle_sounds() {
    if (ttt_pnwc_info.audio.enabled === 'yes') {
      //http://freesound.org/data/previews/220/220170_4100837-lq.mp3

      if (ttt_pnwc_info.audio.hasOwnProperty('opening') && ttt_pnwc_info.audio.opening !== '') {
        general.sounds.opening = new Audio(ttt_pnwc_info.audio.opening);
      }
      if (ttt_pnwc_info.audio.hasOwnProperty('closing') && ttt_pnwc_info.audio.closing !== '') {
        general.sounds.closing = new Audio(ttt_pnwc_info.audio.closing);
      }
    }
  },
  open_popup_by_query_string: function open_popup_by_query_string(query_parameter) {
    query_parameter = query_parameter || 'ttt_pnwc';
    var query_string_value = general.getParameterByName(query_parameter);
    if (query_string_value !== null && query_string_value !== '') {
      general.clearPopupMessages();
      general.messages.push({
        message: 'Customize Popup Notices style easily!',
        type: 'success'
      });
      general.messages.push({
        message: 'Please take a look at an error message',
        type: 'error'
      });
      general.messages.push({
        message: 'And a default one too',
        type: 'info'
      });
      general.messages.push({
        message: "Don't forget the <a target='_blank' href='https://en.wikipedia.org/wiki/Link'>links</a> too",
        type: 'info'
      });
      general.addMessagesToPopup();
      general.openPopup();
    }
  },
  checkExistingElements: function checkExistingElements(selector) {
    var element = jQuery(selector);
    if (element.length) {
      element.each(function (index) {
        general.readNotice(jQuery(this), index + 1, element.length, selector, false);
      });
    }
  },
  readNotice: function readNotice(element, index, total, selector, dynamic) {
    var noticeType = 'success';
    if (selector.indexOf('error') > -1) {
      noticeType = 'error';
    } else if (selector.indexOf('info') > -1) {
      noticeType = 'info';
    }
    if (ttt_pnwc_info.types[noticeType] === 'yes') {
      if (index <= total) {
        general.storeMessage(element, noticeType, dynamic);
      }
      if (index == total) {
        general.clearPopupMessages();
        general.addMessagesToPopup();
        general.openPopup(element);
      }
    }
  },
  clearPopupMessages: function clearPopupMessages() {
    jQuery('#ttt-pnwc-notice').find('.ttt-pnwc-content').empty();
  },
  clearMessages: function clearMessages() {
    general.messages = [];
  },
  removeDuplicatedMessages: function removeDuplicatedMessages() {
    var obj = {};
    for (var i = 0, len = general.messages.length; i < len; i++) obj[general.messages[i]['message']] = general.messages[i];
    general.messages = new Array();
    for (var key in obj) general.messages.push(obj[key]);
  },
  hashMessage: function hashMessage(s) {
    return s.split("").reduce(function (a, b) {
      a = (a << 5) - a + b.charCodeAt(0);
      return a & a;
    }, 0);
  },
  setCookie: function setCookie(name, value, time) {
    var expires;
    if (time) {
      var date = new Date();
      //date.setTime(date.getTime() + (time * 24 * 60 * 60 * 1000));
      date.setTime(date.getTime() + time * 60 * 60 * 1000);
      expires = "; expires=" + date.toGMTString();
    } else {
      expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
  },
  getCookie: function getCookie(c_name) {
    if (document.cookie.length > 0) {
      c_start = document.cookie.indexOf(c_name + "=");
      if (c_start != -1) {
        c_start = c_start + c_name.length + 1;
        c_end = document.cookie.indexOf(";", c_start);
        if (c_end == -1) {
          c_end = document.cookie.length;
        }
        return unescape(document.cookie.substring(c_start, c_end));
      }
    }
    return "";
  },
  isMessageValid: function isMessageValid(message, dynamic) {
    if (message.trim().length == 0) {
      return false;
    }
    // Ignored Messages
    if (ttt_pnwc_info.ignored_msg.field && ttt_pnwc_info.ignored_msg.field !== "") {
      if (ttt_pnwc_info.ignored_msg.search_method === "regex") {
        var matches = ttt_pnwc_info.ignored_msg.field.filter(function (pattern) {
          return new RegExp(pattern, ttt_pnwc_info.ignored_msg.regex_flags).test(message);
        });
        if (matches.length > 0) {
          return false;
        }
      } else if (ttt_pnwc_info.ignored_msg.search_method === "partial_comparison") {
        var matches = ttt_pnwc_info.ignored_msg.field.filter(function (string_check) {
          return message.indexOf(string_check) !== -1;
        });
        if (matches.length > 0) {
          return false;
        }
      } else if (ttt_pnwc_info.ignored_msg.search_method === "full_comparison") {
        var matches = ttt_pnwc_info.ignored_msg.field.filter(function (string_check) {
          return string_check.trim() === message.trim();
        });
        if (matches.length > 0) {
          return false;
        }
      }
    }

    // Cookie Opt
    if (ttt_pnwc_info.cookie_opt.enabled === 'yes' && (ttt_pnwc_info.cookie_opt.message_origin.search('dynamic') != -1 && dynamic || ttt_pnwc_info.cookie_opt.message_origin.search('static') != -1 && !dynamic || ttt_pnwc_info.cookie_opt.message_origin.search('all') != -1)) {
      if (general.getCookie(general.hashMessage(message))) {
        return false;
      }
    }
    return true;
  },
  saveMessageInCookie: function saveMessageInCookie(message) {
    if (ttt_pnwc_info.cookie_opt.enabled === 'yes') {
      var hashedMessage = general.hashMessage(message);
      general.setCookie(hashedMessage, hashedMessage, ttt_pnwc_info.cookie_opt.time);
    }
  },
  storeMessage: function storeMessage(notice, type, dynamic) {
    if (general.isMessageValid(notice.html(), dynamic)) {
      general.saveMessageInCookie(notice.html());
      general.messages.push({
        message: notice.html().trim(),
        type: type,
        dynamic: dynamic
      });
      general.removeDuplicatedMessages();
    }
  },
  getAdditionalIconClass: function getAdditionalIconClass(noticeType) {
    var iconClass = "";
    switch (noticeType) {
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
    if (iconClass == "") {
      iconClass += " " + ttt_pnwc_info.icon_default_class;
    }
    return iconClass;
  },
  addMessagesToPopup: function addMessagesToPopup(notice) {
    jQuery.each(general.messages, function (index, value) {
      var additional_icon_class = general.getAdditionalIconClass(value.type);
      var dynamicClass = value.dynamic ? 'ttt-dynamic' : 'ttt-static';
      jQuery('#ttt-pnwc-notice .ttt-pnwc-content').append("<div class='ttt-pnwc-notice " + value.type + ' ' + dynamicClass + " '><i class='ttt-pnwc-notice-icon " + additional_icon_class + "'></i><div class='ttt-pnwc-message'>" + value.message + "</div></div>");
    });
  },
  initializePopup: function initializePopup() {
    MicroModal.init({
      awaitCloseAnimation: true
    });
  },
  autoClose: function autoClose(modal) {
    var currentTypes = general.messages.map(function (item) {
      return item.type;
    });
    currentTypes = currentTypes.filter(function (value, index, self) {
      return self.indexOf(value) === index;
    });
    var intersection = currentTypes.filter(function (n) {
      return ttt_pnwc_info.auto_close_types.indexOf(n) !== -1;
    });
    if (ttt_pnwc_info.auto_close_types.length === 0 || intersection.length > 0) {
      if (ttt_pnwc_info.auto_close_time > 0) {
        setTimeout(function () {
          MicroModal.close(modal.id);
        }, ttt_pnwc_info.auto_close_time * 1000);
      }
    }
  },
  openPopup: function openPopup() {
    if (!general.open && general.messages.length > 0) {
      if (ttt_pnwc_info.audio.enabled === 'yes' && general.sounds.opening) {
        general.sounds.opening.play();
      }
      MicroModal.show('ttt-pnwc-notice', {
        awaitCloseAnimation: true,
        onShow: function onShow(modal) {
          general.autoClose(modal);
        },
        onClose: function onClose(modal) {
          general.open = false;
          general.clearMessages();
          if (ttt_pnwc_info.audio.enabled === 'yes' && general.sounds.closing) {
            general.sounds.closing.play();
          }
        }
      });
    }
  }
};
module.exports = general;

/***/ }),

/***/ "./src/js/modules lazy recursive ^\\.\\/.*$":
/*!********************************************************!*\
  !*** ./src/js/modules/ lazy ^\.\/.*$ namespace object ***!
  \********************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var map = {
	"./general": "./src/js/modules/general.js",
	"./general.js": "./src/js/modules/general.js"
};

function webpackAsyncContext(req) {
	return Promise.resolve().then(() => {
		if(!__webpack_require__.o(map, req)) {
			var e = new Error("Cannot find module '" + req + "'");
			e.code = 'MODULE_NOT_FOUND';
			throw e;
		}

		var id = map[req];
		return __webpack_require__.t(id, 7 | 16);
	});
}
webpackAsyncContext.keys = () => (Object.keys(map));
webpackAsyncContext.id = "./src/js/modules lazy recursive ^\\.\\/.*$";
module.exports = webpackAsyncContext;

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/create fake namespace object */
/******/ 	(() => {
/******/ 		var getProto = Object.getPrototypeOf ? (obj) => (Object.getPrototypeOf(obj)) : (obj) => (obj.__proto__);
/******/ 		var leafPrototypes;
/******/ 		// create a fake namespace object
/******/ 		// mode & 1: value is a module id, require it
/******/ 		// mode & 2: merge all properties of value into the ns
/******/ 		// mode & 4: return value when already ns object
/******/ 		// mode & 16: return value when it's Promise-like
/******/ 		// mode & 8|1: behave like require
/******/ 		__webpack_require__.t = function(value, mode) {
/******/ 			if(mode & 1) value = this(value);
/******/ 			if(mode & 8) return value;
/******/ 			if(typeof value === 'object' && value) {
/******/ 				if((mode & 4) && value.__esModule) return value;
/******/ 				if((mode & 16) && typeof value.then === 'function') return value;
/******/ 			}
/******/ 			var ns = Object.create(null);
/******/ 			__webpack_require__.r(ns);
/******/ 			var def = {};
/******/ 			leafPrototypes = leafPrototypes || [null, getProto({}), getProto([]), getProto(getProto)];
/******/ 			for(var current = mode & 2 && value; typeof current == 'object' && !~leafPrototypes.indexOf(current); current = getProto(current)) {
/******/ 				Object.getOwnPropertyNames(current).forEach((key) => (def[key] = () => (value[key])));
/******/ 			}
/******/ 			def['default'] = () => (value);
/******/ 			__webpack_require__.d(ns, def);
/******/ 			return ns;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/ensure chunk */
/******/ 	(() => {
/******/ 		// The chunk loading function for additional chunks
/******/ 		// Since all referenced chunks are already included
/******/ 		// in this file, this function is empty here.
/******/ 		__webpack_require__.e = () => (Promise.resolve());
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/global */
/******/ 	(() => {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/publicPath */
/******/ 	(() => {
/******/ 		var scriptUrl;
/******/ 		if (__webpack_require__.g.importScripts) scriptUrl = __webpack_require__.g.location + "";
/******/ 		var document = __webpack_require__.g.document;
/******/ 		if (!scriptUrl && document) {
/******/ 			if (document.currentScript)
/******/ 				scriptUrl = document.currentScript.src;
/******/ 			if (!scriptUrl) {
/******/ 				var scripts = document.getElementsByTagName("script");
/******/ 				if(scripts.length) {
/******/ 					var i = scripts.length - 1;
/******/ 					while (i > -1 && !scriptUrl) scriptUrl = scripts[i--].src;
/******/ 				}
/******/ 			}
/******/ 		}
/******/ 		// When supporting browsers where an automatic publicPath is not supported you must specify an output.publicPath manually via configuration
/******/ 		// or pass an empty string ("") and set the __webpack_public_path__ variable from your code to use your own logic.
/******/ 		if (!scriptUrl) throw new Error("Automatic publicPath is not supported in this browser");
/******/ 		scriptUrl = scriptUrl.replace(/#.*$/, "").replace(/\?.*$/, "").replace(/\/[^\/]+$/, "/");
/******/ 		__webpack_require__.p = scriptUrl + "../";
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
var __webpack_exports__ = {};
/*!********************************!*\
  !*** ./src/scss/frontend.scss ***!
  \********************************/
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin

})();

// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!****************************!*\
  !*** ./src/js/frontend.js ***!
  \****************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modules_general_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modules/general.js */ "./src/js/modules/general.js");
/* harmony import */ var _modules_general_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_modules_general_js__WEBPACK_IMPORTED_MODULE_0__);
/**
 * Popup Notices for WooCommerce.
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  WPFactory
 */

// Loads modules dynamically and asynchronously
__webpack_require__.p = ttt_pnwc_info.plugin_url + "/assets/";
var modules = ttt_pnwc_info.modulesRequired;
if (modules && modules.length) {
  modules.forEach(function (module) {
    __webpack_require__("./src/js/modules lazy recursive ^\\.\\/.*$")("./".concat(module)).then(function (component) {
      if (document.readyState !== 'loading') {
        component.init();
      } else {
        document.addEventListener('DOMContentLoaded', function () {
          component.init();
        });
      }
    });
  });
}

// Loads modules manually and synchronously

document.addEventListener('DOMContentLoaded', function () {
  _modules_general_js__WEBPACK_IMPORTED_MODULE_0___default().init();
});
})();

/******/ })()
;
//# sourceMappingURL=pnwc-frontend.js.map