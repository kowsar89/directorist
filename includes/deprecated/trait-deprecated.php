<?php
/**
 * @author wpWax
 *
 * Avoiding PHP fatal errors when using deprecated classes
 */

namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) exit;

trait Deprecated {

	public function __set($name, $value) {

	}

	public function __get($name) {

	}

	public function __call($name, $arguments) {

	}

	public static function __callStatic($name, $arguments){

	}
}