<?php
/**
 * Plugin Name: WP Cache Remember
 * Plugin URI:  PLUGIN SITE HERE
 * Description: Helper for the WordPress object cache and transients
 * Author:      Steve Grunwell
 * Author URI:  https://stevegrunwell.com
 * Text Domain: wp-cache-remember
 * Domain Path: /languages
 * Version:     0.1.0
 *
 * @package SteveGrunwell\WPCacheRemember
 */

if ( ! function_exists( 'wp_cache_remember' ) ) :
	/**
	 * Retrieve a value from the object cache. If it doesn't exist, run the $callback to generate and
	 * cache the value.
	 *
	 * @param string   $key      The cache key.
	 * @param callable $callback The callback used to generate and cache the value.
	 * @param string   $group    Optional. The cache group. Default is empty.
	 * @param int      $expire   Optional. The number of seconds before the cache entry should expire.
	 *                           Default is 0 (as long as possible).
	 *
	 * @return mixed The value returned from $callback, pulled from the cache when available.
	 */
	function wp_cache_remember( $key, $callback, $group = '', $expire = 0 ) {
		$found  = false;
		$cached = wp_cache_get( $key, $group, false, $found );

		if ( false !== $found ) {
			return $cached;
		}

		$value = $callback();

		wp_cache_set( $key, $value, $group, $expire );

		return $value;
	}
endif;

if ( ! function_exists( 'wp_cache_forget' ) ) :
	/**
	 * Retrieve a value from the object cache and subsequently delete the value from the object cache.
	 *
	 * @param string $key     The cache key.
	 * @param string $group   Optional. The cache group. Default is empty.
	 * @param mixed  $default Optional. The default value to return if the given key doesn't
	 *                          exist in the object cache. Default is null.
	 *
	 * @return mixed The cached value, when available, or $default.
	 */
	function wp_cache_forget( $key, $group = '', $default = null ) {
		$found  = false;
		$cached = wp_cache_get( $key, $group, false, $found );

		if ( false !== $found ) {
			wp_cache_delete( $key, $group );

			return $cached;
		}

		return $default;
	}
endif;

if ( ! function_exists( 'remember_transient' ) ) :
	/**
	 * Retrieve a value from transients. If it doesn't exist, run the $callback to generate and
	 * cache the value.
	 *
	 * @param string   $key      The transient key.
	 * @param callable $callback The callback used to generate and cache the value.
	 * @param int      $expire   Optional. The number of seconds before the cache entry should expire.
	 *                           Default is 0 (as long as possible).
	 *
	 * @return mixed The value returned from $callback, pulled from transients when available.
	 */
	function remember_transient( $key, $callback, $expire = 0 ) {
		$cached = get_transient( $key );

		if ( false !== $cached ) {
			return $cached;
		}

		$value = $callback();

		set_transient( $key, $value, $expire );

		return $value;
	}
endif;

if ( ! function_exists( 'forget_transient' ) ) :
	/**
	 * Retrieve a value from transients and subsequently delete the value from the transient cache.
	 *
	 * @param string $key     The transient key.
	 * @param mixed  $default Optional. The default value to return if the given key doesn't
	 *                          exist in the object cache. Default is null.
	 *
	 * @return mixed The cached value, when available, or $default.
	 */
	function forget_transient( $key, $default = null ) {
		$cached = get_transient( $key );

		if ( false !== $cached ) {
			delete_transient( $key );

			return $cached;
		}

		return $default;
	}
endif;
