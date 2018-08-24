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

			//add_action( 'wp_ajax_nopriv_' . 'ttt_pwcn_get_ajax_notices', array( $this, 'get_ajax_notices' ) );
			//add_action( 'wp_ajax_' . 'ttt_pwcn_get_ajax_notices', array( $this, 'get_ajax_notices' ) );


			/*$notice_types = apply_filters( 'woocommerce_notice_types', array( 'error', 'success', 'notice' ) );
			foreach ( $notice_types as $type ) {
				add_filter( 'woocommerce_add_' . $type, array( $this, 'get_notices' ) );
			}*/

			//add_action('wp_footer',array($this,'clear_ajax_notices'));
			/*add_action('wp_footer',function(){
				$this->clear_ajax_notices();
            });*/

		}

		public function add_modal_main_script(){
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
                                            callback(jQuery(this), index + 1, ownElement.length);
                                        });
                                        var childElements = jQuery(mutation.addedNodes).find(selector);
                                        childElements.each(function (index) {
                                            callback(jQuery(this), index + 1, childElements.length);
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
                    messages:[],
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
                                ttt_pwcn.readNotice(jQuery(this), index + 1, element.length);
                            });
                        }
                    },
                    readNotice: function (element, index, total) {
                        if (index <= total) {
                            ttt_pwcn.storeMessage(element);
                        }
                        if (index == total) {
                            ttt_pwcn.clearPopupMessages();
                            ttt_pwcn.addMessagesToPopup();
                            ttt_pwcn.openPopup(element);
                        }
                    },
                    clearPopupMessages:function(){
                        jQuery('#ttt-pwcn-notice').find('.modal__content').empty();
                    },
                    clearMessages:function(){
                        ttt_pwcn.messages=[];
                    },
                    storeMessage:function(notice){
                        ttt_pwcn.messages.push(notice.html());
                    },
                    addMessagesToPopup: function (notice) {
                        jQuery.each(ttt_pwcn.messages, function( index, value ) {
                            jQuery('#ttt-pwcn-notice .modal__content').append("<div class='ttt-pwcn-notice'>"+value+"</div>");
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
                            onClose:function(modal){
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

		/*public function clear_ajax_notices() {
			if ( ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
				delete_option( 'ttt_pwcn_ajax_notices' );
			}
			//error_Log('clear');
		}*/

		/*public function get_ajax_notices() {
			$notices = get_option( 'ttt_pwcn_ajax_notices' );
			error_log('b');
			error_log(print_r($this->notices,true));
			wp_send_json( array(
				'messages' => $this->format_notices( $notices )
			) );
		}*/

		/*public function get_notices( $notice ) {
			$this->notices[] = $notice;
			//if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
				update_option( 'ttt_pwcn_ajax_notices', $this->notices );
			//}

            error_log('a');
			error_log(print_r($this->notices,true));

			return $notice;
		}*/

		public function add_modal_style() {
			?>
            <style>
                .ttt-pwcn-notice {
                    /*border-bottom: 1px solid #ccc;*/
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
                    padding: 30px;
                    max-width: 500px;
                    max-height: 100vh;
                    border-radius: 4px;
                    box-sizing: border-box;
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
                }

                .modal__header .modal__close:before {
                    content: "\2715";
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

		/*public function format_notices( $notices ) {
			if ( is_array( $notices ) && count( $notices ) > 0 ) {
				return '<div class="ttt-pwcn-notice">' . implode( "</div><div class='ttt-pwcn-notice'>", $notices ) . '</div>';
			} else {
				return '';
			}
		}*/

		public function add_modal_html() {
			/*if ( empty( $this->notices ) ) {
				return;
			}*/
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

            <script>
                /*MicroModal.init({
                    awaitCloseAnimation: true
                });

                document.addEventListener("DOMContentLoaded", function () {
                    setTimeout(function () {
                        MicroModal.show('ttt-pwcn-notice', {
                            awaitCloseAnimation: true
                        }); // [1]
                    }, 300);
                });

                var ttt_pwcn_notices_ajax = {
                    init: function () {
                        var ajaxurl = "<?php //echo admin_url( 'admin-ajax.php' ); ?>";
                        var data = {
                            action: 'ttt_pwcn_get_ajax_notices'
                        };
                        jQuery.post(ajaxurl, data, function (response) {
                            console.log(response)
                            if (response.messages.length) {
                                var container = jQuery('#ttt-pwcn-notice').find('*[data-content="true"]');
                                container.html(response.messages);
                                MicroModal.show('ttt-pwcn-notice', {
                                    awaitCloseAnimation: true
                                });
                            }
                        });
                    }
                };

                jQuery(document).ajaxSuccess(function (event, xhr, settings) {
                    console.log(settings.url)
                    if (settings.url.indexOf('wc-ajax') !== -1) {
                        ttt_pwcn_notices_ajax.init();
                    }

                    //if (settings.url.indexOf('admin-ajax') === -1) {
                    //    ttt_pwcn_notices_ajax.init();
                    //}
                });
                */

            </script>
			<?php
		}
	}
}