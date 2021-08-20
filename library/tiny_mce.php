<?php


add_filter('acf/fields/wysiwyg/toolbars', function ($toolbars) {
	$toolbars['Full'] = array();
	$toolbars['Full'][1] = array(
		'formatselect',
		'bold',
		'italic',
		'underline',
		'bullist',
		'numlist',
		'alignleft',
		'aligncenter',
		'alignright',
		'alignjustify',
		'link',
		'unlink',
		'hr',
		'spellchecker',
		'wp_more',
		'wp_adv'
	);
	$toolbars['Full'][2] = array(
		'styleselect',
		'forecolor',
		'pastetext',
		'removeformat',
		'charmap',
		'outdent',
		'indent',
		'undo',
		'redo',
		'wp_help'
	);

	$toolbars['Font styling only'] = array();
	$toolbars['Font styling only'][1] = array('bold', 'italic', 'underline');

	return $toolbars;
});

add_filter('tiny_mce_before_init', function ($init) {

	// Toolbars anpassen:
	// alle verfügbaren Elemente findet man hier:
	// http://archive.tinymce.com/wiki.php/Controls
	$toolbar1 = explode(',', $init['toolbar1']);
	$toolbar1[] = 'wp_help';
	$toolbar1[] = 'undo';
	$toolbar1[] = 'redo';


	$toolbar2 = explode(',', $init['toolbar2']);
	if (($key = array_search('wp_help', $toolbar2)) !== false) {
		unset($toolbar2[$key]);
	}

	if (($key = array_search('undo', $toolbar2)) !== false) {
		unset($toolbar2[$key]);
	}

	if (($key = array_search('redo', $toolbar2)) !== false) {
		unset($toolbar2[$key]);
	}
	// array_unshift( $toolbar2, 'fontsizeselect' );
	array_unshift($toolbar2, 'styleselect');

	return array_merge($init, [
		'toolbar1' => join(',', $toolbar1),

		'toolbar2' => join(',', $toolbar2),

		// remove h1
		'block_formats' =>
			'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Preformatted=pre;',

		'style_formats' => json_encode([
			[
				'title' => 'Headings',
				'items' => [
					[
						'title' => 'Heading 1',
						'selector' => 'p,h1,h2,h3,h4,h5,h6',
						'inline' => 'span',
						'classes' => 'h1',
					],
					[
						'title' => 'Heading 2',
						'selector' => 'p,h1,h2,h3,h4,h5,h6',
						'inline' => 'span',
						'classes' => 'h2',
					],
					[
						'title' => 'Heading 3',
						'selector' => 'p,h1,h2,h3,h4,h5,h6',
						'classes' => 'h3',
					],
					[
						'title' => 'Heading 4',
						'selector' => 'p,h1,h2,h3,h4,h5,h6',
						'inline' => 'span',
						'classes' => 'h4',
					],
					[
						'title' => 'Heading 5',
						'selector' => 'p,h1,h2,h3,h4,h5,h6',
						'inline' => 'span',
						'classes' => 'h5',
					],
					[
						'title' => 'Heading 6',
						'selector' => 'p,h1,h2,h3,h4,h5,h6',
						'classes' => 'h6',
					],
				]
			],
			[
				'title' => 'Font Weight',
				'items' => [
					[
						'title' => 'Normal',
						'inline' => 'span',
						'selector' => 'h1,h2,h3,h4,h5,h6',
						'classes' => 'font_weight_normal',
						'wrapper' => false,
					],
				]
			],
//			[
//				'title' => 'Color',
//				'items' => [
//					[
//						'title' => 'Blue',
//						'inline' => 'span',
//						'selector' => 'p,a,li,td,h1,h2,h3,h4,h5,h6',
//						'classes' => 'blue_font',
//						'wrapper' => false,
//					],
//					[
//						'title' => 'Green',
//						'inline' => 'span',
//						'selector' => 'p,a,li,td,h1,h2,h3,h4,h5,h6',
//						'classes' => 'green_font',
//						'wrapper' => false,
//					],
//					[
//						'title' => 'Red',
//						'inline' => 'span',
//						'selector' => 'p,a,li,td,h1,h2,h3,h4,h5,h6',
//						'classes' => 'red_font',
//						'wrapper' => false,
//					],
//					[
//						'title' => 'Orange',
//						'inline' => 'span',
//						'selector' => 'p,a,li,td,h1,h2,h3,h4,h5,h6',
//						'classes' => 'orange_font',
//						'wrapper' => false,
//					],
//					[
//						'title' => 'Gray',
//						'inline' => 'span',
//						'selector' => 'p,a,li,td,h1,h2,h3,h4,h5,h6',
//						'classes' => 'gray_font',
//						'wrapper' => false,
//					],
//					[
//						'title' => 'White',
//						'inline' => 'span',
//						'selector' => 'p,a,li,td,h1,h2,h3,h4,h5,h6',
//						'classes' => 'white_font',
//						'wrapper' => false,
//					],
//				]
//			],
			[
				'title' => 'UPPERCASE',
				'inline' => 'span',
				'selector' => 'p,a,li,td,h1,h2,h3,h4,h5,h6',
				'classes' => 'text-uppercase',
				'wrapper' => false,
			],
		]),
	]);
});
