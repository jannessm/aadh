<?php
/*
   Template Name: Posts
*/
?>
	<?php get_header();?>

	<div id="content-wrapper">
	  <div id="content">
      

      <?php the_content(); ?>

      </div>
      <?php
		$sidebar = get_post_meta( $post->ID, 'aadh_sidebar', true);

		if (!!$sidebar) {
			?>
			<div class="sidebar">
				<?php echo do_shortcode('[ad_ev_sidebar]');?>
			</div>
			<?php
		}
		?>
   </div>
	<?php	get_footer();?>