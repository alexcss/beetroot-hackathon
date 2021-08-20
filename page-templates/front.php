<?php
/*
Template Name: Front Page
*/

$post = new TimberPost();

$context         = Timber::get_context();
$context['post'] = $post;
if (isset($_COOKIE['zg-switch-theme-color']) && !empty($_COOKIE['zg-switch-theme-color'])) {
	$context['theme_color'] = 'switch-theme';
}
$data = array(
	'title' => get_the_title(),
	'content' => get_the_content(),
	'featured_image' => get_the_post_thumbnail_url(),
);

$context = array_merge( $context, $data );

Timber::render( 'templates/front.twig', $context );
