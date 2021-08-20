<?php

// Customize Login Screen
function wordpress_login_styling() {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$logoSrc = wp_get_attachment_image_src( $custom_logo_id , 'full' );

	?>
	<style type="text/css">
		.login #login h1 a {
			background-image: url('<?php echo $logoSrc[0]; ?>');
			background-size: contain;
			background-position: 50%;
			width: auto;
		}
	   body.login{
		   background-color: #<?php echo get_background_color(); ?>;
		   background-repeat: repeat;
		   background-position: center center;
	   }

	</style>
<?php }
add_action( 'login_enqueue_scripts', 'wordpress_login_styling' );

function admin_logo_custom_url(){
	$site_url = home_url();
	return ($site_url);
}
add_filter('login_headerurl', 'admin_logo_custom_url');
