<?php
/*
Template Name: Front Page
*/
//
//$argsCases = array(
//	'post_type' => 'bz_case',
//	'posts_per_page' => 2,
//	'meta_key' => 'on_front',
//	'meta_value' => '1'
//);
//$cases = Timber::get_posts( $argsCases );

$post = new TimberPost();

$context         = Timber::get_context();
$context['post'] = $post;

$data = array(
	'featured_image' => get_the_post_thumbnail_url(),
);

$context = array_merge( $context, $data );

Timber::render( 'templates/front.twig', $context );
