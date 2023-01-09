<?php

	/*
		Template Name: Single Event
	*/

	include_once WP_PLUGIN_DIR . '/adventi-events/event.php';


	get_header();?>

	<div id="content-wrapper">
		<div id="content">
			<?php if (have_posts()) : while (have_posts()) : the_post(); 
				$event = AdventiEvent::from_post($post->ID);

			?>
			<div class="entry" style="margin-top: 25px">
				<?php the_content(); ?>
			</div>
			<?php endwhile; endif; ?>
		</div>
		<div class="sidebar">
			<?php echo do_shortcode('[ad_ev_sidebar]')?>
		</div>
	</div>
	<?php	get_footer();
?>