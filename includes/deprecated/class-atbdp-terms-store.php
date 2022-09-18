<?php
/**
 * @author wpWax
 * @deprecated 7.5.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ATBDP_Terms_Data_Store {

	use Directorist\Deprecated;

	public function __construct() {
		_deprecated_function( __CLASS__, '7.5.0' );
	}

}