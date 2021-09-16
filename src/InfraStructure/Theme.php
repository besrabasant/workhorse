<?php

namespace Workhorse\InfraStructure;

use Illuminate\Cache\Repository;

class Theme {
	public static function cache( string $key, \Closure $dataCallback, $ttl = null ) {
		if ( WP_ENV !== 'production' ) {
			return $dataCallback();
		}

		/** @var Repository $cache */
		$cache = \Roots\app( 'cache' );

		if ( ! $cache->has( $key ) ) {

			$data = $dataCallback();


			$cache->put( $key, $data, $ttl );
		}

		return $cache->get( $key );
	}
}