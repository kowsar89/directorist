<?php
/**
 * @author wpWax
 * @deprecated 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ATBDP_GJSGeoQuery {

	use Directorist\Deprecated;

	public function __construct() {
		_deprecated_function( __CLASS__, '7.4.0' );
	}

}

function atbdp_the_distance( $post_obj = null, $round = false ) {
	_deprecated_function( __FUNCTION__, '7.4.0' );
	return '';
}

function atbdp_get_the_distance( $post_obj = null, $round = false ) {
	_deprecated_function( __FUNCTION__, '7.4.0' );
	return '';
}