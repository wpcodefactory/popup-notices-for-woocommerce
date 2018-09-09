<?php
/**
 * WP Admin Notices - Javascript
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Pablo S G Pacheco
 */

namespace ThanksToIT\WPAN;

if ( ! class_exists( 'ThanksToIT\WPAN\Javascript' ) ) {

	class Javascript {
		/**
		 * @var Notice
		 */
		private $notice;

		/*public function __construct( Notice $notice ) {
			$this->notice = $notice;
		}*/

		/**
		 * @var Notices_Manager
		 */
		private $manager;

		public function __construct( Notices_Manager $manager ) {
			$this->manager = $manager;
		}

		public function handle_dismissible_persistence() {
		    $ajax = new Ajax();
			?>
			<script>
                var ttt_dismissibility_persistence = {
                    handle_dismissibility_persistence: function () {
                        var close_buttons = document.querySelectorAll('.<?php echo esc_attr( Notice::$prefix )?> .notice-dismiss');
                        for (var i = 0; i < close_buttons.length; i++) {
                            var button = close_buttons[i];
                            button.addEventListener('click', ttt_dismissibility_persistence.handle_dismiss_notice);
                        }
                    },
                    handle_dismiss_notice: function (e) {
                        var button = e.target;
                        var notice = button.parentNode;
                        var is_persistent = notice.classList.contains('persistent');
                        if (!is_persistent) {
                            return;
                        }
                        var httpRequest = new XMLHttpRequest();
                        var id = notice.getAttribute('data-notice-id');
                        var expiration = notice.getAttribute('data-expiration');
                        httpRequest.open('POST', ajaxurl)
                        httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
                        httpRequest.send('action=' + encodeURIComponent('<?php echo $ajax->action_dismiss_persistently ?>') + '&id=' + encodeURIComponent(id) + '&expiration=' + encodeURIComponent(expiration) + '&security=' + encodeURIComponent('<?php echo wp_create_nonce( $ajax->action_dismiss_persistently_security ) ?>'));
                    },
                };
                document.addEventListener('DOMContentLoaded', function () {
                    ttt_dismissibility_persistence.handle_dismissibility_persistence();
                });
			</script>
			<?php
		}
	}
}