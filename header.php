<!DOCTYPE html>
<html <?php language_attributes(); ?> >
	<head>
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

		<title><?php bloginfo('name'); ?></title>
		
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

		<meta name="viewport" content="width=device-width, user-scalable=no">

		<?php wp_head(); ?>
	</head>
	
<?php
/*
	body
*/
?>
	<body <?php body_class();?> >
		<?php
			global $wp_query;
			$img = get_post_meta($post->ID, 'aadh_header_img', true);
			$event_img = get_post_meta($post->ID, '_ad_ev_meta_image', true);
			$img_id = !!$event_img ? $event_img : $img;
	
			$brightness = get_post_meta($post->ID, 'aadh_brightness', true);
			$brightness = !!$brightness ? (float) $brightness : 0.2;

			$text = get_post_meta($post->ID, 'aadh_title', true);
			if (!$text) {
				$text = "<h1>".the_title('','', false)."</h1>";
			}
			
			if ($img_id) {
				$img = wp_get_attachment_url( $img_id, 'medium_large' );
			}

/*
	header
*/
		?>

		<div id="header" 
			style="background-image: url( '<?php echo $img; ?>' )">
		<div id="background-layer" 
			style="opacity: <?php echo $brightness; ?>">
		</div>

			<a href="<?php echo get_home_url() ?>" style="position:relative;z-index:1;"><div id="header_logo" 
				style="background-image: url('<?php echo get_site_icon_url(1024); ?>')">
			</div></a>
			<div id="location" class="main-menu-item"></div>
			<?php

				wp_nav_menu( array( 'menu' => 'main-menu' , 'theme_location' => 'main-menu', 'walker' => new WP_Custom_Walker('main-menu', array('main-menu-item'), false), 'items_wrap' => '%3$s' ));
			?>

			<div id="header-content"><?php echo $text; ?></div>
                        <div id="arrow-down" onclick="scrollDown('#content-wrapper');"></div>
		</div>
		<div id="header-collapse-icon" onclick="toggle_burger_collapse()">
			<div class="collapse-icon-top"></div>
			<div class="collapse-icon-middle"></div>
			<div class="collapse-icon-bottom"></div>
		</div>
<?php
/*
	collapse menu
*/
?>
		<div id="collapse-menu-wrapper">
		<?php
			$collapse_walker = new WP_Custom_Walker('collapse-menu', array('collapse-menu-item-0', 'collapse-menu-item-1'), true);
			wp_nav_menu( array( 'menu' => 'collapse-menu', 'theme_location' => 'header-collapse', 'walker' => $collapse_walker, 'items_wrap' => '%3$s') );
		echo '<div id="social-medias-collapse">';
			 $options = get_option('footer_theme_options');

			 $socials = array(
			 		'facebook' => array('facebook_bool', 'facebook_link'),
			 		'youtube' => array('youtube_bool', 'youtube_link'),
			 		'email' => array('email_bool', 'email_link'),
			 		'own' => array('own_bool', 'own_link')
			 );

			 foreach ($socials as $key => $value) {
			 	if(array_key_exists($value[0], $options) && strcmp($options[$value[0]], $key)==0)
			 		echo '<a href="'.$options[$value[1]].'" target="_blank"><div id="'.$key.'_collapse"></div></a>';
			 }
			?>
		</div>
		</div>