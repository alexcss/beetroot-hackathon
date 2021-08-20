<?php

/**
 * Custom language switcher
 * @return string
 */
function customLanguagesList()
{
	$languages = icl_get_languages('skip_missing=0');
	$result = '';
	if (!empty($languages)) {
		$result .= '<ul class="lang-switch">';

		foreach ($languages as $l) {
			if (!$l['active']) {
				$result .= '<li>';
				$result .= '<a href="' . $l['url'] . '" class="language-list__link">';
			} else {
				$result .= '<li class="active">';
			}
			$result .= icl_disp_language($l['translated_name']);
			if (!$l['active']) {
				$result .= '</a>';
			}
			$result .= '</li>';
		}
		$result .= '</ul>';
	}
	return $result;
}

/**
 * Output background image style
 *
 * @param array|string $img Image array or url
 * @param bool $echo Whether to output the the style tag or return it.
 *
 * @return string|void String when retrieving.
 */
function bg($img, $size = '', $echo = true)
{
	if (!$img) {
		trigger_error("Image source can't be empty", E_USER_NOTICE);
	}

	if (is_array($img)) {
		$url = $size ? $img['sizes'][$size] : $img['url'];
	} else {
		$url = $img;
	}

	$string = 'style="background-image: url(' . $url . ')"';

	if ($echo) {
		echo $string;
	} else {
		return $string;
	}
}

/**
 * Get post fallback thumbnail
 *
 * @param int| $post Post object or id
 * @param string $size Image size
 *
 * @return string|void String when retrieving.
 */
function getPostFallbackThumbnaillUrl($size = 'large')
{
	$post_fallback_image = get_field('posts_fallback_image', 'options');
	$wp_thumb_url = (is_array($post_fallback_image) ? $post_fallback_image['sizes'][$size] : $post_fallback_image)
		?: get_stylesheet_directory_uri() . '/assets/images/image-placeholder.jpg';
	$thumb_url = prepareImage($wp_thumb_url ?: $wp_thumb_url, $size);

	$wp_thumb_url = get_stylesheet_directory_uri() . '/assets/images/image-placeholder.jpg';
//        $thumb_url = prepareImage($wp_thumb_url ?: $wp_thumb_url, $size);

	return $thumb_url;
}

/**
 * override suppress_filters for all get_posts calls by using this instead
 * @return array
 */
function getPosts($args)
{
	return get_posts(array_merge(['suppress_filters' => false], $args));
}

/**
 * extract unique values from array
 * @return array
 */
function map($arr, $key, $sort = true)
{
	foreach ($arr as $item) {
		$res[] = $item[$key];
	}
	$res = array_unique($res);
	$sort ? sort($res) : null;
	return array_values($res);
}

/**
 * Format phone number, trim all unnecessary characters
 *
 * @param string $phone Phone number
 *
 * @return string Formatted phone number
 */
function preparePhone($phone)
{
	return preg_replace('/[^+\d]+/', '', $phone);
}

/**
 * Return/Output SVG as html
 *
 * @param array|string $img Image link or array
 * @param string $class Additional class attribute for img tag
 * @param string $size Image size if $img is array
 * @param array $attributes Additional attributes array (key is an attribute name)
 *
 * @return void
 */
function displaySvg($img, $class = '', $size = 'medium', $attributes = array())
{
	echo returnSvg($img, $class, $size, $attributes);
}

function returnSvg($img, $class = '', $size = 'medium', $attributes = array())
{
	if (!$img) {
		return '';
	}

	$attr_array = array();
	if (!empty($attributes)) {
		foreach ($attributes as $attr_name => $attr_value) {
			$attr_array[] = $attr_name . '="' . $attr_value . '"';
		}
	}

	$icon_url = is_array($img) ? $img['url'] : $img;
	$icon_url = changeUrlToPath($icon_url);
	$file_info = pathinfo($icon_url);
	if ($file_info['extension'] == 'svg') {
		$arrContextOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
			),
		);
		$image = file_get_contents($icon_url, false, stream_context_create($arrContextOptions));
		if ($attr_array) {
			$image = str_replace('<svg ', '<svg ' . implode(' ', $attr_array) . ' ', $image);
		}
		if ($class) {
			$image = str_replace('<svg ', '<svg class="' . $class . '" ', $image);
		}
	} elseif (is_array($img)) {
		$image = '<img
                class="' . $class . '"
                src="' . $img['sizes'][$size] . '"
                ' . ($attr_array ? implode(' ', $attr_array) . ' ' : null) . '
            />';
	} else {
		$image = '<img class="' . $class . '" src="' . $img . '" ' . ($attr_array ? implode(' ', $attr_array) . ' ' : null) . '/>';
	}
	return $image;
}

function changeUrlToPath($url)
{
	$path_info = wp_upload_dir();
	return str_replace($path_info['baseurl'], $path_info['basedir'], $url);
}

/**
 * Get post thumbnail
 *
 * @param int|object $post Post object (WP_Post) or id
 * @param string $size Image size
 *
 * @return string|void String when retrieving.
 */
function getPostThumbnailUrl($post = null, $size = 'large')
{
	if (!$post) {
		global $post;
	}
	$wp_thumb_url = get_the_post_thumbnail_url($post, $size);
	$thumb_url = prepareImage($wp_thumb_url);

	return $thumb_url;
}

/**
 * Prepare image
 *
 * @param int|string $image Image url or id
 * @param string $size Image size
 *
 * @return string|void String when retrieving.
 */
function prepareImage($image, $size = 'large')
{
	if (intval($image)) {
		$image = wp_get_attachment_image_url($image, $size);
	}
	return $image ? : getPostFallbackThumbnaillUrl($size);
}

/**
 * Get all menus
 *
 * @return array|void String when retrieving.
 */
function getAllMenus()
{
	return get_terms('nav_menu', array('hide_empty' => true));
}


/**
 * Custom excerpt
 * @param int|object $post id or post object
 * @return string
 */
function getCustomExcerpt($post, $length = 55, $echo = false)
{
	if ($content = get_field('excerpt', $post)) {
		$excerpt = substr($content, 0, strpos($content, '</p>') + 4);
	} elseif (has_excerpt($post)) {
		$excerpt = wp_trim_words(get_the_excerpt($post), $length, '');
	} else {
		$patterns = "/\[[\/]?[^\]]*\]/";
		$replacements = "";
		$excerpt = preg_replace($patterns, $replacements, get_the_content($post));
		$excerpt = wp_trim_words($excerpt, $length, '');
	}

	if ($echo) {
		echo $excerpt;
	} else {
		return $excerpt;
	}
}

/**
 * [formatBytes description]
 * @param integer $bytes Size in bytes
 * @param integer $precision
 * @return string
 */

function formatBytes($bytes, $precision = 2)
{
	$units = array('b', 'Kb', 'Mb', 'Gb', 'Tb');

	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);

	// Uncomment one of the following alternatives
	$bytes /= pow(1024, $pow);
	// $bytes /= (1 << (10 * $pow));

	return round($bytes, $precision) . ' ' . $units[$pow];
}

function formatText($text)
{
	$pattern = array('/\*(.*?)\*/', '/\_(.*?)\_/', '/\~(.*?)\~/');
	$replacement = array('<b>$1</b>', '<u>$1</u>', '<i>$1</i>');
	return preg_replace($pattern, $replacement, $text);
}

/*Check if IE browser*/
function aeDetectIE()
{
	if (preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT'])
		|| (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false)
		|| (strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== false)) {
		return true;
	}
	return false;
}
