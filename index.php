<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */
$context = Timber::get_context();
$context['posts'] = Timber::get_posts();
$context['title'] = get_the_title( get_option('page_for_posts', true));
//$context['categories'] = Timber::get_terms('category');
//$context['blog_url'] = get_permalink( get_option( 'page_for_posts' ));

$templates = array( 'index.twig' );
if ( is_home() ) {
	array_unshift( $templates, 'home.twig' );
}
Timber::render( $templates, $context );

