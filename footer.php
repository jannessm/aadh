		<div id="social-wrapper">
		<div id="social-medias">
			<?php
				 $options = get_option('footer_theme_options');

				 $socials = array('facebook', 'youtube', 'email', 'custom', 'cal');

				 foreach ($socials as $key) {

				 	if(isset($options[$key . '_bool']) && $options[$key . '_bool'] === $key)
				 		echo '<a href="'.$options[$key . '_link'].'" target="_blank"><div id="'.$key.'"></div></a>';
				 }
			?>
		</div>
		</div>
	<div id="footer">

		<a href="<?php echo get_home_url() ?>" style="position:relative;z-index:1;"><div id="footer_logo" 
				style="background-image: url('<?php site_icon_url(); ?>')">
			</div></a>
		<?php //wp_nav_menu( array( 'menu' => 'footer-menu' , 'theme_location' => 'footer-menu', 'walker' => new WP_Custom_Walker('main-menu', 'main-menu-item', false), 'items_wrap' => '%3$s') );?>
		<div id="footer-content">
			<?php echo $options['text']; ?>
			<br>
			<br>
			<?php echo $options['address'];?>
		</div>
	</div>
	<?php wp_footer();?>
</body>
</html>