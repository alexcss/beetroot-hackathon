<?php
/**
 * Created by PhpStorm.
 * User: AlexCSS
 * Date: 04.04.2018
 * Time: 23:26
 */


/**
 * Registers a new post type
 * @uses $wp_post_types Inserts new post type object into the list
 *
 * @param string  Post type key, must not exceed 20 characters
 * @param array|string  See optional args description above.
 * @return object|WP_Error the registered post type object, or an error object
 */
function bz_register_post_types() {

	$labels = array(
		'name'                => __( 'Cases', 'text-domain' ),
		'singular_name'       => __( 'Case', 'text-domain' ),
		'add_new'             => _x( 'Add New Case', 'text-domain', 'text-domain' ),
		'add_new_item'        => __( 'Add New Case', 'text-domain' ),
		'edit_item'           => __( 'Edit Case', 'text-domain' ),
		'new_item'            => __( 'New Case', 'text-domain' ),
		'view_item'           => __( 'View Case', 'text-domain' ),
		'search_items'        => __( 'Search Cases', 'text-domain' ),
		'not_found'           => __( 'No Cases found', 'text-domain' ),
		'not_found_in_trash'  => __( 'No Cases found in Trash', 'text-domain' ),
		'parent_item_colon'   => __( 'Parent Case:', 'text-domain' ),
		'menu_name'           => __( 'Cases', 'text-domain' ),
	);

	$args = array(
		'labels'              => $labels,
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'show_in_rest'        => true,
		'menu_position'       => null,
		'menu_icon'           => 'dashicons-format-gallery',
		'show_in_nav_menus'   => false,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => false,
		'can_export'          => true,
		'capability_type'     => 'post',
		'rewrite'             => array('slug' => 'cases'),
		'supports'            => array(
			'title', 'editor', 'author', 'thumbnail','revisions'
		)
	);

	register_post_type( 'bz_case', $args );

}

//add_action( 'init', 'bz_register_post_types' );