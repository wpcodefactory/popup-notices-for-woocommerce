<?php
/**
 * WP Admin Notices - WP Plugin
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Pablo S G Pacheco
 */

namespace ThanksToIT\WPAN;

use MongoDB\Driver\Manager;

if ( ! class_exists( 'ThanksToIT\WPAN\Notice' ) ) {

	class Notice {
		private $id = 'ttt-wp-admin-notice';
		private $content = '<p></p>';
		private $notice_html = '';
		private $notice_type = 'notice-info';
		public $dismissible = true;
		public $dismissible_persistent = true;
		public static $prefix = 'tttwpan';
		public $dismiss_expiration = MONTH_IN_SECONDS;
		public $valid = true;
		public $keep_active_on = array( 'activated_plugin', 'updated_plugin' );

		/**
		 * @var Javascript
		 */
		private static $javascript;

		/**
		 * @var Display_Rules
		 */
		private $display_rules;

		/**
		 * @var Notices_Manager
		 */
		private $manager;

		/**
		 * @return Notices_Manager
		 */
		public function get_manager() {
			return $this->manager;
		}

		/**
		 * @param Notices_Manager $manager
		 */
		public function set_manager( $manager ) {
			$this->manager = $manager;
		}

		public function __construct( $id ) {
			$this->id = sanitize_title( $id );
		}

		public function setup_dismissibility( $dismissible = true, $persistent = true, $expiration = MONTH_IN_SECONDS ) {
			$this->dismissible            = $dismissible;
			$this->dismissible_persistent = $persistent;
			$this->dismiss_expiration     = $expiration;
		}

		public function keep_active_if_necessary( $display_on_key ) {
			if ( in_array( $display_on_key, $this->keep_active_on ) ) {
				$ids   = get_transient( "tttwpan_active_notices" );
				$ids   = $ids === false ? array() : $ids;
				$count = count( $ids );
				$ids[] = $this->get_id();
				$ids   = array_unique( $ids );
				if ( count( $ids ) != $count ) {
					set_transient( "tttwpan_active_notices", $ids, MONTH_IN_SECONDS );
				}
			}
		}

		public function is_active() {
			$ids   = get_transient( "tttwpan_active_notices" );
			$ids   = $ids === false ? array() : $ids;
			$found = array_search( $this->get_id(), $ids );
			if ( $found !== false ) {
				return true;
			} else {
				return false;
			}
		}

		public function remove_from_active() {
			$ids   = get_transient( "tttwpan_active_notices" );
			$ids   = $ids === false ? array() : $ids;
			$found = array_search( $this->get_id(), $ids );
			if ( $found !== false ) {
				unset( $ids[ $found ] );
			}
			set_transient( "tttwpan_active_notices", $ids, MONTH_IN_SECONDS );
		}

		private static function handle_javascript() {
			$js = self::$javascript;
			if ( ! $js ) {
				$js = new Javascript( get_notices_manager() );
				$js->handle_dismissible_persistence();
				self::$javascript = $js;
			}
		}

		/**
		 * @return Display_Rules
		 */
		public function get_display_rules() {
			return $this->display_rules;
		}

		public function display_on( $args = array() ) {
			$this->display_rules = new Display_Rules( $args );
			$this->display_rules->set_manager( $this->get_manager() );
			$this->display_rules->set_notice( $this );
		}

		public function enable() {
			if ( ! $this->valid ) {
				return;
			}

			if ( current_filter() != 'admin_notices' ) {
				add_action( 'admin_notices', array( $this, 'display' ) );
			} else {
				$this->display();
			}
		}

		public function is_dismissed() {
			$id        = $this->get_id();
			$user      = \wp_get_current_user();
			$prefix    = self::$prefix;
			$dismissed = get_transient( "{$prefix}_dismiss_{$id}_{$user->ID}" );
			if ( $dismissed === false ) {
				return false;
			} else {
				return true;
			}
		}

		public function display() {
			if (
				! $this->valid ||
				$this->is_dismissed()
			) {
				return;
			}

			if ( ! $this->display_rules ) {
				$this->display_on( new Display_Rules() );
			}
			if ( $this->get_display_rules()->rules_match() ) {
				echo $this->create_html();
			}
			self::handle_javascript();
		}

		public function dismiss() {
			$id     = $this->get_id();
			$user   = wp_get_current_user();
			$prefix = self::$prefix;
			if ( $this->dismiss_expiration > 0 ) {
				set_transient( "{$prefix}_dismiss_{$id}_{$user->ID}", true, $this->dismiss_expiration );
			}
			//$this->remove_from_active();
		}

		public function create_html() {
			$dismissible_class      = $this->dismissible ? 'is-dismissible' : '';
			$dismissible_persistent = $this->dismissible_persistent ? 'persistent' : '';
			$id                     = $this->id;
			$expiration             = $this->dismiss_expiration;

			return sprintf(
				'<div class="notice %2$s %3$s %4$s %5$s %6$s" data-id="%5$s" data-expiration="%7$s" data-notice-id="%5$s">%1$s</div>',
				$this->get_content(),
				esc_attr( $this->get_type() ),
				esc_attr( $dismissible_class ),
				esc_attr( $dismissible_persistent ),
				esc_attr( $id ),
				esc_attr( self::$prefix ),
				esc_attr( $expiration )
			);
		}

		/**
		 * @return string
		 */
		public function get_notice_html() {
			return $this->notice_html;
		}

		/**
		 * @param string $notice_html
		 */
		public function set_notice_html( $notice_html ) {
			$this->notice_html = $notice_html;
		}

		/**
		 * @return string
		 */
		public function get_id() {
			return $this->id;
		}

		/**
		 * @return string
		 */
		public function get_content() {
			return $this->content;
		}

		/**
		 * @param string $content
		 */
		public function set_content( $content ) {
			$this->content = $content;
		}

		/**
		 * @return string
		 */
		public function get_type() {
			return $this->notice_type;
		}

		/**
		 * @param string $notice_type notice-error | notice-warning | notice-success | notice-info
		 */
		public function set_type( $notice_type = 'notice-info' ) {
			$this->notice_type = $notice_type;
		}


	}
}