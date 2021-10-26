<?php

namespace Workhorse\InfraStructure;

use Illuminate\Cache\Repository;

// TODO: DISable cache till feature to invalidate cache is added.
class Theme {
	public static function cache( string $key, \Closure $dataCallback, $ttl = null ) {
		$key = function_exists( 'pll__' ) ? $key . "_" . get_locale() : $key;

		return $dataCallback();

//		if ( WP_ENV !== 'production' ) {
//			return $dataCallback();
//		}
//
//		/** @var Repository $cache */
//		$cache = \Roots\app( 'cache' );
//
//		if ( ! $cache->has( $key ) ) {
//
//			$data = $dataCallback();
//
//
//			$cache->put( $key, $data, $ttl );
//		}
//
//		return $cache->get( $key );
	}
}