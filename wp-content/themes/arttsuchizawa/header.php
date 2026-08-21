<?php
/**
 * The header for our theme
 */
global $aAreaPriceData;

$is_front  = ! is_paged() && ( is_front_page() || ( is_home() && ( (int) get_option( 'page_for_posts' ) !== get_queried_object_id() ) ) );
$site_name = get_bloginfo( 'name', 'display' );
$post_name = $post && $post->post_name && ctype_alnum($post->post_name)? $post->post_name: '';
$body_class[] = $post_name;
$accessible_id = !empty(get_page_by_path('/accessible'))? get_page_by_path('/accessible')->ID: 0;
if( post_password_required() ) {
	$body_class[] = 'password_required';
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/print.css" media="print" type="text/css" />
<style>@page {margin: 0; size: A4 <?php echo is_page(array('receipt'))? 'landscape': 'portrait'; ?>; }</style>
</head>

<body <?php body_class($body_class); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">

<?php if( !post_password_required() ): ?>
<header id="masthead" class="site-header">

<div class="custom-header">

<div class="custom-header-media">
<div id="wp-custom-header" class="wp-custom-header">
<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/main-image.jpg" width="1920" height="1272" alt="" sizes="(max-width: 767px) 200vw, 100vw" decoding="async" fetchpriority="high" />
</div>
</div>

<div class="site-branding">
<div class="wrap">
<?php the_custom_logo(); ?>

<div class="site-branding-text">
<?php if( $site_name && is_front_page() ) : ?>
<h1 class="site-title"><a href="<?php echo esc_url(get_permalink(3)); ?>"><?php echo $site_name; ?></a></h1>
<?php elseif( $site_name && $post && $accessible_id === $post->post_parent ) : ?>
<p class="site-title"><?php echo $site_name; ?></p>
<?php elseif( $site_name ) : ?>
<p class="site-title"><a href="<?php echo esc_url(home_url( '/' )); ?>" rel="home" <?php echo $is_front ? 'aria-current="page"' : ''; ?>><?php echo $site_name; ?></a></p>
<?php endif; // ( $site_name && is_front_page() ) ?>
</div><!-- .site-branding-text -->
</div><!-- .wrap -->
</div><!-- .site-branding -->

</div><!-- .custom-header -->

<?php if( has_nav_menu( 'top' ) && !is_front_page() ) : ?>
<div class="navigation-top">
<div class="wrap">

<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'twentyseventeen' ); ?>">
<button class="menu-toggle" aria-controls="top-menu" aria-expanded="false"><?php
echo twentyseventeen_get_svg( array( 'icon' => 'bars' ) );
echo twentyseventeen_get_svg( array( 'icon' => 'close' ) );
_e( 'Menu', 'twentyseventeen' );
?></button>
<?php
wp_nav_menu(
	array(
		'theme_location' => 'top',
		'menu_id'        => 'top-menu',
	)
);
?>
</nav><!-- #site-navigation -->

</div><!-- .wrap -->
</div><!-- .navigation-top -->
<?php endif; // ( has_nav_menu( 'top' ) && !is_front_page() ) ?>

</header><!-- #masthead -->
<?php endif; // ( !post_password_required() ) ?>

<div class="site-content-contain">
<div id="content" class="site-content">
