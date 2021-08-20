<?php

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if (!class_exists('Timber')) {

	add_action(
		'admin_notices',
		function () {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
		}
	);

//	add_filter(
//		'template_include',
//		function( $template ) {
//			return get_stylesheet_directory() . '/static/no-timber.html';
//		}
//	);
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array('twigs', 'views');

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_filter('timber/context', array($this, 'add_to_context'));
		add_filter('timber/twig', array($this, 'add_to_twig'));
		parent::__construct();
	}

	/** This is where you add some context
	 *
	 * @param array $context context['this'] Being the Twig's {{ this }}.
	 *
	 * @return array
	 */
	public function add_to_context($context) {
		$registered_menus = get_registered_nav_menus();
		$menus = [];

		if(!empty($registered_menus)){
			foreach ($registered_menus as $key => $menu){
				$menus[$key] = new Timber\Menu($key);
			}
		} else {
			$menus = new Timber\Menu();
		}
		$context['menu'] = $menus;
		$context['custom_logo'] = new Timber\Image(get_theme_mod('custom_logo'));
		$context['footer'] = get_field('footer', 'options');

		return $context;
	}

	/** This Would return 'foo bar!'.
	 *
	 * @param string $string being 'foo', then returned 'foo bar!'.
	 * @return string
	 */
	public function phone_url_filter($string) {
		$string = preg_replace('/[^0-9+]/', '', $string);
		return $string;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 * @return string $twig
	 */
	public function add_to_twig($twig) {
		$twig->addExtension(new Twig\Extension\StringLoaderExtension());
		$twig->addFilter(new Twig\TwigFilter('phone_url', array($this, 'phone_url_filter')));
		return $twig;
	}

}

new StarterSite();
