<?php
/**
 * The template for displaying Author Archive pages
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.2
 */


$context = Timber::get_context();
$context['posts'] = new Timber\PostQuery();

if ( isset( $wp_query->query_vars['author'] ) ) {
	$author            = new Timber\User( $wp_query->query_vars['author'] );
	$context['author'] = $author;
	$context['title']  = 'Author Archives: ' . $author->name();
}

$templates = array('author.twig', 'index.twig');

Timber::render($templates, $context);
