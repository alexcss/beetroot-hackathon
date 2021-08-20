<?php
/**
 * Foundation PHP template
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

// Add class to body to help w/ CSS
add_filter( 'body_class', 'mobile_nav_class' );
function mobile_nav_class( $classes ) {
	$classes[] = 'offcanvas';
	return $classes;
}

// Pagination.
if ( ! function_exists( 'foundationpress_pagination' ) ) :
	function foundationpress_pagination() {
		global $wp_query;

		$big = 999999999; // This needs to be an unlikely integer

		// For more options and info view the docs for paginate_links()
		// http://codex.wordpress.org/Function_Reference/paginate_links
		$paginate_links = paginate_links(
			array(
				'base'      => str_replace( $big, '%#%', html_entity_decode( get_pagenum_link( $big ) ) ),
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'total'     => $wp_query->max_num_pages,
				'mid_size'  => 5,
				'prev_next' => true,
				'prev_text' => __( '&laquo;', 'foundationpress' ),
				'next_text' => __( '&raquo;', 'foundationpress' ),
				'type'      => 'list',
			)
		);

		$paginate_links = str_replace( "<ul class='page-numbers'>", "<ul class='pagination text-center' role='navigation' aria-label='Pagination'>", $paginate_links );
		$paginate_links = str_replace( '<li><span class="page-numbers dots">', "<li><a href='#'>", $paginate_links );
		$paginate_links = str_replace( '</span>', '</a>', $paginate_links );
		$paginate_links = str_replace( "<li><span class='page-numbers current'>", "<li class='current'>", $paginate_links );
		$paginate_links = str_replace( "<li><a href='#'>&hellip;</a></li>", "<li><span class='dots'>&hellip;</span></li>", $paginate_links );
		$paginate_links = preg_replace( '/\s*page-numbers/', '', $paginate_links );

		// Display the pagination if more than one page is found.
		if ( $paginate_links ) {
			echo $paginate_links;
		}
	}
endif;

// Custom Comments Pagination.
if ( ! function_exists( 'foundationpress_get_the_comments_pagination' ) ) :
	function foundationpress_get_the_comments_pagination( $args = array() ) {
		$navigation = '';
		$args = wp_parse_args( $args, array(
			'prev_text'				=> __( '&laquo;', 'foundationpress' ),
			'next_text'				=> __( '&raquo;', 'foundationpress' ),
			'size'					=> 'default',
			'show_disabled'			=> true,
		) );
		$args['type'] = 'array';
		$args['echo'] = false;
		$links = paginate_comments_links( $args );
		if ( $links ) {
			$link_count = count( $links );
			$pagination_class = 'pagination';
			if ( 'large' == $args['size'] ) {
				$pagination_class .= ' pagination-lg';
			} elseif ( 'small' == $args['size'] ) {
				$pagination_class .= ' pagination-sm';
			}
			$current = get_query_var( 'cpage' ) ? intval( get_query_var( 'cpage' ) ) : 1;
			$total = get_comment_pages_count();
			$navigation .= '<ul class="' . $pagination_class . '">';
			if ( $args['show_disabled'] && 1 === $current ) {
				$navigation .= '<li class="page-item disabled">' . $args['prev_text'] . '</li>';
			}
			foreach ( $links as $index => $link ) {
				if ( 0 == $index && 0 === strpos( $link, '<a class="prev' ) ) {
					$navigation .= '<li class="page-item">' . str_replace( 'prev page-numbers', 'page-link', $link ) . '</li>';
				} elseif ( $link_count - 1 == $index && 0 === strpos( $link, '<a class="next' ) ) {
					$navigation .= '<li class="page-item">' . str_replace( 'next page-numbers', 'page-link', $link ) . '</li>';
				} else {
					$link = preg_replace( "/(class|href)='(.*)'/U", '$1="$2"', $link );
					if ( 0 === strpos( $link, '<span class="page-numbers current' ) ) {
						$navigation .= '<li class="page-item active">' . str_replace( array( '<span class="page-numbers current">', '</span>' ), array( '<a class="page-link" href="#">', '</a>' ), $link ) . '</li>';
					} elseif ( 0 === strpos( $link, '<span class="page-numbers dots' ) ) {
						$navigation .= '<li class="page-item disabled">' . str_replace( array( '<span class="page-numbers dots">', '</span>' ), array( '<a class="page-link" href="#">', '</a>' ), $link ) . '</li>';
					} else {
						$navigation .= '<li class="page-item">' . str_replace( 'class="page-numbers', 'class="page-link', $link ) . '</li>';
					}
				}
			}
			if ( $args['show_disabled'] && $current == $total ) {
				$navigation .= '<li class="page-item disabled">' . $args['next_text'] . '</li>';
			}
			$navigation .= '</ul>';
			$navigation = _navigation_markup( $navigation, 'comments-pagination' );
		}
		return $navigation;
	}
endif;

// Custom Comments Pagination.
if ( ! function_exists( 'foundationpress_the_comments_pagination' ) ) :
	function foundationpress_the_comments_pagination( $args = array() ) {
		echo foundationpress_get_the_comments_pagination( $args );
	}
endif;


/**
 * Enable Foundation responsive embeds for WP video embeds
 */

if ( ! function_exists( 'foundationpress_responsive_video_oembed_html' ) ) :
	function foundationpress_responsive_video_oembed_html( $html, $url, $attr, $post_id ) {

		// Whitelist of oEmbed compatible sites that **ONLY** support video.
		// Cannot determine if embed is a video or not from sites that
		// support multiple embed types such as Facebook.
		// Official list can be found here https://codex.wordpress.org/Embeds
		$video_sites = array(
			'youtube', // first for performance
			'collegehumor',
			'dailymotion',
			'funnyordie',
			'ted',
			'videopress',
			'vimeo',
		);

		$is_video = false;

		// Determine if embed is a video
		foreach ( $video_sites as $site ) {
			// Match on `$html` instead of `$url` because of
			// shortened URLs like `youtu.be` will be missed
			if ( strpos( $html, $site ) ) {
				$is_video = true;
				break;
			}
		}

		// Process video embed
		if ( true == $is_video ) {

			// Find the `<iframe>`
			$doc = new DOMDocument();
			$doc->loadHTML( $html );
			$tags = $doc->getElementsByTagName( 'iframe' );

			// Get width and height attributes
			foreach ( $tags as $tag ) {
				$width  = $tag->getAttribute( 'width' );
				$height = $tag->getAttribute( 'height' );
				break; // should only be one
			}

			$class = 'responsive-embed'; // Foundation class

			// Determine if aspect ratio is 16:9 or wider
			if ( is_numeric( $width ) && is_numeric( $height ) && ( $width / $height >= 1.7 ) ) {
				$class .= ' widescreen'; // space needed
			}

			// Wrap oEmbed markup in Foundation responsive embed
			return '<div class="' . $class . '">' . $html . '</div>';

		} else { // not a supported embed
			return $html;
		}

	}
	add_filter( 'embed_oembed_html', 'foundationpress_responsive_video_oembed_html', 10, 4 );
endif;
