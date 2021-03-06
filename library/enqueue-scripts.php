<?php
/**
 * Enqueue all styles and scripts
 *
 * Learn more about enqueue_script: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_script}
 * Learn more about enqueue_style: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_style }
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */


// Check to see if rev-manifest exists for CSS and JS static asset revisioning
//https://github.com/sindresorhus/gulp-rev/blob/master/integration.md

if (!function_exists('foundationpress_asset_path')) :
	function foundationpress_asset_path($filename)
	{
		$filename_split = explode('.', $filename);
		$dir = end($filename_split);
		$manifest_path = dirname(dirname(__FILE__)) . '/dist/assets/' . $dir . '/rev-manifest.json';

		if (file_exists($manifest_path)) {
			$manifest = json_decode(file_get_contents($manifest_path), true);
		} else {
			$manifest = [];
		}

		if (array_key_exists($filename, $manifest)) {
			return $manifest[$filename];
		}
		return $filename;
	}
endif;


if (!function_exists('foundationpress_scripts')) :
	function foundationpress_scripts()
	{

		// Enqueue the main Stylesheet.
		wp_enqueue_style('main', get_stylesheet_directory_uri() . '/dist/assets/css/' . foundationpress_asset_path('app.css'), array(), null, 'all');

		// Deregister the jquery version bundled with WordPress.
		wp_deregister_script('jquery');

		// CDN hosted jQuery placed in the header, as some plugins require that jQuery is loaded in the header.
		wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', array(), '3.6.0', false);

		// Deregister the jquery-migrate version bundled with WordPress.
		wp_deregister_script('jquery-migrate');

		// CDN hosted jQuery migrate for compatibility with jQuery 3.x
		// wp_register_script('jquery-migrate', '//code.jquery.com/jquery-migrate-3.0.1.min.js', array('jquery'), '3.0.1', false);

		// Enqueue jQuery migrate. Uncomment the line below to enable.
		// wp_enqueue_script( 'jquery-migrate' );

		// Enqueue Foundation scripts
		wp_enqueue_script('vue', 'https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js', null, null, true);
		wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.6.1/gsap.min.js', null, null, true);
		wp_enqueue_script('app', get_stylesheet_directory_uri() . '/dist/assets/js/' . foundationpress_asset_path('app.js'), array('jquery'), null, true);
		wp_localize_script('app', 'themeUrl', get_template_directory_uri());

		// Enqueue FontAwesome from CDN. Uncomment the line below if you need FontAwesome.
		//wp_enqueue_script( 'fontawesome', 'https://use.fontawesome.com/5016a31c8c.js', array(), '4.7.0', true );

		// Add the comment-reply library on pages where it is necessary
		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}

		wp_dequeue_style('wp-block-library');
		wp_dequeue_style('bodhi-svgs-attachment');
		wp_dequeue_style('wc-block-style');
		wp_deregister_script('wp-embed');
	}

	add_action('wp_enqueue_scripts', 'foundationpress_scripts');
endif;

/*------------------------------------*\
    //Javascript to footer
\*------------------------------------*/

add_action('wp_enqueue_scripts', function () {
	remove_action('wp_head', 'wp_print_scripts');
	remove_action('wp_head', 'wp_print_head_scripts', 9);
	remove_action('wp_head', 'wp_enqueue_scripts', 1);
	add_action('wp_footer', 'wp_print_scripts', 5);
	add_action('wp_footer', 'wp_enqueue_scripts', 5);
	add_action('wp_footer', 'wp_print_head_scripts', 5);
});

// Remove CF7 styles
//add_filter('wpcf7_load_css', '__return_false');
