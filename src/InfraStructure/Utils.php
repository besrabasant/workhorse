<?php

namespace Workhorse\InfraStructure;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Utils {
	public static function hideFeaturedImage( string $post_type ) {
		add_filter( 'wp_editor_settings', function ( $settings ) use ( $post_type ) {
			$current_screen = \get_current_screen();

			if ( ! $current_screen || $current_screen->post_type === $post_type ) {
				return $settings;
			}

			$settings['media_buttons'] = false;

			return $settings;
		} );
	}
}