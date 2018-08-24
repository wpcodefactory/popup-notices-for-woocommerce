<?php
/**
 * Plugin Name: Pretty WooCommerce Notices - TTT
 * Description: Customize WooCommerce Notices
 * Version: 1.0.0
 * Author: Thanks to IT
 * Author URI: https://github.com/thanks-to-it
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: pretty-wc-notices-ttt
 * Domain Path: /languages
 */

require_once "vendor/autoload.php";

$plugin = \ThanksToIT\PWCN\Core::instance();
$plugin->setup( array(
	'path' => __FILE__
) );
$plugin->init();