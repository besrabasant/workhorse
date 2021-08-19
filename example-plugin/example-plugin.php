<?php


use Workhorse\Plugin\Bootloader;

/**
 * Plugin Name:  Example Plugin
 * URI:          https://example.com
 * Description:  An Example Workhorse Plugin.
 * Version:      1.0.0
 * Author:       Basant Besra
 * Author URI:   https://besrabasant.github.io/
 * License:      MIT License
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require __DIR__ . "/vendor/autoload.php";

Bootloader::register( __FILE__, \ExamplePlugin\ExamplePlugin::PLUGIN_NAME );
