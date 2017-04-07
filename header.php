<?php
/**
* The Header for our theme.
*
* Displays all of the <head> section and everything up till <div id="main">
*
* @package WordPress
* @subpackage Twenty_Twelve
* @since Twenty Twelve 1.0
*/
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
	<!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width" />
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />
		<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
		<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
		<?php wp_head(); ?>

		<link media="all" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/style.css" rel="stylesheet">
		<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/navigation.js"></script>
	</head>

	<body <?php body_class(); ?>>

		<div id="fb-root"></div>
		<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


		<header id="masthead" class="site-header" role="banner">
			<div class="header-bg"></div>
			<div class="top-holder">
				<div class="logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="logo">
						<img src="<?php echo get_template_directory_uri(); ?>/images/logo.png">
					</a>
				</div>
				<hgroup>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
				</hgroup>

				<div class="follow-us">
					<ul class="soc">
						<li><a class="soc-facebook" href="#"></a></li>
						<li><a class="soc-twitter" href="#"></a></li>
						<!--
						<li><a class="soc-youtube" href="#"></a></li>
						<li><a class="soc-instagram soc-icon-last" href="#"></a></li>
						-->
					</ul>
					<p>Follow us on</p>
				</div>
			</div>

			<div class="next-fixture-holder">
				<div class="next-fixture">
					<h4>Upcoming fixtures:</h4>
					<?php get_upcoming_fixtures(3); ?>
				</div>
			</div>

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<h3 class="menu-toggle"><?php _e( 'Menu', 'twentytwelve' ); ?></h3>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
			</nav><!-- #site-navigation -->

		</header><!-- #masthead -->


		<div id="page" class="hfeed site">
			<?php echo slider_pro(1, array("width"=>"100%", "height"=>300, "effect_type"=>"fade"));  ?>


			<div id="main" class="wrapper">
