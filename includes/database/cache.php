<?php
/**
 * Handles object cache
 *
 * @author wpWax
 * @since  7.5.0
 */

namespace Directorist\database;

use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Object_Cache {

	public static function init() {
		// Invalidate caches
		add_action( 'post_updated', array( __CLASS__, 'flush_listings' ) );
		add_action( 'delete_post', array( __CLASS__, 'flush_listings' ) );
		add_action( 'updated_postmeta', array( __CLASS__, 'flush_listings' ) );
		add_action( 'deleted_postmeta', array( __CLASS__, 'flush_listings' ) );

		add_action( 'set_object_terms', array( __CLASS__, 'flush_listings' ) );
		add_action( 'deleted_term_relationships', array( __CLASS__, 'flush_listings' ) );



		add_action( 'clean_taxonomy_cache', array( __CLASS__, 'flush_listings' ) );
		add_action( 'clean_term_cache', array( __CLASS__, 'flush_listings' ) );

	}

	public static function flush_listings() {
		// $group = self::get_group( 'listings' );
		// if ( wp_cache_supports( 'flush_group' ) ) {
		// 	wp_cache_flush_group( $group );
		// } else {

		// }

	}

	public static function get_listings_data( $args ) {

		$group = self::get_group( 'listings' );
		$key   = self::generate_cache_key( $args );

		$query = wp_cache_get( $key, $group );

		if ( ! $query ) {
			$query = new WP_Query( $args );
			wp_cache_set( $key, $query, $group );
		}

		return $query;
	}

	public static function get_group( $groupname ) {
		if ( $groupname == 'listings' ) {
			return 'directorist_listings';
		}
		return '';
	}

	/**
	 * Ref: https://developer.wordpress.org/reference/classes/wp_query/generate_cache_key/
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public static function generate_cache_key( $args ) {
		global $wpdb;

		unset(
			$args['cache_results'],
			$args['fields'],
			$args['lazy_load_term_meta'],
			$args['update_post_meta_cache'],
			$args['update_post_term_cache'],
			$args['update_menu_item_cache'],
			$args['suppress_filters']
		);

		$placeholder = $wpdb->placeholder_escape();
		array_walk_recursive(
			$args,
			/*
			 * Replace wpdb placeholders with the string used in the database
			 * query to avoid unreachable cache keys. This is necessary because
			 * the placeholder is randomly generated in each request.
			 *
			 * $value is passed by reference to allow it to be modified.
			 * array_walk_recursive() does not return an array.
			 */
			function ( &$value ) use ( $wpdb, $placeholder ) {
				if ( is_string( $value ) && str_contains( $value, $placeholder ) ) {
					$value = $wpdb->remove_placeholder_escape( $value );
				}
			}
		);

		$key = md5( serialize( $args ) );

		return "directorist:$key";
	}

}
