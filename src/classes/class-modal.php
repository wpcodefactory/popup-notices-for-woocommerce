<?php
/**
 * Pretty WooCommerce Notices (TTT) - Modal
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Thanks to IT
 */

namespace ThanksToIT\PWCN;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'ThanksToIT\PWCN\Modal' ) ) {

	class Modal {
		//public $notices = array();

		public function init() {
			add_action( 'wp_footer', array( $this, 'add_modal_html' ) );
			add_action( 'wp_footer', array( $this, 'add_modal_main_script' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'add_scripts' ) );
			add_action( 'wp_head', array( $this, 'add_modal_style' ) );
		}

		public function add_modal_main_script() {
			?>
            <script>
                function ttt_onElementInserted(containerSelector, selector, callback) {
                    if ("MutationObserver" in window) {
                        var onMutationsObserved = function (mutations) {
                            mutations.forEach(function (mutation) {
                                if (mutation.addedNodes.length) {
                                    if (jQuery(mutation.addedNodes).length) {
                                        var ownElement = jQuery(mutation.addedNodes).filter(selector);
                                        ownElement.each(function (index) {
                                            callback(jQuery(this), index + 1, ownElement.length, selector);
                                        });
                                        var childElements = jQuery(mutation.addedNodes).find(selector);
                                        childElements.each(function (index) {
                                            callback(jQuery(this), index + 1, childElements.length, selector);
                                        });
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

                var ttt_pwcn = {
                    messages: [],
                    init: function () {
                        this.initializePopup();
                        ttt_onElementInserted('body', '.woocommerce-error li', ttt_pwcn.readNotice);
                        ttt_onElementInserted('body', '.woocommerce-message', ttt_pwcn.readNotice);
                        ttt_onElementInserted('body', '.woocommerce-info', ttt_pwcn.readNotice);

                        ttt_pwcn.checkExistingElements('.woocommerce-error li');
                        ttt_pwcn.checkExistingElements('.woocommerce-message');
                        ttt_pwcn.checkExistingElements('.woocommerce-info');

                    },
                    checkExistingElements: function (selector) {
                        var element = jQuery(selector);
                        if (element.length) {
                            element.each(function (index) {
                                ttt_pwcn.readNotice(jQuery(this), index + 1, element.length, selector);
                            });
                        }
                    },
                    readNotice: function (element, index, total, selector) {
                        var noticeType = 'message';
                        if (selector.indexOf('error') > -1) {
                            noticeType = 'error';
                        } else if (selector.indexOf('info') > -1) {
                            noticeType = 'info';
                        }

                        if (index <= total) {
                            ttt_pwcn.storeMessage(element, noticeType);
                        }
                        if (index == total) {
                            ttt_pwcn.clearPopupMessages();
                            ttt_pwcn.addMessagesToPopup();
                            ttt_pwcn.openPopup(element);
                        }
                    },
                    clearPopupMessages: function () {
                        jQuery('#ttt-pwcn-notice').find('.modal__content').empty();
                    },
                    clearMessages: function () {
                        ttt_pwcn.messages = [];
                    },
                    storeMessage: function (notice, type) {
                        //ttt_pwcn.messages.push(notice.html());
                        ttt_pwcn.messages.push({message: notice.html(), type: type});
                    },
                    addMessagesToPopup: function (notice) {
                        jQuery.each(ttt_pwcn.messages, function (index, value) {
                            //jQuery('#ttt-pwcn-notice .modal__content').append("<div class='ttt-pwcn-notice'>" + value + "</div>");
                            jQuery('#ttt-pwcn-notice .modal__content').append("<div class='ttt-pwcn-notice "+value.type+"'><i class='ttt-pwcn-notice-icon'></i>" + value.message + "</div>");
                        });
                    },
                    initializePopup: function () {
                        MicroModal.init({
                            awaitCloseAnimation: true,
                        });
                    },
                    openPopup: function () {
                        MicroModal.show('ttt-pwcn-notice', {
                            awaitCloseAnimation: true,
                            onClose: function (modal) {
                                ttt_pwcn.clearMessages();
                            }
                        });
                    }
                };
                document.addEventListener('DOMContentLoaded', function () {
                    ttt_pwcn.init();
                });
            </script>
			<?php
		}

		public function add_modal_style() {
			?>
            <style>
                .ttt-pwcn-notice i{
                    font-style: normal;
                }

                .ttt-pwcn-notice {
                    /*border-bottom: 1px solid #ccc;*/
                }

                .ttt-pwcn-notice * {
                    margin: 0 10px;
                }

                .ttt-pwcn-notice-icon{
                    display:inline-block;
                    font-size:29px;
                    margin-right:10px;
                }

                .ttt-pwcn-notice-icon:before{
                    content:"\2714";
                }

                .ttt-pwcn-notice.error .ttt-pwcn-notice-icon:before {
                    content: "\26A0";
                }

                .ttt-pwcn-notice.info .ttt-pwcn-notice-icon:before{
                    content:"\27B2";
                }

                .ttt-pwcn-notice {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    /*justify-content: space-between;*/
                    padding: 15px 10px;
                    background: #f7f7f7;
                    line-height: 15px;
                }

                .ttt-pwcn-notice:nth-child(even) {
                    background: #eee;
                }

                .ttt-pwcn-notice .button {
                    /*margin-left: auto;*/
                    display: block;
                    order: 2;
                    margin-left: 15px;
                }

                .ttt-pwcn-notice:last-child {
                    border: none;
                }

                .modal *, .modal *:focus {
                    outline: none;
                }

                .modal__overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.6);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 999999;
                }

                .modal__container {
                    background-color: #fff;
                    padding: 25px;
                    min-width: 450px;
                    max-width: 700px;
                    max-height: 100vh;
                    border-radius: 4px;
                    box-sizing: border-box;
                }

                @media (max-width: 600px) {
                    .modal__container {
                        min-width: 75%;
                        max-width: 75%;
                    }
                }

                .modal__header {
                    text-align: right;
                }

                .modal__title {
                    margin-top: 0;
                    margin-bottom: 0;
                    font-weight: 600;
                    font-size: 1.25rem;
                    line-height: 1.25;
                    color: #00449e;
                    box-sizing: border-box;
                }

                .modal__close {
                    background: transparent;
                    background-color: #eee;
                    border: 0;
                    right: -23px;
                    top: -23px;
                    display: block;
                    position: absolute;
                    border-radius: 5px;
                    width: 45px;
                    padding: 0;
                    height: 45px;
                    font-size: 20px;
                }

                .modal__header .modal__close:before {
                    content: "\2716";
                }

                .modal__content {

                }

                .modal__btn {
                    font-size: .875rem;
                    padding-left: 1rem;
                    padding-right: 1rem;
                    padding-top: .5rem;
                    padding-bottom: .5rem;
                    background-color: #e6e6e6;
                    color: rgba(0, 0, 0, .8);
                    border-radius: .25rem;
                    border-style: none;
                    border-width: 0;
                    cursor: pointer;
                    -webkit-appearance: button;
                    text-transform: none;
                    overflow: visible;
                    line-height: 1.15;
                    margin: 0;
                    will-change: transform;
                    -moz-osx-font-smoothing: grayscale;
                    -webkit-backface-visibility: hidden;
                    backface-visibility: hidden;
                    -webkit-transform: translateZ(0);
                    transform: translateZ(0);
                    transition: -webkit-transform .25s ease-out;
                    transition: transform .25s ease-out;
                    transition: transform .25s ease-out, -webkit-transform .25s ease-out;
                }

                .modal__btn:focus, .modal__btn:hover {
                    -webkit-transform: scale(1.05);
                    transform: scale(1.05);
                }

                .modal__btn-primary {
                    background-color: #00449e;
                    color: #fff;
                }

                /**************************\
				  Demo Animation Style
				\**************************/
                @keyframes mmfadeIn {
                    from {
                        opacity: 0;
                    }
                    to {
                        opacity: 1;
                    }
                }

                @keyframes mmfadeOut {
                    from {
                        opacity: 1;
                    }
                    to {
                        opacity: 0;
                    }
                }

                @keyframes mmslideIn {
                    from {
                        transform: translateY(-30%);
                    }
                }

                @keyframes mmslideOut {
                    to {
                        transform: translateY(-30%);
                    }
                }

                .micromodal-slide {
                    display: none;
                }

                .micromodal-slide.is-open {
                    display: block;
                }

                .micromodal-slide[aria-hidden="false"] .modal__overlay {
                    animation: mmfadeIn .3s cubic-bezier(0.0, 0.0, 0.2, 1);
                }

                .micromodal-slide[aria-hidden="false"] .modal__container {
                    animation: mmslideIn .3s cubic-bezier(0, 0, .2, 1);
                }

                .micromodal-slide[aria-hidden="true"] .modal__overlay {
                    animation: mmfadeOut .3s cubic-bezier(0.0, 0.0, 0.2, 1);
                }

                .micromodal-slide[aria-hidden="true"] .modal__container {
                    animation: mmslideOut .3s cubic-bezier(0, 0, .2, 1);
                }

                .micromodal-slide .modal__container,
                .micromodal-slide .modal__overlay {
                    will-change: transform;
                }
            </style>
			<?php
		}

		public function add_scripts() {
			$plugin = \ThanksToIT\PWCN\Core::instance();
			$path   = $plugin->plugin_info['path'];
			wp_enqueue_script( 'ttt_pwcn_micromodal', 'https://unpkg.com/micromodal/dist/micromodal.min.js' );
		}

		public function add_modal_html() {
			?>
            <div class="modal micromodal-slide" id="ttt-pwcn-notice" aria-hidden="true">
                <div class="modal__overlay" tabindex="-1" data-micromodal-close>
                    <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                        <div class="modal__wrapper">
                            <header class="modal__header">
                                <!--<h2 class="modal__title" id="modal-1-title">
                                    Micromodal
                                </h2>-->
                                <button class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                            </header>
                            <main class="modal__content" id="modal-1-content" data-content="true">
								<?php //echo $this->format_notices( $this->notices ); ?>
                            </main>
                        </div>
                    </div>
                </div>
            </div>
			<?php
		}
	}
}