<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::get_context();
$context['post'] = get_field('page_404', 'option');

Timber::render( '404.twig', $context );
