<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.2
 */


$context = Timber::get_context();
$context['title'] = get_the_archive_title();
$context['posts'] = new Timber\PostQuery();

$templates = array('archive.twig', 'index.twig');

Timber::render($templates, $context);
