<?php

// Gutenberg Only for ....
add_filter( 'use_block_editor_for_post_type', function( $enabled, $post_type ) {
	return in_array( $post_type, [ 'post', 'page' ] );
}, 10, 2 );


/**
 * Templates and Page IDs without editor
 *
 */
function zg_disable_editor($id = false)
{

	$excluded_templates = array(
		'page-templates/front.php',
	);

	$excluded_ids = array(// get_option( 'page_on_front' )
	);

	if (empty($id))
		return false;

	$id = intval($id);
	$template = get_page_template_slug($id);

	return in_array($id, $excluded_ids) || in_array($template, $excluded_templates);
}

/**
 * Disable Gutenberg by template
 *
 */
function zg_disable_gutenberg($can_edit, $post_type)
{

	if (!(is_admin() && !empty($_GET['post'])))
		return $can_edit;

	if (zg_disable_editor($_GET['post']))
		$can_edit = false;

	return $can_edit;

}

add_filter('gutenberg_can_edit_post_type', 'zg_disable_gutenberg', 10, 2);
add_filter('use_block_editor_for_post_type', 'zg_disable_gutenberg', 10, 2);

//Custom gutenberg styles

//add_action('enqueue_block_editor_assets', function () {
//
//	wp_enqueue_script(
//		'zg-editor',
//		get_template_directory_uri() . '/g-blocks/assets/js/editor.js',
//		array('wp-blocks', 'wp-dom', 'wp-edit-post'),
//		filemtime(get_template_directory() . '/g-blocks/assets/js/editor.js'),
//		true
//	);
//});
//
//add_action('after_setup_theme', function () {
//	add_theme_support('editor-styles');
//	add_editor_style('g-blocks/assets/css/editor.css');
//});
