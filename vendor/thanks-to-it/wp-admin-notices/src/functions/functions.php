<?php

namespace ThanksToIT\WPAN;

/**
 * @return Notices_Manager
 */
function get_notices_manager() {
	$notices_manager = Notices_Manager::instance();

	return $notices_manager;
}
