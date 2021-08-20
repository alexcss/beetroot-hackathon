<?php
/**
 * Author: Ole Fredrik Lie
 * URL: http://olefredrik.com
 *
 * FoundationPress functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

/** Various clean up functions */
require_once('library/cleanup.php');

/** Required for Foundation to work properly */
require_once('library/foundation.php');

/** Format comments */
require_once('library/class-foundationpress-comments.php');

/** Register all navigation menus */
require_once('library/navigation.php');

/** Enqueue scripts */
require_once('library/enqueue-scripts.php');

/** Add theme support */
require_once('library/theme-support.php');

/** Change WP's sticky post class */
require_once('library/sticky-posts.php');

/** Configure custom image sizes */
require_once('library/responsive-images.php');

/** Customization Admin */
require_once('library/custom-admin.php');

/** Customization Gutenberg */
require_once('library/gutenberg.php');

/** If your site requires protocol relative url's for theme assets, uncomment the line below */
// require_once( 'library/class-foundationpress-protocol-relative-theme-assets.php' );

/*Wordpress Helpers*/
require_once('library/helpers.php');

/*add formats to editor*/
require_once('library/tiny_mce.php');

/** Add Timber support */

require_once('library/timber.php');

// add ACF Options Page
if (function_exists('acf_add_options_page')) {

	acf_add_options_page([
		'page_title' => 'Global Options',
		'menu_title' => 'Global Options',
		'menu_slug' => 'global-settings',
		'capability' => 'edit_posts',
		'redirect' => false
	]);
}

