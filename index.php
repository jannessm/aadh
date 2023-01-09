<?php
	get_header(); ?>

	<div id="content-wrapper">
	<?php
		$echoed_sidebar = false;
		while (have_posts()) {
			the_post();
			$sidebar = PageMetaBox::sidebar($post->ID);

			if (!$echoed_sidebar && $sidebar) {
				?><div id="content"><?php
				$echoed_sidebar = true;
			}

			?>
   			<div class="entry">
      			<?php the_content(); ?>
   			</div>
			<?php
		}

	
		if ($echoed_sidebar) {
			?>
			</div>
			<div class="sidebar">
				<?php echo do_shortcode('[ad_ev_sidebar]');?>
			</div>
			<?php
		}
	?>
   	</div>
	<?php get_footer();?>